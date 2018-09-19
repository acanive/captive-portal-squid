<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 16-feb-17
 * Time: 10:22 a.m.
 */
namespace MyBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use MyBundle\Utils\memcache;

class LoginListener
{
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {

        $memcache = new memcache();
        $user = $event->getAuthenticationToken()->getUser();
        $memcache->addMemcache($user);
    }

}