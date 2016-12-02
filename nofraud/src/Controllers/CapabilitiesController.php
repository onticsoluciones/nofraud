<?php

namespace Ontic\NoFraud\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CapabilitiesController implements IController
{
    /**
     * @return Response
     */
    public function defaultAction()
    {
        return new JsonResponse([]);
    }
}