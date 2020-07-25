<?php

namespace Codenom\Schemas\Reader;

use Codenom\Schemas\BaseHandler;

class BaseReader extends BaseHandler
{
	/**
	 * Whether the reader is in a state to be used
	 *
	 * @var bool
	 */
	protected $ready = false;

	/**
	 * The currently loaded schema.
	 * Could be static but since Reader is usually called by
	 * the service we'll try it like this.
	 *
	 * @var string
	 */
	protected $schema;

	/**
	 * Indicate whether the reader is in a state to be used
	 *
	 * @param array|string $tables
	 */
	public function ready(): bool
	{
		return $this->ready;
	}

	/**
	 * Check that reader is ready before using its functions
	 *
	 * @param array|string $tables
	 */
	protected function ensureReady(): bool
	{
		if ($this->ready) {
			return true;
		}

		if (!$this->config->silent) {
			throw SchemasException::forReaderNotReady();
		}

		$this->errors[] = lang('Schemas.notReady');
		return false;
	}

	/**
	 * Dummy implementation for classes that cannot lazy load
	 *
	 * @param array|string $tables
	 */
	public function fetch($tables)
	{
	}

	/**
	 * Dummy implementation for classes that only bulk load
	 *
	 * @param array|string $tables
	 */
	public function fetchAll()
	{
	}
}

/** End of src/Reader/BaseReader.php */
