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
            return $this->redirectToRoute('app_to_do_list_tasks', ['id' => $toDoList->getId()]);
        }
        return $this->render('task/add_task.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/task/{id}/edit', name: 'app_edit_task')]
    public function editTask(int $id, Request $request, EntityManagerInterface $entityManager, ToDoListRepository $toDoListRepository, CategoryRepository $categoryRepository): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $toDoList = $toDoListRepository->find($task->getToDoList()->getId());
        $category = $categoryRepository->find($task->getCategory()->getId());

        if ($form->isSubmitted() && $form->isValid()) {

            if($toDoList == null){
                return $this->render('task/edit_task.html.twig', [
                    'form' => $form
                ]);
            }

            $task = $form->getData();
            $task->setToDoList($toDoList);
            $task->setCategory($category);
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_to_do_list_tasks', ['id' => $toDoList->getId()]);
        }
        return $this->render('task/edit_task.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/task/{id}/delete', name: 'app_delete_task')]
    public function deleteTask(int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('app_to_do_list_tasks', ['id' => $task->getToDoList()->getId()]);
    }

    #[Route('/task/{id}/close', name: 'app_close_task')]
    public function closeTask(int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setClosed(true);
        $entityManager->persist($task);
        $entityManager->flush();
        return $this->redirectToRoute('app_to_do_list_tasks', ['id' => $task->getToDoList()->getId()]);
    }
}
