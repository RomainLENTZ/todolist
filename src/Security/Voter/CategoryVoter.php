<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter
{
    public const ACCESS = 'access';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == self::ACCESS
            && $subject instanceof \App\Entity\Category;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($attribute == self::ACCESS){
            if($subject->getUser() == $user){
                return true;
            }
        }

        return false;
    }
}
