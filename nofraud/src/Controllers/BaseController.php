<?php

namespace Ontic\NoFraud\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController implements IController
{
    /** @var Request */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
}