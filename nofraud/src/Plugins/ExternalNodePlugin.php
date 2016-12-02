<?php

namespace Ontic\NoFraud\Plugins;

use Ontic\NoFraud\Model\Assessment;

class ExternalNodePlugin extends BasePlugin
{
    private $url;
    private $username;
    private $password;

    /**
     * @return string
     */
    function getCode()
    {
        return 'external_nofraud_node';
    }

    function configure($configuration)
    {
        $this->url = $configuration['url'];
        $this->username = $configuration['username'];
        $this->password = $configuration['password'];
    }

    /**
     * @return string[]
     */
    function getProvidedFields()
    {
        $url = $this->url . '/capabilities';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($handle);
        if($output === false)
        {
            return [];
        }

        return json_decode($output, true);
    }

    /**
     * @param $data
     * @return Assessment|null
     */
    function assess($data)
    {
        $url = $this->url . '/capabilities';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($handle);
        if($output === false)
        {
            return null;
        }

        $score = json_decode($output, true)['assessment'];
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