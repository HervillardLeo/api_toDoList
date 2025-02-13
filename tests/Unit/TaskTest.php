<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskEntity(): void
    {   
        $date = new \DateTime("");
        $task = new Task();
        $task->setTitle("Unit Test");
        $task->setisCompleted(true);
        $task->setCreatedAt($date);

        $this->assertSame("Unit Test", $task->getTitle());
        $this->assertTrue($task->isCompleted());
        $this->assertSame($date, $task->getCreatedAt());
    }
}
