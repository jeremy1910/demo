<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TagVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['TAG_EDIT', 'TAG_CREATE', 'TAG_DELETE'])
            && $subject instanceof \App\Entity\Tag;
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
            case 'TAG_EDIT':
                return $this->canEdit($user);
                break;
            case 'TAG_CREATE':
                return $this->canCreate($user);
                break;
            case 'TAG_DELETE':
                return $this->canDelete($user);
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
