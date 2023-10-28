<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Form\TaskType;
use App\Repository\CategoryRepository;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(Request $request): Response
    {
        $configJson = $request->getSession()->get("config");
        return $this->render('task/index.html.twig', [
            'configJson' => $configJson
        ]);
    }


    #[Route('/task/{id}/add', name: 'app_add_task')]
    #[IsGranted('access', 'toDoList', 'Oooops, it looks like you\'re trying to access things you don\'t have permission for... 🧐 Get out of here little freak !! 🔙', 404)]
    public function addTask(ToDoList $toDoList, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($toDoList == null){
                return $this->render('task/add_task.html.twig', [
                    'form' => $form
                ]);
            }

            $task = $form->getData();
            $task->setToDoList($toDoList);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_to_do_list_tasks', ['id' => $toDoList->getId()]);
        }
        return $this->render('task/add_task.html.twig', [
            'form' => $form
        ]);
    }
}
