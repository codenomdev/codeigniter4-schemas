<?php

namespace Codenom\Schemas\Config;

use CodeIgniter\Config\BaseService;
use Codenom\Schemas\Structures\Schema;

class Services extends BaseService
{
    public static function schemas(BaseConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('schemas', $config);
        }

        // If no config was injected then load one
        if (empty($config)) {
            $config = config('Schemas');
        }

        return new \Codenom\Schemas\Schemas($config);
    }
}

/** End of src/Config/Services.php */
