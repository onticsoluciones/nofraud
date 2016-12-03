<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Interfaces\BasePlugin;
use Ontic\NoFraud\Model\Assessment;

class StolenCcCheckerPlugin extends BasePlugin
{
    /** @var string */
    private $blockedCardNumbers;

    /**
     * @return string
     */
    function getCode()
    {
        return 'stolen_cc_checker';
    }

    /**
     * @param string[] $configuration
     */
    function configure($configuration)
    {
        $this->blockedCardNumbers = explode("\n", file_get_contents($configuration['file']));
    }

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        return ['credit_card'];
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        if(!isset($data['credit_card']))
        {
            return null;
        }

        $creditCard = $data['credit_card'];
        if(in_array($creditCard, $this->blockedCardNumbers))
        {
            // Fraudulent CC number, reject the transaction ASAP
            return new Assessment(0, true);
        }

        return new Assessment(100, false);
    }

    /**
     * @param $data
     * @return mixed
     */
    function augment($data)
    {
        return $data;
    }
}