<?php

namespace Ontic\NoFraud\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AssessmentController implements IController
{
    /**
     * @return Response
     */
    public function defaultAction()
    {
        return new JsonResponse([]);
    }
}