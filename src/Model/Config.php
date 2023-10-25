<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Config
{
    #[Assert\LessThanOrEqual(50)]
    #[Assert\NotNull(message: 'Le nombre de résultat ne peut pas être nul')]
    #[Assert\Positive(message: 'Le nombre de résultat ne peut pas être inférieur à 0')]
    private int $numberOfResult;
    #[Assert\Choice(
        choices: ['asc', 'desc'],
        message: 'La valeur choisie doit être égale "asc" ou "desc"'
    )]
    private string $sortOrder;


    public function getNumberOfResult(): int
    {
        return $this->numberOfResult;
    }
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }


    public function setNumberOfResult(int $numberOfResult): void
    {
        $this->numberOfResult = $numberOfResult;
    }
    public function setSortOrder(string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }
}