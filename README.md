# NotificationBundle

This Symfony bundle allows the creation of a *notification system* for
registered members of a site.  Similar to that seen on sites like Facebook.

A **Notification** corresponds to an event which happened on the site which the
user should be made aware about.  The Notification class contains the logic to
build the views needed for the Alert and email which will be sent to the user.

An **Alert** is an on-site notification - you should implement some section on
the site where the user can see their history of Alerts, and probably let them
see their unseen Alert count at all times.

The **Notifier**, when provided with a Notification,  may fire an email and/or
Alert, depending on the user's preferences (must be implemented through
``NotifiableInterface``).

## Usage

* Add the bundle to your composer.json (note: this bundle hasn't reached a
  stable version yet:
  
``` javascript
{
    "require": {
        "jamesi/notification-bundle": "dev-master"
    }
}
```

* Add the bunlde to your AppKernel

``` php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Jamesi\NotificationBundle\JamesiNotificationBundle(),
    }
```

* Make your User class implement the ``NotifiableInterface``:

``` php
    public function getNotificationName()
    {
        // Who should the email be addressed to?
        return $this->getUsername();
    }

    public function getNotificationEmail()
    {
        // Who should the email be addressed to?
        return $this->getEmail();
    }

    public function acceptsNotificationAlert($type)
    {
        // Implement logic here for which on-site alerts the user wants
        return true;
    }

    public function acceptsNotificationEmail($type)
    {
        // Implement logic here for unsubscribing from types of email notification
        return true;
    }
```

* Create an entity which extends the provided Alert entity, give it an ID and
  an entity type (i.e. your User implementation) for the $user ManyToOne
  relationship

``` php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Jamesi\NotificationBundle\Entity\Alert as BaseAlert;

/**
 * @ORM\Entity
 * @ORM\Table(name="alert")
 */
class Alert extends BaseAlert
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;
}
```
  
* Configure the bundle in ``app/config/config.yml``:

``` yaml
jamesi_notification:
    alert_class: AppBundle\Entity\Alert
    from_email:
        address: myapp@example.com
        sender_name: My App Name
```

* Start creating Notification subclasses in your bundle (see
  ``FooNotification`` for an example)

* In your controllers, use the ``jamesi_notification.notifier`` service (an
  instance of the ``Notifier`` class) to send out notifications, using the
  ``notify`` method.