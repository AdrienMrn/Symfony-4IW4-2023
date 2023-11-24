<?php

namespace App\Security\Voter;

use App\Entity\Proof;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\matches;

class ProofVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Proof;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::EDIT, self::DELETE => $this->canModify($subject, $user),
            default => false,
        };
    }

    private function canModify(Proof $proof, UserInterface $user): bool
    {
        return in_array('ROLE_COORDINATOR', $user->getRoles()) or ($proof->getOwner() === $user and !$proof->isValidate());
    }
}
