<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Article;
    }

    /**
     * @param string $attribute
     * @param Article $article
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $article, TokenInterface $token)
    {
        /**
         * @var $user User
         */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }


        if (in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }

        if ($article->getUser() === null)
        {
            return false;
        }


        switch ($attribute) {
            case 'EDIT':
                return $this->canEdit($user, $article);
                break;
            case 'DELETE':
                return $this->canDelete($user, $article);
                break;
        }

        return false;
    }

    protected function canEdit(User $user, Article $article)
    {

        return $user->getId() === $article->getUser()->getId();

    }

    protected function canDelete(User $user, Article $article)
    {

        return $user->getId() === $article->getUser()->getId();

    }
}
