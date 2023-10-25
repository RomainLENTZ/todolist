<?php

namespace App\Entity;

use Symfonyfony\Component\SERIALIZER\ANNOTATION\Ignore ;

use Symfony\Component\Serializer\Annotation\Groups;
class Person
{


    private int $age;

    #[Groups(['group1'])]
    private string $name;

    //#[Ignore]
    public string $adress;

    public function getAge(): int
    {
        return $this->age;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}