<?php

namespace Ontic\NoFraud\Controllers;

use Ontic\NoFraud\Utils;
use Ontic\NoFraud\Utils\PluginUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssessmentController extends BaseController
{
    /**
     * @return Response
     */
    public function defaultAction()
    {
        $data = static::parseRequestData($this->getRequest());
        if($data === null)
        {
            return new Response('400 Bad Request', 400);
        }

        $cumulativeScore = 0;
        $cumulativeWeight = 0;

        $plugins = PluginUtils::loadPlugins();

        // Give all the plugins a chance to append new data
        // to the payload
        foreach($plugins as $plugin)
        {
            $data = $plugin->augment($data);
        }

        // Retrieve the assessments
        foreach($plugins as $plugin)
        {
            $assessment = $plugin->assess($data);

            if($assessment === null)
            {
                // The plugin couldn't make an assessment, skip it
                continue;
            }

            if($assessment->isAuthoritative() && $plugin->isAuthoritative())
            {
                // The plugin has made an authorizative assessment,
                // so just return its score
                return static::createResponse($assessment->getScore());
            }

            $cumulativeScore += ($assessment->getScore() * $plugin->getWeight());
            $cumulativeWeight += $plugin->getWeight();
        }

        $score = round($cumulativeScore / $cumulativeWeight);

        return static::createResponse($score);
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    private static function parseRequestData(Request $request)
    {
        $requestBody = $request->getContent();
        $data = json_decode($requestBody, true);
        return $data;
    }

    private static function createResponse($score)
    {
        return new JsonResponse([
            'assessment' => $score
        ]);
    }
}