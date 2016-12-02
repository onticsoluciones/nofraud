<?php

namespace Ontic\NoFraud\Model;

class Assessment
{
    /** @var int */
    private $score;
    /** @var boolean */
    private $authoritative;

    /**
     * @param int $score
     * @param bool $authoritative
     */
    public function __construct($score, $authoritative)
    {
        $this->score = $score;
        $this->authoritative = $authoritative;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return boolean
     */
    public function isAuthoritative()
    {
        return $this->authoritative;
    }
}