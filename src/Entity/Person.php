<?php

namespace App\Entity;

class Person
{
    private int $age;

    private string $name;

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