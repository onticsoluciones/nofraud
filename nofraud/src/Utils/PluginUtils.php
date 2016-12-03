<?php

namespace Ontic\NoFraud\Utils;

use Ontic\NoFraud\Interfaces\IPlugin;
use Symfony\Component\Yaml\Yaml;

class PluginUtils
{
    /**
     * @return IPlugin[]
     */
    public static function loadPlugins()
    {
        $plugins = [];
        $configurationFile = PROJECT_ROOT . '/data/config.yml';
        $configuration = Yaml::parse(file_get_contents($configurationFile));

        foreach($configuration['plugins'] as $pluginConfig)
        {
            $code = $pluginConfig['code'];
            $weight = isset($pluginConfig['weight'])
                ? $pluginConfig['weight']
                : 1;
            $authoritative = isset($pluginConfig['authoritative'])
                ? $pluginConfig['authoritative']
                : false;
            $configuration = isset($pluginConfig['config'])
                ? $pluginConfig['config']
                : [];

            $pluginClass = 'Ontic\NoFraud\Plugins\\' . static::toPascalCase($code) . 'Plugin';
            $plugins[] = new $pluginClass($weight, $authoritative, $configuration);
        }

        return $plugins;
    }

    private static function toPascalCase($string)
    {
        return preg_replace_callback("/(?:^|_)([a-z])/", function($matches)
        {
            return strtoupper($matches[1]);
        }, $string);
    }
}