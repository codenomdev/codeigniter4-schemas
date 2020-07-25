<?php

namespace Codenom\Schemas\Archiver\Handlers;

use Codenom\Schemas\Archiver\BaseArchiver;
use Codenom\Schemas\Archiver\ArchiverInterface;
use Codenom\Schemas\Structures\Schema;

class CliHandler extends BaseArchiver implements ArchiverInterface
{
    /**
     * Write out the schema to standard output via Kint
     *
     * @param Schema $schema
     *
     * @return bool  true
     */
    public function archive(Schema $schema): bool
    {
        +d($schema); // plus disables Kint's depth limit

        return true;
    }
}
/** End of src/Archiver/Handlers/CliHandler.php */
