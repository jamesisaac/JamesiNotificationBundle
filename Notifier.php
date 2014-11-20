<?php

namespace Jamesi\NotificationBundle;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Jamesi\NotificationBundle\Entity\Alert;
use Jamesi\NotificationBundle\Model\AlertInterface;
use Jamesi\NotificationBundle\Model\NotifiableInterface;
use Jamesi\NotificationBundle\Notification\NotificationInterface;

/**
 * Carries out of the logic of sending out Alerts/Emails when provided a Notification
 */
class Notifier
{
    protected $doctrine;
    
    protected $mailer;
    
    protected $templating;

    protected $config;
    
    public function __construct(Doctrine $doctrine, \Swift_Mailer $mailer,
            EngineInterface $templating, array $config)
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->config = $config;
    }

    /**
     * Something has happened - notify a user about this
     *
     * @param NotifiableInterface $recipient      The user who should be notified
     * @param NotificationInterface $notification The Notification with alert/email contents
     */
    public function notify(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        // See if the user accepts it
        $type = $notification->getType();
        $acceptAlert = $recipient->acceptsNotificationAlert($type);
        $acceptEmail = $recipient->acceptsNotificationEmail($type);

        // Check if the notification is valid
        if (!$notification->meetsRequirements()) {
            return;
        }
        
        // Generate and send
        $notification->setRecipient($recipient);
        $notification->setTemplating($this->templating);
        
        if ($acceptAlert) $this->notifyAlert($recipient, $notification);
        if ($acceptEmail) $this->notifyEmail($recipient, $notification);
    }
    
    protected function notifyAlert(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $alertContent = $notification->getAlertContent();
        if ($alertContent)
        {
            /** @var AlertInterface $alert */
            $alert = new $this->config['alert_class']();
            $alert->setUser($recipient);
            $alert->setType($notification->getType());
            $alert->setContent($alertContent);

            $em = $this->doctrine->getManager();
            $em->persist($alert);
            $em->flush();
        }
    }
    
    protected function notifyEmail(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $emailSubject = $notification->getEmailSubject();
        if ($emailSubject)
        {
            $email = \Swift_Message::newInstance()
                ->setSubject($emailSubject)
                ->setFrom(array($this->config['from_email']['address']
                    => $this->config['from_email']['sender_name']))
                ->setTo(array($recipient->getNotificationEmail() => $recipient->getNotificationName()))
                ->setBody($notification->getEmailPlainContent())
            ;

            $html = $notification->getEmailHtmlContent();
            if ($html) $email->addPart($html, 'text/html');

            $this->mailer->send($email);
        }
    }
}