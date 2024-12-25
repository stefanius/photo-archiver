<?php

namespace Tests\Unit\Actions;

use App\Strategies\PerMonthStrategy;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PerMonthStrategyTest extends TestCase
{
    #[Test]
    public function it_can_generate_a_subfolder_name_per_month()
    {
        // Given
        $date = Carbon::createFromFormat('Ymd', '20201010');
        $strategy = new PerMonthStrategy;

        // When
        $result = $strategy->pathFromDate($date);

        // Then
        $this->assertIsString($result);
        $this->assertEquals('2020-10', $result);
    }
}
