<?php

namespace Ontic\NoFraud;

use Ontic\NoFraud\Plugins\IPlugin;

class Utils
{
    /**
     * @return IPlugin[]
     */
    public static function getInstalledPlugins()
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
     * @return \PDO
     */
    public static function openConnection()
    {
        $databasePath = __DIR__ . '/../data/database.sqlite';
        return new \PDO('sqlite:' . $databasePath);
    }
}