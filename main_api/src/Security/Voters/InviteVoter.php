<?php
declare(strict_types=1);
namespace App\Security\Voters;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use \App\Security\User as SessionUser;

class InviteVoter extends Voter
{
    const MODIFY = 'modifyInvite';

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::MODIFY])) {
            return false;
        }

        if (!$subject === User::class) {
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
            case self::MODIFY:
                return $this->modifyInvite($user, $subject);
        }
    }

    private function modifyInvite(SessionUser $user, User $invite)
    {
        return ($user->getRoles() !== $invite->getRoles()) && in_array($user->getRoles(), [$invite::ROLE_ADMIN, $invite::ROLE_SUPER_ADMIN]);
    }

}