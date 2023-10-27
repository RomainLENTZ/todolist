<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Entity\User;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ToDoListController extends AbstractController
{
    #[Route('/todolist', name: 'app_to_do_list')]
    public function index(ToDoListRepository $repository): Response
    {
        return $this->render('to_do_list/index.html.twig', [
            'toDoLists' => $repository->findAll(),
        ]);
    }


    #[Route('/todolist/add', name: 'app_add_to_do_list')]
    public function addToDoList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todolist = new ToDoList();
        $addToDoListForm = $this->createForm(ToDoListType::class, $todolist);
        $addToDoListForm->handleRequest($request);

        if ($addToDoListForm->isSubmitted() && $addToDoListForm->isValid()) {

            $todolist = $addToDoListForm->getData();
            $todolist->setUser(new User());
            $entityManager->persist($todolist);
            $entityManager->flush();

            return $this->redirectToRoute('app_to_do_list');
        }


        return $this->render('to_do_list/add_to_do.html.twig', [
            "addForm" => $addToDoListForm->createView(),
        ]);
    }

    #[Route('/todolist/{id}/tasks', name: 'app_to_do_list_tasks')]
    public function toDoListTasks(int $id, ToDoListRepository $toDoListRepository): Response
    {
        $toDoList = $toDoListRepository->find($id);

        return $this->render('to_do_list/to_to_list_tasks.html.twig', [
            'toDoList' => $toDoList,
        ]);
    }

    #[Route('/todolist/{id}/edit', name: 'app_edit_to_do_list')]
    public function editToDoList(int $id, ToDoListRepository $toDoListRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $toDoList = $toDoListRepository->find($id);

        $form = $this->createForm(ToDoListType::class, $toDoList);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_to_do_list');
        }

        return $this->render('to_do_list/edit_to_do_list.html.twig', [
            'toDoList' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

}
