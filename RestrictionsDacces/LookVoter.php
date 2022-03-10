<?php

namespace App\Security;

use App\Entity\Look;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class lookVoter extends Voter {

    const VIEW = 'view';
    const EDIT = 'edit';
    const LOOK= 'look';

    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool {

        // si l'utilisateur n'est pas un employe, renvoie false
        if (!$this->security->isGranted('ROLE_EMPLOYE')) {
            return false;
        }

        // si l'attribut n'est pas celui que nous prenons en charge, renvoie false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::LOOK])) {
            return false;
        }

        // voter uniquement sur les objets "Look"
        if (!$subject instanceof Look) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // l'utilisateur doit être connecté ; sinon, refuser l'accès
            return false;
        }

        // vous savez que $subject est un objet Look, grâce à `supports()`
        /** @var Look $look */
        $look = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView();
            case self::EDIT:
                return $this->canEdit();
            case self::LOOK:
                return $this->canLook($user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(): bool {
        // s'ils peuvent modifier, ils peuvent afficher
        if ($this->canEdit()) {
            return true;
        }
    }

    private function canEdit(): bool {
        return date('w') == 6 || date('w') == 0 || date('H:i:s') > "18:00:00" || date('H:i:s') < "09:00:00";
    }

    private function canLook(User $user): bool {
        return new \DateTime('now') > $user->getExpirationDate();
    }
}