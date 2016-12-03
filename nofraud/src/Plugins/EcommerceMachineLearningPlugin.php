<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Interfaces\BasePlugin;
use Ontic\NoFraud\Model\Assessment;

class EcommerceMachineLearningPlugin extends BasePlugin
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'ecommerce_machine_learning';
    }

    /**
     * @param string[] $configuration
     */
    function configure($configuration)
    {
        // TODO: Implement configure() method.
    }

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        return [
            'order_amount',
            'shipping_country'
        ];
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        // TODO: Implement assess() method.
    }

    /**
     * @param $data
     * @return mixed
     */
    function augment($data)
    {
        // TODO: Implement augment() method.
    }
}