<?php

namespace Jamesi\NotificationBundle\Notification;

use Symfony\Component\Templating\EngineInterface;

interface NotificationInterface
{
    /**
     * Returns camelcase class name without "Notification" suffix
     * 
     * @return string 
     */
    function getType();
    
    /**
     * Set the user who should receive the alert and email
     * 
     * @param $recipient
     */
    function setRecipient($recipient);
    
    function setTemplating(EngineInterface $templating);

    /**
     * Check whether the attribute vals are sufficient
     *
     * Can be used as a safety measure to avoid templates breaking during
     * notification generation
     *
     * @return bool True if requirements are met
     */
    public function meetsRequirements();

    /**
     * Create an array of values to pass to the template renderer
     *
     * @return array
     */
    public function getTemplateVars();

    /**
     * Path to twig template file, used by default in getAlertContent
     *
     * @return string
     */
    public function getAlertContentTemplate();

    public function getEmailSubjectTemplate();

    public function getEmailPlainContentTemplate();

    public function getEmailHtmlContentTemplate();

    /**
     * Render a twig template or something to generate the content of the
     * alert with all placeholders replaced
     * 
     * @return string 
     */
    function getAlertContent();
    
    function getEmailSubject();
    
    function getEmailPlainContent();
    
    function getEmailHtmlContent();
}