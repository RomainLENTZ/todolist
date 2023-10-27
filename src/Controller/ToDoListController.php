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

        if($this->getUser() != null){
            $user = $this->getUser();
            $userToDoList = $user->getToDoLists();
            return $this->render('to_do_list/index.html.twig', [
                'toDoLists' => $userToDoList,
            ]);
        }

        return $this->redirectToRoute('app_login');

    }


    #[Route('/todolist/add', name: 'app_add_to_do_list')]
    public function addToDoList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todolist = new ToDoList();
        $addToDoListForm = $this->createForm(ToDoListType::class, $todolist);
        $addToDoListForm->handleRequest($request);

        if ($addToDoListForm->isSubmitted() && $addToDoListForm->isValid()) {

            $todolist = $addToDoListForm->getData();
            $todolist->setUser($this->getUser());

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

}
