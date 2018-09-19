<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 16-feb-17
 * Time: 11:19 a.m.
 */

namespace MyBundle\Listener;

use MyBundle\Utils\memcache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use MyBundle\Listener;


class AfterLogout implements LogoutHandlerInterface {


    /**
     * This method is called by the LogoutListener when a user has requested
     * to be logged out. Usually, you would unset session variables, or remove
     * cookies, etc.
     *
     * @param Request $request
     * @param Response $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $memcache = new memcache();
        $user = $token->getUser();
        $memcache->deleteMemcache($user);

        return $response;
    }
}