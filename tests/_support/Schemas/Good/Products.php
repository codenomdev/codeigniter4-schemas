<?php

namespace CIModuleTests\Support;

use Codenom\Schemas\Structures\Mergeable;
use Codenom\Schemas\Structures\Schema;
use Codenom\Schemas\Structures\Relation;
use Codenom\Schemas\Structures\Table;
use Codenom\Schemas\Structures\Field;
use Codenom\Schemas\Structures\Index;
use Codenom\Schemas\Structures\ForeignKey;

/* SCHEMA */

$schema = new Schema();

/* TABLES */
$schema->tables->products = new Table('products');
$schema->tables->workers  = new Table('workers');

/* RELATIONS */
// Products->Workers
$relation         = new Relation();
$relation->type   = 'belongsTo';
$relation->table  = 'workers';
$relation->pivots = [
	['products', 'worker_id', 'workers', 'id'],
];
$schema->tables->products->relations->workers = $relation;

// Workers->Products
$relation         = new Relation();
$relation->type   = 'hasMany';
$relation->table  = 'products';
$relation->pivots = [
	['workers', 'id', 'products', 'worker_id'],
];
$schema->tables->workers->relations->products = $relation;

/* CLEANUP */
unset($relation);
