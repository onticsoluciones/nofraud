<?php

namespace Ontic\NoFraud\Controllers;

use Ontic\NoFraud\Utils;
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

        $scores = [];

        $plugins = Utils::getInstalledPlugins();

        foreach($plugins as $plugin)
        {
            $data = $plugin->augment($data);
            $assessment = $plugin->assess($data);
            if($assessment !== null)
            {
                if($assessment->isAuthoritative())
                {
                    return static::createResponse($assessment->getScore());
                }

                $scores[] = $assessment->getScore();
            }
        }

        if(count($scores) > 0)
        {
            $score = (int) (array_sum($scores) / count($scores));
        }
        else
        {
            $score = null;
        }

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