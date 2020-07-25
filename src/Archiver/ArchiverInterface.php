<?php

namespace Codenom\Schemas\Archiver;

use Codenom\Schemas\Structures\Schema;

interface ArchiverInterface
{
    /**
     * Store a copy of the schema to its destination
     *
     * @param Schema $schema
     *
     * @return bool  Success or failure
     */
    public function archive(Schema $schema): bool;
}

/** End of src/Archiver/ArchiverInterface.php */
