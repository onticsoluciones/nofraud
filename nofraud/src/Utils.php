<?php

namespace Ontic\NoFraud;

use Ontic\NoFraud\Interfaces\IPlugin;

class Utils
{
    /**
     * @deprecated
     * @return IPlugin[]
     */
    public static function getAvailablePlugins()
    {
        $pluginsDirectory = __DIR__ . '/Plugins';

        $plugins = [];

        foreach(scandir($pluginsDirectory) as $entry)
        {
            $entryPath = $pluginsDirectory . '/' . $entry;
            if(is_dir($entryPath))
            {
                // Skip directory entries
                continue;
            }

            $className = pathinfo($entryPath)['filename'];
            $fullyQualifiedClassName = 'Ontic\NoFraud\Plugins\\' . $className;
            if(!class_exists($fullyQualifiedClassName))
            {
                // The class doesn't exist, bail out
                continue;
            }

            $object = new $fullyQualifiedClassName();
            if (!$object instanceof IPlugin)
            {
                // The class exists but it doesn't implement
                // IPlugin, so skip it
                continue;
            }

            $plugins[] = $object;
        }

        return $plugins;
    }

    /**
     * @param string $code
     * @return null|IPlugin
     */
    public static function getPluginByCode($code)
    {
        foreach(static::getAvailablePlugins() as $plugin)
        {
            if($plugin->getCode() === $code)
            {
                return $plugin;
            }
        }

        return null;
    }

    /**
     * @return \PDO
     */
    public static function openConnection()
    {
        $databasePath = __DIR__ . '/../data/database.sqlite';
        return new \PDO('sqlite:' . $databasePath);
    }
}