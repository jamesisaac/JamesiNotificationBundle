<?php

namespace Jamesi\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Jamesi\NotificationBundle\Model\AlertInterface;
use Jamesi\NotificationBundle\Model\NotifiableInterface;

/**
 * A concrete ORM implementation of the Alert
 *
 * Alerts are notifications which appear as short messages on the website itself,
 * presented through a small dropdown accessible by users on every page.
 *
 * A notification usually generates on of these alongside sending off an email
 *
 * @ORM\MappedSuperclass(repositoryClass="Jamesi\NotificationBundle\Repository\AlertRepository")
 */
class Alert implements AlertInterface
{
    /**
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * The HTML content of the alert
     *
     * @var string
     * @ORM\Column(type="text") 
     */
    protected $content;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="time_fired", type="datetime") 
     */
    protected $timeFired;
    
    /**
     * A short identifier for the type/category of this alert
     *
     * Should be camel-cased and correspond to a Notification subclass
     *
     * @var string
     * @ORM\Column(type="string") 
     */
    protected $type;
    
    /**
     * Has the user opened the alert dropdown and seen this alert?
     *
     * @var boolean
     * @ORM\Column(type="boolean") 
     */
    protected $seen;
    
    /**
     * Has the user marked this alert as deleted? (Soft delete)
     *
     * @var boolean
     * @ORM\Column(type="boolean") 
     */
    protected $deleted;
    
    
    public function __construct()
    {
        $this->seen = false;
        $this->deleted = false;
        $this->timeFired = new \DateTime();
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setUser(NotifiableInterface $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setTimeFired($timeFired)
    {
        $this->timeFired = $timeFired;
    }
    
    public function getTimeFired()
    {
        return $this->timeFired;
    }
    
    public function isValidType($type)
    {
        // TODO: Make the base path configurable (all Notifications in same folder)
        // return class_exists('Jamesi\\NotificationBundle\\Notification\\'.$type.'Notification');
        return true;
    }
    
    public function setType($type)
    {
        if (!$this->isValidType($type))
        {
            throw new \InvalidArgumentException('Bad type: ' . $type);
        }
        
        $this->type = $type;
    }
    
    /**
     * Get the type of notification the alert corresponds to
     * 
     * @param bool $forceValidity If the type isn't valid, make it valid
     * @param bool $underscore Convert from CamelCase to underscores
     * @return string 
     */
    public function getType($forceValidity = false, $underscore = false)
    {
        $type = $this->type;

        // TODO: Make this a configurable default class
        if ($forceValidity && !$this->isValidType($type)) $type = 'Misc';
        if ($underscore) $type = self::camelToUnderscore($type);
        
        return $type;
    }

    static protected function camelToUnderscore($string)
    {
        $string = preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);
        $string = preg_replace('/([A-Z])([A-Z][a-z])/', '$1_$2$3', $string);

        return strtolower($string);
    }
    
    public function setSeen($seen)
    {
        $this->seen = $seen;
    }
    
    public function isSeen()
    {
        return $this->seen;
    }
    
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
    
    public function isDeleted()
    {
        return $this->deleted;
    }
}