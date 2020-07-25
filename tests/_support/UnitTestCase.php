<?php

namespace Tests\Support;

use Codenom\Schemas\Structures\Schema;
use Codenom\Schemas\Structures\Relation;
use Codenom\Schemas\Structures\Table;
use Codenom\Schemas\Structures\Field;
use Codenom\Schemas\Structures\Index;
use Codenom\Schemas\Structures\ForeignKey;

class UnitTestCase extends \CodeIgniter\Test\CIUnitTestCase
{
	/**
	 * Preconfigured config instance.
	 */
	protected $config;

	/**
	 * Instance of the library.
	 */
	protected $schemas;

	public function setUp(): void
	{
		parent::setUp();

		$config                    = new \Codenom\Schemas\Config\Schemas();
		$config->silent            = false;
		$config->ignoredTables     = ['migrations'];
		$config->ignoredNamespaces = [];
		$config->schemasDirectory  = SUPPORTPATH . 'Schemas/Good';
		$config->automate = [
			'draft'   => false,
			'archive' => false,
			'read'    => false,
		];

		$this->config  = $config;
		$this->schemas = new \Codenom\Schemas\Schemas($config);

		// Create a mock schema so we don't have to call any handlers

		// Factories
		$table1               = new Table('factories');
		$table1->fields->id   = new Field('id');
		$table1->fields->name = new Field('name');
		$table1->fields->uid  = new Field('uid');

		$relation         = new Relation();
		$relation->type   = 'manyToMany';
		$relation->table  = 'workers';
		$relation->pivots = [
			['factories', 'id', 'factories_workers', 'factory_id'],
			['factories_workers', 'worker_id', 'workers', 'id'],
		];
		$table1->relations->workers = $relation;

		// Workers		
		$table2                    = new Table('workers');
		$table2->fields->id        = new Field('id');
		$table2->fields->firstname = new Field('firstname');
		$table2->fields->lastname  = new Field('lastname');
		$table2->fields->role      = new Field('role');

		$relation         = new Relation();
		$relation->type   = 'manyToMany';
		$relation->table  = 'factories';
		$relation->pivots = [
			['workers', 'id', 'factories_workers', 'worker_id'],
			['factories_workers', 'factory_id', 'factories', 'id'],
		];
		$table2->relations->factories = $relation;

		// Create the schema and add the tables
		$this->schema                    = new Schema();
		$this->schema->tables->factories = $table1;
		$this->schema->tables->workers   = $table2;
	}

	public function tearDown(): void
	{
		parent::tearDown();

		$this->schemas->reset();
		unset($this->schema);
		cache()->clean();
	}
}
