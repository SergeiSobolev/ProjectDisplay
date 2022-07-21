<?php

namespace App\EventListener;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class OrderEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::postUpdate,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getEntityManager();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($entity instanceof Order) {

            if (array_key_exists('status', $args->getEntityChangeSet())) {
                if (
                    array_search(Order::STATUS_CART, $args->getEntityChangeSet()['status']) === 0 &&
                    array_search(Order::STATUS_PROCESSING, $args->getEntityChangeSet()['status']) === 1
                ) {
                    $orderItemsCartNow = $em->getRepository(OrderItem::class)->findBy(['orderRef' => $entity->getId()]);
                    foreach ($orderItemsCartNow as $orderItem) {
                        $product = $orderItem->getProduct();
                        $productValueWarehouse = $product->getWarehouse();

                        $newProductValueWarehouse = $productValueWarehouse - $orderItem->getQuantity();
                        $product->setWarehouse($newProductValueWarehouse);
                    }
                }
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $args->getEntityManager()->flush();
    }
}