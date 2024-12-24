<?php

namespace App\Strategies;

use Carbon\Carbon;

interface Strategy
{
    /**
     * Generate path from date.
     */
    public function pathFromDate(Carbon $date): string;
}
