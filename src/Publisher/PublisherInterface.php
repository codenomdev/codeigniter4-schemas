<?php

namespace Codenom\Schemas\Publisher;

use Codenom\Schemas\Structures\Schema;

interface PublisherInterface
{
	/**
	 * Commit the schema to its destination
	 *
	 * @param Schema $schema
	 *
	 * @return bool  Success or failure
	 */
	public function publish(Schema $schema): bool;
}

/** End of src/Publisher/PublisherInterface.php */
