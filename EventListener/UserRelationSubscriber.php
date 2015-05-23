<?php

namespace ARV\BlogBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

class UserRelationSubscriber implements EventSubscriber
{

    protected $userClass;

    public function __construct($userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy()
        ;

        // Add relation with custom entity User
        if ($metadata->getName() == 'ARV\BlogBundle\Entity\Article') {
            // Check user_class parameter
            if ($this->userClass !== null) {
                $metadata->mapManyToOne(array(
                    'targetEntity'  => $this->userClass,
                    'fieldName'     => 'user',
                    'joinColumn'    => array(
                        'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                        'referencedColumnName' => $namingStrategy->referenceColumnName()
                    )
                ));
            }
        }

        // Add relation with custom entity User
        if ($metadata->getName() == 'ARV\BlogBundle\Entity\Comment') {
            // Check user_class parameter
            if ($this->userClass !== null) {
                $metadata->mapManyToOne(array(
                    'targetEntity'  => $this->userClass,
                    'fieldName'     => 'user',
                    'joinColumn'    => array(
                        'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
                        'referencedColumnName' => $namingStrategy->referenceColumnName()
                    )
                ));
            }
        }
    }

}
