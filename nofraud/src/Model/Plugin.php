<?php

namespace Ontic\NoFraud\Model;

class Plugin
{
    /** @var string */
    private $code;
    /** @var int */
    private $priority;
    /** @var bool */
    private $authoritative;
    /** @var float */
    private $weight;
    /** @var string[] */
    private $configuration;

    /**
     * Plugin constructor.
     * @param string $code
     * @param int $priority
     * @param bool $authoritative
     * @param float $weight
     * @param [string]string $configuration
     */
    public function __construct(
        $code, $priority, $authoritative, $weight, $configuration)
    {
        $this->code = $code;
        $this->priority = $priority;
        $this->authoritative = $authoritative;
        $this->weight = $weight;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Plugin
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return Plugin
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAuthoritative()
    {
        return $this->authoritative;
    }

    /**
     * @param boolean $authoritative
     * @return Plugin
     */
    public function setAuthoritative($authoritative)
    {
        $this->authoritative = $authoritative;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return Plugin
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param \string[] $configuration
     * @return Plugin
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }
}