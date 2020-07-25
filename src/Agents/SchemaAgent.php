<?php

namespace Codenom\Schemas\Agents;

/* Codenom\Agents
 * Service analysis and assessment for CodeIgniter 4
 * https://github.com/Codenom/codeigniter4-agents
 *
 * Install:
 * `composer require Codenom\agents`
 * `php spark handlers:register`
 *
 * Collect:
 * `php spark agents:check`
 *
 * Monitor:
 * https://github.com/Codenom/headquarters
 */

use CodeIgniter\Config\Services;
use Codenom\Agents\BaseAgent;
use Codenom\Agents\Interfaces\AgentInterface;

class SchemaAgent extends BaseAgent implements AgentInterface
{
    // Attributes for Codenom\Handlers
    public $attributes = [
        'name'       => 'Schema',
        'uid'        => 'schema',
        'icon'       => 'fas fa-project-diagram',
        'summary'    => 'Map the database structure from the default connection',
    ];

    public function check($path = null)
    {
        $schemas = Services::schemas();
        if (empty($schemas)) {
            return false;
        }
        $config = config('Schemas');

        // Generate the schema
        $schema = $schemas->import(...$config->defaultHandlers)->get();


        $this->record('defaultSchema', 'object', $schema);
    }
}

/** End of src/Agents/SchemaAgent.php */
