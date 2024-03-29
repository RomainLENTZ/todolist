<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ToDoListVoter extends Voter
{
    public const ACCESS = 'access';

    protected function supports(string $attribute, mixed $subject): bool
    {

        return $attribute == self::ACCESS
            && $subject instanceof \App\Entity\ToDoList;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if($attribute == self::ACCESS){
            if($user == $subject->getUser()){
                return true;
            }
        }

        return false;
    }
}
