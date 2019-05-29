<?php

namespace App\Security\Voter;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CATEGORY_EDIT', 'CATEGORY_CREATE', 'CATEGORY_DELETE'])
            && $subject instanceof \App\Entity\Category;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
         * @var $user User
         */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'CATEGORY_CREATE':
                return $this->canCreate($user);
                break;
            case 'CATEGORY_DELETE':
                return $this->canDelete($user);
                break;
            case 'CATEGORY_EDIT' :
                return $this->canEdit($user);
                break;
        }

        return false;
    }

    private function canCreate(User $user){

        return in_array('ROLE_ADMIN', $user->getRoles());
    }
    private function canDelete(User $user){
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
    private function canEdit(User $user){
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
