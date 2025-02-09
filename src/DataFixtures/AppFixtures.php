<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task()
                    ->setTitle("Fixture Task")
                    ->setIsCompleted()
                    ->setCreatedAt();

        $manager->persist($task);

        $manager->flush();
    }
}
