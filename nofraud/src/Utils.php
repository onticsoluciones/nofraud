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
            if(in_array($entry, ['.', '..']))
            {
                continue;
            }

            $pluginPath = $pluginsDirectory . '/' . $entry;
            $className = pathinfo($pluginPath)['filename'];
            $fullyQualifiedClassName = 'Ontic\NoFraud\Plugins\\' . $className;
            if(class_exists($fullyQualifiedClassName))
            {
                $object = new $fullyQualifiedClassName();
                if ($object instanceof IPlugin)
                {
                    $plugins[] = $object;
                }
            }
        }

        return $plugins;
    }
}