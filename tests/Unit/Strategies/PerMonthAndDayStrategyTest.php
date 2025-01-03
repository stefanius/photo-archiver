<?php

namespace Tests\Unit\Actions;

use Carbon\Carbon;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Strategies\PerMonthAndDayStrategy;

class PerMonthAndDayStrategyTest extends TestCase
{
    #[Test]
    public function it_can_generate_a_subfolder_name_per_month_and_day()
    {
        // Given
        $date = Carbon::createFromFormat('Ymd', '20201210');
        $strategy = new PerMonthAndDayStrategy;

        // When
        $result = $strategy->pathFromDate($date);

        // Then
        $this->assertIsString($result);
        $this->assertEquals('2020-12/2020-12-10', $result);
    }
}
