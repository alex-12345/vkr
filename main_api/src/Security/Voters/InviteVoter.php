<?php
declare(strict_types=1);
namespace App\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use \App\Security\User as SessionUser;
use \App\Entity\User as InvitedUser;

class InviteVoter extends Voter
{
    const CREATE = 'create';
    const GET = 'get';
    const REMOVE = 'remove';

    const SUBJECT = 'invites';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::GET, self::REMOVE])) {
            return false;
        }

        if (!$subject === self::SUBJECT) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof SessionUser) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canInvite($user);
            case self::GET:
                return $this->getInvite($user);
            case self::REMOVE:
                return $this->removeInvite($user);
        }
    }

    private function canInvite(SessionUser $user)
    {
        return $this->getInvite($user);
    }

    private function removeInvite(SessionUser $user)
    {
        return $this->getInvite($user);
    }

    private function getInvite(SessionUser $user){
        return in_array($user->getRoles(), [InvitedUser::ROLE_ADMIN, InvitedUser::ROLE_SUPER_ADMIN]);
    }
}