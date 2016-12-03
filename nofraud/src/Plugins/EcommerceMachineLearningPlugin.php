<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Interfaces\BasePlugin;
use Ontic\NoFraud\Model\Assessment;

class EcommerceMachineLearningPlugin extends BasePlugin
{
    /** @var string */
    private $executable;

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
        if(isset($configuration['executable']))
        {
            $this->executable = $configuration['executable'];
        }
    }

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        return [
            'order_amount',
            'country',
            'country_iso_code'
        ];
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        $args = [[
            'amount' => $data['order_amount'],
            'ship_to_same_country' => ($data['country'] == $data['country_iso_code'])
                ? 1
                : 0
        ]];

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
        ];
        $cmd = sprintf('%s --evaluate', $this->executable);
        $process = proc_open($cmd, $descriptors, $pipes);
        fwrite($pipes[0], json_encode($args));
        fclose($pipes[0]);
        $score = (float) trim(stream_get_contents($pipes[1]));
        proc_close($process);
        return new Assessment($score, false);
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