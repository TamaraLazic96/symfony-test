<?php

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class LikeNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof MicroPost)
                continue;

            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName'])
                continue;

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff))
                return;

            /** @var MicroPost $post */
            $post = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($post->getUser());
            $notification->setPost($post);
            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}