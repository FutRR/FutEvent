<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';


    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        //if the attribute isn't the one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        // only vote on 'Event' objects
        if (!$subject instanceof Event){
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User){
            //user must be logged in; if not, deny access
            $vote?->addReason('User must be logged in.');
            return false;
        }

        $event = $subject;

        return match($attribute){
            self::VIEW => $this->canView($event, $user),
            self::EDIT => $this->canEdit($event, $user),
            self::DELETE => $this->canDelete($event, $user),
            default => throw new \LogicException('This code should be unreachable!')
        };
    }

    private function canView(Event $event, User $user): bool
    {
        //if the user can edit, they can view the event
        if ($this->canEdit($event, $user)){
            return true;
        }

        // if the event is private, only the creator can view it
        return !$event->isPrivate() || $event->getCreator() === $user;
    }

    private function canEdit(Event $event, User $user, ?Vote $vote = null): bool
    {
        if ($event->getCreator() === $user){
            return true;
        }

        $vote?->addReason('The logged in user must be the creator of the event.');
        return false;
    }

    private function canDelete(Event $event, User $user, ?Vote $vote = null): bool
    {
        if ($this->canEdit($event, $user)){
            return true;
        }

        $vote?->addReason('The logged in user must be the creator of the event.');
        return false;
    }

}
