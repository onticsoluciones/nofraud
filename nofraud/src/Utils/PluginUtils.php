<?php

namespace Ontic\NoFraud\Utils;

use Symfony\Component\Yaml\Yaml;

class PluginUtils
{
    public static function loadPlugins()
    {
        $configurationFile = PROJECT_ROOT . '/data/config.yml';
        $configuration = Yaml::parse(file_get_contents($configurationFile));

        foreach($configuration['plugins'] as $pluginConfig)
        {
            $code = $pluginConfig['code'];
            $weight = $pluginConfig['weight'] ?: 1;
            $authoritative = $pluginConfig['authoritative'] ?: false;
            $configuration = $pluginConfig['config'];

            $pluginClass = 'Ontic\NoFraud\Plugins\\' . static::toPascalCase($code) . 'Plugin';
            yield new $pluginClass($weight, $authoritative, $configuration);
        }
    }

    private static function toPascalCase($string)
    {
        return preg_replace_callback("/(?:^|_)([a-z])/", function($matches)
        {
            return strtoupper($matches[1]);
        }, $string);
    }
}