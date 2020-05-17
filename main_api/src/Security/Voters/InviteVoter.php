<?php
namespace App\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use \App\Security\User as SessionUser;
use \App\Entity\User as InvitedUser;

class InviteVoter extends Voter
{
    const CREATE = 'inviteUser';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof InvitedUser) {
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

        $userInvite = $subject;

        switch ($attribute) {
            case self::CREATE:
                return $this->canInvite($userInvite, $user);
            case self::EDIT:
                return $this->todo($userInvite, $user);
        }
    }

    private function canInvite(InvitedUser $userInvite, SessionUser $user)
    {

        return in_array($user->getRoles(), [InvitedUser::ROLE_ADMIN, InvitedUser::ROLE_SUPER_ADMIN]);
    }

    private function todo($userInvite, $user){

    }
}