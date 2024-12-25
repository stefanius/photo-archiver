<?php

namespace App\Strategies;

use Carbon\Carbon;

class PerDayStrategy implements Strategy
{
    /**
     * Generate path from date.
     */
    public function pathFromDate(Carbon $date): string
    {
        return sprintf("%d-%'.02d-%'.02d", $date->year, $date->month, $date->day);
    }
}
