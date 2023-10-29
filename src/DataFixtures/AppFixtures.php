<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadCategories($manager);
        $this->loadToDoLists($manager);
        $this->loadTasks($manager);

        $manager->flush();
    }

    private function getCategories() : array{
        return [
            ['name' => 'Travail', 'color' => '#3366FF'],
            ['name' => 'Personnel', 'color' => '#33CC33'],
            ['name' => 'Loisirs', 'color' => '#FF5733'],
            ['name' => 'Famille', 'color' => '#FF33FF'],
            ['name' => 'Sport', 'color' => '#FF3333'],
            ['name' => 'Cuisine', 'color' => '#FFCC33'],
            ['name' => 'Maison', 'color' => '#33FFCC'],
            ['name' => 'Voyage', 'color' => '#33FF66'],
            ['name' => 'Santé', 'color' => '#33FF33'],
            ['name' => 'Shopping', 'color' => '#33CCFF'],
            ['name' => 'Autres', 'color' => '#FF33CC'],
            ["name" => 'Une dernière', "color" => '#FF3366']
        ];
    }

    private function getTasks() : array
    {
        return [
            ['title' => 'Tâche 1', 'description' => 'Description de la tâche 1', 'expirationDate' => new \DateTime('2021-01-01')],
            ['title' => 'Tâche 2', 'description' => 'Description de la tâche 2', 'expirationDate' => new \DateTime('2021-01-02')],
            ['title' => 'Tâche 3', 'description' => 'Description de la tâche 3', 'expirationDate' => new \DateTime('2021-01-03')],
            ['title' => 'Tâche 4', 'description' => 'Description de la tâche 4', 'expirationDate' => new \DateTime('2021-01-04')],
            ['title' => 'Tâche 5', 'description' => 'Description de la tâche 5', 'expirationDate' => new \DateTime('2021-01-05')],
            ['title' => 'Tâche 6', 'description' => 'Description de la tâche 6', 'expirationDate' => new \DateTime('2021-01-06')],
            ['title' => 'Tâche 7', 'description' => 'Description de la tâche 7', 'expirationDate' => new \DateTime('2021-01-07')],
            ['title' => 'Tâche 8', 'description' => 'Description de la tâche 8', 'expirationDate' => new \DateTime('2021-01-08')],
            ['title' => 'Tâche 9', 'description' => 'Description de la tâche 9', 'expirationDate' => new \DateTime('2021-01-09')],
            ['title' => 'Tâche 10', 'description' => 'Description de la tâche 10', 'expirationDate' => new \DateTime('2021-01-10')],
            ['title' => 'Tâche 11', 'description' => 'Description de la tâche 11', 'expirationDate' => new \DateTime('2021-01-11')],
            ['title' => 'Tâche 12', 'description' => 'Description de la tâche 12', 'expirationDate' => new \DateTime('2021-01-12')]
        ];
    }

    private function getToDoLists() : array
    {
        return [
            ['title' => 'Liste 1'],
            ['title' => 'Liste 2'],
            ['title' => 'Liste 3'],
            ['title' => 'Liste 4'],
            ['title' => 'Liste 5'],
            ['title' => 'Liste 6'],
        ];
    }

    private function getUsers(): array
    {
        return [
            ["username" => "user1", "email" => "user1@gmail.com", "password" => "user1"],
            ["username" => "user2", "email" => "user2@gmail.com", "password" => "user2"],
            ["username" => "user3", "email" => "user3@gmail.com", "password" => "user3"],
            ["username" => "admin", "email" => "admin@gmail.com", "password" => "admin", "roles" => ["ROLE_ADMIN"]],
        ];
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $users = $this->getUsers();

        foreach ($users as $userItem) {
            $user = new User();
            $user->setUsername($userItem['username']);
            $user->setEmail($userItem['email']);
            $user->setPassword($userItem['password']);

            if (isset($userItem['roles'])) {
                $user->setRoles($userItem['roles']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager): void
    {
        $categories = $this->getCategories();

        foreach ($categories as $key => $categoryItem) {
            $category = new Category();
            $category->setName($categoryItem['name']);
            $category->setColor($categoryItem['color']);
            $category->setUser($manager->getRepository(User::class)->find($manager->getRepository(User::class)->findAll()[(int)floor($key/(4+1))])); //12 catégories pour 3 user donc on en met 4 par user
            $manager->persist($category);
            var_dump("categorie $key");
        }

        $manager->flush();
    }

    private function loadToDoLists(ObjectManager $manager): void
    {
        $toDoLists = $this->getToDoLists();

        foreach ($toDoLists as $key => $toDoListItem) {
            $toDoList = new ToDoList();
            $toDoList->setTitle($toDoListItem['title']);
            $toDoList->setUser($manager->getRepository(User::class)->find($manager->getRepository(User::class)->findAll()[(int)floor($key/(2+1))]->getId())); // 6 listes pour 3 user donc on en met 2 par user
            $manager->persist($toDoList);
            var_dump("liste $key");
        }

        $manager->flush();
    }

    private function loadTasks(ObjectManager $manager): void
    {
        $tasks = $this->getTasks();

        foreach ($tasks as $key => $taskItem) {
            $task = new Task();
            $task->setTitle($taskItem['title']);
            $task->setDescription($taskItem['description']);
            $task->setExpirationDate($taskItem['expirationDate']);
            $task->setToDoList($manager->getRepository(ToDoList::class)->find($manager->getRepository(ToDoList::class)->findAll()[(int)floor($key/(2+1))]->getId())); // 12 tâches pour 6 listes donc on en met 2 par liste
            $task->addCategory($manager->getRepository(Category::class)->find($manager->getRepository(Category::class)->findAll()[$key]->getId())); // 12 tâches pour 12 catégories donc on en met 1 par catégorie
            $manager->persist($task);
            var_dump("tâche $key");
        }

        $manager->flush();
    }


}
