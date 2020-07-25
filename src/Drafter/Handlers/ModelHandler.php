<?php

namespace Codenom\Schemas\Drafter\Handlers;

use CodeIgniter\Config\BaseConfig;
use Config\Services;
use Codenom\Schemas\Drafter\BaseDrafter;
use Codenom\Schemas\Drafter\DrafterInterface;
use Codenom\Schemas\Structures\Schema;
use Codenom\Schemas\Structures\Table;
use Codenom\Schemas\Structures\Field;

class ModelHandler extends BaseDrafter implements DrafterInterface
{
    /**
     * The default database group.
     *
     * @var string
     */
    protected $defaultGroup;

    /**
     * The database group to constrain by.
     *
     * @var string
     */
    protected $group;

    /**
     * Save the config and set the initial database group
     *
     * @param BaseConfig  $config   The library config
     * @param string      $group    A database group to use as a filter; null = default group, false = no filtering
     */
    public function __construct(BaseConfig $config = null, $group = null)
    {
        parent::__construct($config);

        // Load the default database group		
        $config = config('Database');
        $this->defaultGroup = $config->defaultGroup;
        unset($config);

        // If nothing was specified then constrain to the default database group
        if (is_null($group)) {
            $this->group = $this->defaultGroup;
        } elseif (!empty($group)) {
            $this->group = $group;
        }
    }

    /**
     * Change the name of the database group constraint
     *
     * @param string  $group    A database group to use as a filter; false = no filtering
     */
    public function setGroup(string $group)
    {
        $this->group = $group;
        return $group;
    }

    /**
     * Get the name of the database group constraint
     *
     * @return string|null  The current group
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * Load models and build table data off their properties
     *
     * @return Schema|null
     */
    public function draft(): ?Schema
    {
        // Start with an empty schema
        $schema = new Schema();

        foreach ($this->getModels() as $class) {
            $instance = new $class();

            // Start a new table
            $table             = new Table($instance->table);
            $table->model      = $class;
            $table->returnType = $instance->returnType;

            // Create a field for the primary key
            $field = new Field($instance->primaryKey);
            $field->primary_key = true;
            $table->fields->{$field->name} = $field;

            // Create a field for each allowed field
            foreach ($instance->allowedFields as $fieldName) {
                $field = new Field($fieldName);
                $table->fields->$fieldName = $field;
            }

            // Figure out which timestamp fields (if any) this model uses and add them
            $timestamps = $instance->useTimestamps ? ['createdField', 'updatedField'] : [];
            if ($instance->useSoftDeletes) {
                $timestamps[] = 'deletedField';
            }

            // Get field names from each timestamp attribute
            foreach ($timestamps as $attribute) {
                $fieldName   = $instance->$attribute;
                $field       = new Field($fieldName);
                $field->type = $instance->dateFormat;

                $table->fields->$fieldName = $field;
            }

            $schema->tables->{$table->name} = $table;
        }

        return $schema;
    }

    /**
     * Load model class names from all namespaces, filtered by group
     *
     * @return array of model class names
     */
    protected function getModels(): array
    {
        $loader  = Services::autoloader();
        $locator = Services::locator();
        $models = [];

        // Get each namespace
        foreach ($loader->getNamespace() as $namespace => $path) {
            // Skip namespaces that are ignored
            if (in_array($namespace, $this->config->ignoredNamespaces)) {
                continue;
            }

            // Get files under this namespace's "/Models" path
            foreach ($locator->listNamespaceFiles($namespace, '/Models/') as $file) {
                if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                    // Load the file
                    require_once $file;
                }
            }
        }

        // Filter loaded class on likely models
        $classes = preg_grep('/model$/i', get_declared_classes());

        // Try to load each class
        foreach ($classes as $class) {
            // Try to instantiate
            try {
                $instance = new $class();
            } catch (\Exception $e) {
                continue;
            }

            // Make sure it's really a model
            if (!($instance instanceof \CodeIgniter\Model)) {
                continue;
            }

            // Make sure it has a valid table
            $table = $instance->table;
            if (empty($table)) {
                continue;
            }

            // Filter by group
            $group = $instance->DBGroup ?? $this->defaultGroup;
            if (empty($this->group) || $group == $this->group) {
                $models[] = $class;
            }
            unset($instance);
        }

        return $models;
    }
}

/** End of src/Drafter/Handlers/ModelHandler.php */
