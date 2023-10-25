<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

    class Person
    {
        #[Groups(['passport'])]
        private string $name;

        #[Groups(['passport'])]
        private int $age;
        #[Groups(['physique'])]
        private string $eye;

        #[Groups(['physique'])]
        private string $hair;

        #[Groups(['physique'])]
        private string $skin;

        public function getAge(): int
        {
            return $this->age;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getEye(): string
        {
            return $this->eye;
        }

        public function getHair(): string
        {
            return $this->hair;
        }

        public function getSkin(): string
        {
            return $this->skin;
        }


        public function setAge(int $age): void
        {
            $this->age = $age;
        }

        public function setName(string $name): void
        {
            $this->name = $name;
        }

        public function setEye(string $eye): void
        {
            $this->eye = $eye;
        }
        public function setHair(string $hair): void
        {
            $this->hair = $hair;
        }

        public function setSkin(string $skin): void
        {
            $this->skin = $skin;
        }

}