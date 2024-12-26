<?php

namespace Tests\Unit\Actions;

use Carbon\Carbon;
use Tests\TestCase;
use App\Strategies\PerDayStrategy;
use PHPUnit\Framework\Attributes\Test;

class PerDayStrategyTest extends TestCase
{
    #[Test]
    public function it_can_generate_a_subfolder_name_per_day()
    {
        // Given
        $date = Carbon::createFromFormat('Ymd', '20201010');
        $strategy = new PerDayStrategy;

        // When
        $result = $strategy->pathFromDate($date);

        // Then
        $this->assertIsString($result);
        $this->assertEquals('2020-10-10', $result);
    }
}
