<?php

namespace App\Strategies;

use Carbon\Carbon;

class PerMonthStrategy implements Strategy
{
    /**
     * Generate path from date.
     */
    public function pathFromDate(Carbon $date): string
    {
        return sprintf("%d-%'.02d", $date->year, $date->month);
    }
}
