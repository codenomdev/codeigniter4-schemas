<?php

namespace Codenom\Schemas\Archiver\Handlers;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\BaseConfig;
use Codenom\Schemas\Exceptions\SchemasException;
use Codenom\Schemas\Archiver\BaseArchiver;
use Codenom\Schemas\Archiver\ArchiverInterface;
use Codenom\Schemas\Structures\Schema;
use Codenom\Schemas\Structures\Mergeable;
use Codenom\Schemas\Traits\CacheHandlerTrait;

class CacheHandler extends BaseArchiver implements ArchiverInterface
{
    use CacheHandlerTrait;

    /**
     * Save the config and set up the cache
     *
     * @param BaseConfig      $config   The library config
     * @param CacheInterface  $cache    The cache handler to use, null to load a new default
     */
    public function __construct(BaseConfig $config = null, CacheInterface $cache = null)
    {
        parent::__construct($config);

        $this->cacheInit($cache);
    }

    /**
     * Store the scaffold and each individual table to cache
     *
     * @param Schema $schema
     *
     * @return bool  Success or failure
     */
    public function archive(Schema $schema): bool
    {
        // Grab the tables to store separately
        $tables = $schema->tables;
        $schema->tables = new Mergeable();

        // Save each individual table
        foreach ($tables as $table) {
            $schema->tables->{$table->name} = true;
            $this->cache->save($this->cacheKey . '-' . $table->name, $table, $this->config->ttl);
        }

        // Save the scaffold version of the schema
        return $this->cache->save($this->cacheKey, $schema, $this->config->ttl);
    }
}

/** End of src/Archiver/Handlers/CacheHandler.php */
