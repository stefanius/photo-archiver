<?php

namespace App\Strategies;

use Carbon\Carbon;

class PerMonthAndDayStrategy implements Strategy
{
    /**
     * Generate path from date.
     */
    public function pathFromDate(Carbon $date): string
    {
        $month = sprintf("%d-%'.02d", $date->year, $date->month);
        $day = sprintf("%d-%'.02d-%'.02d", $date->year, $date->month, $date->day);

        return "{$month}/{$day}";
    }
}
