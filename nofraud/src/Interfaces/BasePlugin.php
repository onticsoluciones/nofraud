<?php

namespace Ontic\NoFraud\Interfaces;

abstract class BasePlugin implements IPlugin
{
    /** @var int */
    private $weight;
    /** @var boolean */
    private $authoritative;
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
        $this->authoritative = $isAuthoritative;
        $this->configuration = $configuration;
        $this->configure($configuration);
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return boolean
     */
    public function isAuthoritative()
    {
        return $this->authoritative;
    }

    /**
     * @return \string[]
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}