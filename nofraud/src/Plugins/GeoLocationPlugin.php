<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Interfaces\BasePlugin;
use Ontic\NoFraud\Model\Assessment;

class GeoLocationPlugin extends BasePlugin
{
    /** @var \PDO */
    private $connection;

    /**
     * @return string
     */
    function getCode()
    {
        return 'geo_location';
    }

    /**
     * @param string[] $configuration
     */
    function configure($configuration)
    {
        $databaseFile = $configuration['file'];
        $this->connection = new \PDO('sqlite:' . $databaseFile);
    }

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        return ['client_ip_address'];
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        return null;
    }

    /**
     * @param $data
     * @return mixed
     */
    function augment($data)
    {
        if(!isset($data['client_ip_address']))
        {
            return $data;
        }

        // Convert the IP address to an integer
        $ipAddress = ip2long($data['client_ip_address']);

        // Look up the country code in the database
        $sql = 'SELECT country_iso_code
          FROM blocks INNER JOIN countries ON blocks.geoname_id = countries.geoname_id
          WHERE :ip >= network_start_integer AND :ip <= network_last_integer;';
        $parameters = [ 'ip' => $ipAddress ];
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        if(($row = $statement->fetch()) !== false)
        {
            $data['client_country_code'] = $row['country_iso_code'];
        }

        return $data;
    }
}