<?php
declare(strict_types=1);

namespace App\Security\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Security\User as SessionUser;

class UserVoter extends Voter
{
    const LOCK = 'modifyUserLock';
    const MODIFY = 'modifyUserRoles';
    const SET_NEW_ROLE = 'setNewUserRoles';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::LOCK, self::MODIFY, self::SET_NEW_ROLE])) {
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
        if (!$tokenUser instanceof SessionUser) {
            return false;
        }
        switch ($attribute) {
            case self::LOCK:
                return $this->modifyUserLock($tokenUser, $subject);
            case self::MODIFY || self::SET_NEW_ROLE:
                return $this->modifyUserRoles($tokenUser, $subject);
        }
    }

    private function modifyUserLock(SessionUser $user, User $lockedUser)
    {
         if($user->getId() ===  $lockedUser->getId())
            return false;

        $userRole = $user->getRoles();
        $lockedUserRole = $lockedUser->getRoles();

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

    private function modifyUserRoles(SessionUser $user, User $lockedUser)
    {
        if($user->getId() === $lockedUser->getId())
            return false;

        $userRole = $user->getRoles();
        $lockedUserRole = $lockedUser->getRoles();

        switch ($userRole){
            case User::ROLE_SUPER_ADMIN:
                return $lockedUserRole !== User::ROLE_SUPER_ADMIN;
            case User::ROLE_ADMIN:
                return !in_array($lockedUserRole, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
        }
        return false;

    }

}