<?php

namespace Jamesi\NotificationBundle\Model;

/**
 * Interface for an object which accepts Noticiations (i.e. a User)
 *
 * The notifiable object must broadcast whether it wants to receive each type of
 * notification (alerts and emails).
 *
 * If it does want to receive emails, it must provide a name and email address
 * for the mailer to make use of.
 */
interface NotifiableInterface
{
    /**
     * Name in the "To" field of the email
     * @return string
     */
    public function getNotificationName();

    /**
     * Email address to send notifications to
     * @return string
     */
    public function getNotificationEmail();

    /** @return bool */
    public function acceptsNotificationAlert($type);

    /** @return bool */
    public function acceptsNotificationEmail($type);
}
