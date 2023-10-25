<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PersonController extends AbstractController
{
    #[Route('/person', name: 'app_person')]
    public function index(SerializerInterface $serializer): Response
    {
        return $this->render('person/index.html.twig');
    }

    #[Route('/person/encode', name: 'app_person_encode')]
    public function encode(): Response
    {
        $normalizer = [new ObjectNormalizer()];
        $encoder = [new JsonEncoder()];

        $serializer = new Serializer($normalizer, $encoder);

        $person = new Person();
        $person->setName('Romain');
        $person->setAge(22);
        $person->setEye('blue');
        $person->setHair('Brown');
        $person->setSkin('white');

        // NE PAS FILTRER LES ATTRIBUTS
         //$jsonContent = $serializer->serialize($person, 'json');

        // IGNORER
        //$jsonContent = $serializer->serialize($person, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['age']]);

        // FILTRER PAR ATTRIBUT
         //$jsonContent = $serializer->serialize($person, 'json',  [AbstractNormalizer::ATTRIBUTES => ['eye']]);

        // FILTRER PAR GROUPE
         $jsonContent = $serializer->serialize($person, 'json', [AbstractNormalizer::GROUPS => ['passport']]);


        return $this->render('person/encod.html.twig', [
            'person' => $person, 'json_content' => $jsonContent,
        ]);
    }

    #[Route('/person/deserialize', name: 'app_person_deserialize')]
    public function decode(SerializerInterface $serializer): Response
    {
        $data = <<<EOF
            <person>
                <name>Romain</name>
                <age>22</age>
            </person>
        EOF;

        $person = $serializer->deserialize($data, Person::class, 'xml');

        //dd($person);

        return $this->render('person/deserialize.html.twig', [
            'personxml' => $data,
            'personObject' => $person,
        ]);
    }
}
