<?php

namespace App\Controller;

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
}
