<?php

namespace Ontic\NoFraud\Repositories;

use Ontic\NoFraud\Model\Plugin;

class PluginRepository extends BaseRepository
{
    /**
     * @return Plugin[]
     */
    public function findAll()
    {
        $sql = 'SELECT * FROM plugins ORDER BY priority DESC;';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute();

        while(($row = $statement->fetch()) !== false)
        {
            yield static::rowToPlugin($row);
        }
    }

    /**
     * @param Plugin $plugin
     * @return Plugin
     */
    public function insert(Plugin $plugin)
    {
        // Update the plugin so it has the lowest priority
        $sql = 'SELECT COALESCE(MIN(priority), 1) - 1 AS newPriority FROM plugins;';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute();
        $priority = $statement->fetch()['newPriority'];
        $plugin->setPriority($priority);

        $sql = '
          INSERT INTO plugins(code, priority, authoritative, weight, configuration)
          VALUES(:code, :priority, :authoritative, :weight, :configuration);';

        $parameters = [
            'code' => $plugin->getCode(),
            'priority' => $plugin->getPriority(),
            'authoritative' => $plugin->isAuthoritative() ? 1 : 0,
            'weight' => $plugin->getWeight(),
            'configuration' => json_encode($plugin->getConfiguration())
        ];

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($parameters);

        return $plugin;
    }

    /**
     * @param $row
     * @return Plugin
     */
    private static function rowToPlugin($row)
    {
        $code = $row['code'];
        $priority = $row['priority'];
        $authoritative = $row['authoritative'];
        $weight = $row['weight'];
        $configuration = json_decode($row['configuration'], true);

        return new Plugin($code, $priority, $authoritative, $weight, $configuration);
    }
}