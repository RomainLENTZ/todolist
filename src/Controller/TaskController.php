<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\CategoryRepository;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(Request $request): Response
    {
//        dd($request->getSession()->get("config"));
        $configJson = $request->getSession()->get("config");
        return $this->render('task/index.html.twig', [
            'configJson' => $configJson
        ]);
    }


    #[Route('/task/{toDoListId}/add', name: 'app_add_task')]
    public function addTask(int $toDoListId, Request $request, EntityManagerInterface $entityManager, ToDoListRepository $toDoListRepository): Response
    {
        $task = new Task();

        $toDoList = $toDoListRepository->find($toDoListId);
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

            return $this->redirectToRoute('app_to_do_list');
        }
        return $this->render('task/add_task.html.twig', [
            'form' => $form
        ]);
    }
}
