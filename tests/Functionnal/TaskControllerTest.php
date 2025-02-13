<?php

namespace App\Tests\Functional;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testListTasks(): void
    {
        $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testAddTask(): void
    {
        $this->client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'New Task'
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($this->client->getResponse()->getContent());

        $newTask = $this->entityManager->getRepository(Task::class)->findOneBy(['title'=> 'New Task']);
        $this->assertNotNull($newTask);
    }

    public function testCompleteTask(): void
    {
        // Create task for test
        $task = new Task();
        $task->setTitle("Test Task");
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->assertFalse($task->isCompleted());

        $this->client->request('PATCH', "/tasks/{$task->getId()}/complete");

        $this->assertResponseStatusCodeSame(200);
        $updatedTask = $this->entityManager->getRepository(Task::class)->find($task->getId());
        $this->assertTrue($updatedTask->isCompleted());
    }

    public function testDeleteTask(): void
    {
        // Create task for test
        $task = new Task();
        $task->setTitle("Task to Delete");
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $id = $task->getId();

        $this->client->request('DELETE', "/tasks/{$id}");

        $this->assertResponseStatusCodeSame(204);
        $deletedTask = $this->entityManager->getRepository(Task::class)->find($id);
        $this->assertNull($deletedTask);
    }
}
