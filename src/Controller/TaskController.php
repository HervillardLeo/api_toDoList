<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tasks', name: 'api_tasks_')]
final class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(TaskRepository $taskRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $taskList = $taskRepository->findAll();
            if (!$taskList) {
                return new JsonResponse(['error' => 'No tasks found'], Response::HTTP_NOT_FOUND);
            }

            $jsonTaskList = $serializer->serialize($taskList, 'json');
            return new JsonResponse($jsonTaskList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while fetching tasks'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {
            $task = $serializer->deserialize($request->getContent(), Task::class, 'json');

            // Validation des données
            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $em->persist($task);
            $em->flush();

            return new JsonResponse($serializer->serialize($task, 'json'), Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid request data or internal error'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/complete', methods: ['PATCH'])]
    public function complete(Task $task, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        try {
            $task->setIsCompleted(true);
            $em->flush();

            return new JsonResponse($serializer->serialize($task, 'json'), Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while updating the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->remove($task);
            $em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while deleting the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
