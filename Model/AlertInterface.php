<?php

namespace Jamesi\NotificationBundle\Model;

use Jamesi\NotificationBundle\Model\NotifiableInterface;

/**
 * The parts of the Alert entity which the Notifier requires to be implemented
 *
 * @see \Jamesi\NotificationBundle\Entity\Alert
 * @see \Jamesi\NotificationBundle\Notifier
 */
interface AlertInterface
{
    /**
     * @param NotifiableInterface $user
     */
    public function setUser(NotifiableInterface $user);

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @param string $content
     */
    public function setContent($content);
}
