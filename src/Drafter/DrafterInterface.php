<?php

namespace Codenom\Schemas\Drafter;

use Codenom\Schemas\Structures\Schema;

interface DrafterInterface
{
    /**
     * Run the handler and return the resulting schema, or null on failure
     *
     * @return Schema|null
     */
    public function draft(): ?Schema;
}

/** End of src/Drafter/DrafterInterface.php */
