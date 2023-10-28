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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('access', 'toDoList', 'Oooops, it looks like you\'re trying to access things you don\'t have permission for... 🧐 Get out of here little freak !! 🔙', 404)]
    public function toDoListTasks(ToDoList $toDoList, ToDoListRepository $toDoListRepository): Response
    {
        return $this->render('to_do_list/to_to_list_tasks.html.twig', [
            'toDoList' => $toDoList,
        ]);
    }

    #[Route('/todolist/{id}/edit', name: 'app_edit_to_do_list')]
    #[IsGranted('access', 'toDoList', 'Oooops, it looks like you\'re trying to access things you don\'t have permission for... 🧐 Get out of here little freak !! 🔙', 404)]
    public function editToDoList(ToDoList $toDoList, Request $request, EntityManagerInterface $entityManager): Response
    {
        if($toDoList === null) {
            return $this->redirectToRoute('app_to_do_list'); //si la liste n'existe pas on redirige vers la liste des listes
        }

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

    #[Route('/todolist/{id}/delete', name: 'app_delete_to_do_list')]
    #[IsGranted('access', 'toDoList', 'Oooops, it looks like you\'re trying to access things you don\'t have permission for... 🧐 Get out of here little freak !! 🔙', 404)]
    public function deleteToDoList(int $id, ToDoListRepository $toDoListRepository, EntityManagerInterface $entityManager, ToDoList $toDoList): Response
    {
        $toDoList = $toDoListRepository->find($id);
        if($toDoList === null) {
            return $this->redirectToRoute('app_to_do_list'); //si la liste n'existe pas on redirige vers la liste des listes
        }

        $entityManager->remove($toDoList);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

}
