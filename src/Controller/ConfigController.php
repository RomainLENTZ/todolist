<?php

namespace App\Controller;

use App\Form\ConfigType;
use App\Model\Config;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ConfigController extends AbstractController
{
    #[Route('/config', name: 'app_config')]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $config = new Config();
        $configForm = $this->createForm(ConfigType::class, $config);

        $configForm->handleRequest($request);
        if ($configForm->isSubmitted() && $configForm->isValid()) {
            $config = $configForm->getData();

            $serializedData = $serializer->serialize($config, "json");
            return $serializedData;

            return $this->redirectToRoute('task_success');
        }


        return $this->render('config/index.html.twig', [
            'configForm' => $configForm->createView(),
        ]);
    }
}
