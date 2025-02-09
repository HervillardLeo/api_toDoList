<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return new JsonResponse($jsonTaskList, Response::HTTP_OK, [], true );
    }
}
