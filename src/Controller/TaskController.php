<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/tasks', name: 'api_tasks_')]
final class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(TaskRepository $taskRepository, SerializerInterface $serializer): JsonResponse
    {   
        $taskList = $taskRepository->findAll();
        $jsonTaskList = $serializer->serialize($taskList, 'json');
        return new JsonResponse($jsonTaskList, Response::HTTP_OK, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $task = $serializer->deserialize($request->getContent(), Task::class, 'json');
        $em->persist($task);
        $em->flush();

        return new JsonResponse($serializer->serialize($task, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}/complete', methods: ['PATCH'])]
    public function complete(Task $task, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $task->setisCompleted(true);
        $em->flush();

        return new JsonResponse($serializer->serialize($task, 'json'), Response::HTTP_CREATED, [], true);
    }
}
