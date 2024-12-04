<?php

namespace Tests\TestData;

use App\Jobs\Traits\Helpers;

class TraitMockClass
{
    use Helpers;

    public function callGenerateSubFolderPath($filename)
    {
        return $this->generateSubFolderPath($filename);
    }
}
