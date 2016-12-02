<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Model\Assessment;

interface IPlugin
{
    /**
     * @return string[]
     */
    function getProvidedFields();

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data);

    /**
     * @param $data
     * @return mixed
     */
    function augment($data);
}