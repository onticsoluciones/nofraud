<?php

namespace Ontic\NoFraud\Controllers;

use Symfony\Component\HttpFoundation\Response;

interface IController
{
    /**
     * @return Response
     */
    public function defaultAction();
}
