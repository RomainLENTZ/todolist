<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $this->em->getRepository(User::class)->findAll(),
        ]);
    }

    //delete user
    #[Route('/admin/delete/{id}', name: 'admin_user_delete')]
    public function delete(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();
        return $this->redirectToRoute('app_admin');
    }
}
