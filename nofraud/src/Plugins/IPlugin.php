<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Model\Assessment;

interface IPlugin
{
    /**
     * @return string
     */
    function getCode();

    /**
     * @param string[] $configuration
     */
    function configure($configuration);

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