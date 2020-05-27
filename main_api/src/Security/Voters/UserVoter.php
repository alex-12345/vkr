<?php
declare(strict_types=1);

namespace App\Security\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const LOCK = 'modifyUserLock';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::LOCK])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $tokenUser = $token->getUser();
        if (!$tokenUser instanceof \App\Security\User) {
            return false;
        }
        switch ($attribute) {
            case self::LOCK:
                return $this->modifyUserLock($tokenUser, $subject);
        }
    }

    private function modifyUserLock(\App\Security\User $user, User $lockedUser)
    {
        $userRole = $user->getRoles();
        $lockedUserRole = $lockedUser->getRoles();

        if($lockedUserRole ===  $userRole)
            return false;

        switch ($userRole){
            case User::ROLE_SUPER_ADMIN:
                return in_array($lockedUserRole, [User::ROLE_ADMIN, User::ROLE_MODERATOR, User::ROLE_USER]);
            case User::ROLE_ADMIN:
                return in_array($lockedUserRole, [User::ROLE_MODERATOR, User::ROLE_USER]);
            case User::ROLE_MODERATOR:
                return $lockedUserRole === User::ROLE_USER;
        }
        return false;
    }

}