<?php

namespace Ontic\NoFraud\Controllers;

use Ontic\NoFraud\Utils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CapabilitiesController extends BaseController
{
    /**
     * @return Response
     */
    public function defaultAction()
    {
        $capabilities = [];

        foreach(Utils::getInstalledPlugins() as $plugin)
        {
            foreach($plugin->getProvidedFields() as $field)
            {
                if(!isset($capabilities[$field]))
                {
                    $capabilities[] = $field;
                }
            }
        }
        return new JsonResponse($capabilities);
    }
}