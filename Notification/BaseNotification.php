<?php

namespace Jamesi\NotificationBundle\Notification;

use Symfony\Component\Templating\EngineInterface;

abstract class BaseNotification implements NotificationInterface
{
    protected $recipient;
    
    /**
     * @var EngineInterface
     */
    protected $templating;

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }
    
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
    
    public function getType()
    {
        $cname = get_class($this);
        $parts = explode('\\', $cname);
        $cname = $parts[count($parts)-1];
            
        $cname = str_replace('Notification', '', $cname);
        
        return $cname;
    }

    public function meetsRequirements()
    {
        return true;
    }

    public function getTemplateVars()
    {
        return array();
    }

    public function getAlertContentTemplate()
    {
        return null;
    }

    public function getEmailSubjectTemplate()
    {
        return null;
    }

    public function getEmailPlainContentTemplate()
    {
        return null;
    }

    public function getEmailHtmlContentTemplate()
    {
        return null;
    }

    public function getAlertContent()
    {
        if ($template = $this->getAlertContentTemplate()) {
            return $this->templating->render($template, $this->getTemplateVars());
        }
        return null;
    }

    public function getEmailSubject()
    {
        if ($template = $this->getEmailSubjectTemplate()) {
            return $this->templating->render($template, $this->getTemplateVars());
        }
        return null;
    }

    public function getEmailPlainContent()
    {
        if ($template = $this->getEmailPlainContentTemplate()) {
            return $this->templating->render($template, $this->getTemplateVars());
        }
        return null;
    }

    public function getEmailHtmlContent()
    {
        if ($template = $this->getEmailHtmlContentTemplate()) {
            return $this->templating->render($template, $this->getTemplateVars());
        }
        return null;
    }
}