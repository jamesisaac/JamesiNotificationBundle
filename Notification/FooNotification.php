<?php

namespace Jamesi\NotificationBundle\Notification;

class FooNotification extends BaseNotification
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getTemplateVars()
    {
        return array('message' => $this->message);
    }

    public function getAlertContentTemplate()
    {
        return 'JamesiNotificationBundle::alerts/foo.html.twig';
    }

    public function getEmailPlainContentTemplate()
    {
        return 'JamesiNotificationBundle::emails/plain/foo.txt.twig';
    }

    public function getEmailSubject()
    {
        return 'Foo';
    }
}
