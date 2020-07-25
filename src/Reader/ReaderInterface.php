<?php

namespace Codenom\Schemas\Reader;

use Codenom\Schemas\Structures\Schema;

interface ReaderInterface extends \Countable, \IteratorAggregate
{
	/**
	 * Indicate whether the reader is in a state to be used
	 *
	 * @return bool
	 */
	public function ready(): bool;

	/**
	 * Fetch specified tables into the scaffold
	 *
	 * @param array|string $tables
	 */
	public function fetch($tables);

	/**
	 * Fetch all available tables into the scaffold
	 */
	public function fetchAll();
}

/** End of src/Reader/ReaderInterface.php */
