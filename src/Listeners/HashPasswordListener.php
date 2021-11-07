<?php

namespace App\Listeners;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordListener implements EventSubscriber
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $em = $args->getEntityManager();

        $entityChangeSet = $em->getUnitOfWork()->getEntityChangeSet($entity);
        if (array_key_exists('password', $entityChangeSet)) {
            [$oldValue, $newValue] = $entityChangeSet['password'];

            if (!$newValue) {
                $entity->setPassword($oldValue);  // Restore previous password value
            } else {
                $this->encodePassword($entity); // Encode new password
            }
        }

        // necessary to force the update to see the change
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity)
    {
        if (!$entity->getPassword()) {
            return;
        }

        $encoded = $this->passwordHasher->hashPassword(
            $entity,
            $entity->getPassword()
        );
        $entity->setPassword($encoded);
    }
}
