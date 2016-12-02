<?php

namespace Ontic\NoFraud\Plugins;

abstract class BasePlugin implements IPlugin
{
    /** @var int */
    private $weight;
    /** @var boolean */
    private $isAuthoritative;
    /** @var string[] */
    private $configuration;

    /**
     * BasePlugin constructor.
     * @param int $weight
     * @param bool $isAuthoritative
     * @param $configuration
     */
    public function __construct($weight, $isAuthoritative, $configuration)
    {
        $this->weight = $weight;
        $this->isAuthoritative = $isAuthoritative;
        $this->configuration = $configuration;
        $this->configure($configuration);
    }
}