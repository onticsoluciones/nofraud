<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Model\Assessment;

/**
 * A sample plugin that rejects all transactions originating
 * from a set of known bad IP addresses
 * @package Ontic\NoFraud\Plugins
 */
class SampleIpBlacklistPlugin implements IPlugin
{
    // Since this is just an sample plugin, the "bad" IPs
    // are just hardcoded into the file. In a real system we
    // would load the IPs from a database or other external
    // source
    private static $blacklist = [
        '192.168.2.86',
        '192.168.3.145',
        '192.168.4.45',
        '192.168.5.232',
    ];

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        // This plugin will use only the "client_ip_address" datum
        // of the transaction
        return ['client_ip_address'];
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        // If an IP address is not provided we can't make an
        // assessment
        if(!isset($data['client_ip_address']))
        {
            return null;
        }

        // Is the IP address a know bad address?
        // If so we can authoritatively determine that the
        // transaction is fraudulent
        $clientIpAddress = $data['client_ip_address'];
        if(in_array($clientIpAddress, static::$blacklist))
        {
            return new Assessment(0, true);
        }

        // No problems found, return a positive assessment
        // and let other plugins examine the transaction
        return new Assessment(100, false);
    }

    /**
     * @param $data
     * @return mixed
     */
    function augment($data)
    {
        // This plugin doesn't modify the source data,
        // so we just return it as it is
        return $data;
    }
}