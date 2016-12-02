<?php

namespace Ontic\NoFraud\Controllers;

use Ontic\NoFraud\Exceptions\AuthenticationFailedException;
use Ontic\NoFraud\Model\User;
use Ontic\NoFraud\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController implements IController
{
    /** @var Request */
    private $request;
    /** @var User */
    private $user;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $username = $request->getUser();
        $password = $request->getPassword();
        $this->user = (new UserRepository())->load($username, $password);
        if($this->user === null)
        {
            throw new AuthenticationFailedException();
        }
    }

    /**
     * @return Response
     */
    public abstract function defaultAction();

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}