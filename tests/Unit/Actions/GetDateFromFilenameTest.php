<?php

namespace Tests\Unit\Actions;

use Carbon\Carbon;
use Tests\TestCase;
use App\Actions\GetDateFromFilename;
use PHPUnit\Framework\Attributes\Test;

class GetDateFromFilenameTest extends TestCase
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Setup test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->path = base_path('tests/archive');
    }

    #[Test]
    public function it_can_get_a_date_from_a_valid_filename()
    {
        // Given
        $filename = 'IMG_20190708_074526533.jpg';
        $action = new GetDateFromFilename;

        // When
        $result = $action->handle($filename);

        // Then
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals('20190708', $result->format('Ymd'));
        $this->assertEquals(2019, $result->year);
        $this->assertEquals(7, $result->month);
        $this->assertEquals(8, $result->day);
    }

    #[Test]
    public function it_can_get_a_date_from_a_valid_filename_with_dashes()
    {
        // Given
        $filename = 'IMG-20191111-074526533.jpg';
        $action = new GetDateFromFilename;

        // When
        $result = $action->handle($filename);

        // Then
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals('20191111', $result->format('Ymd'));
        $this->assertEquals(2019, $result->year);
        $this->assertEquals(11, $result->month);
        $this->assertEquals(11, $result->day);
    }

    #[Test]
    public function it_cannot_get_a_date_from_a_filename_without_an_img_prefix()
    {
        // Given
        $filename = '20190708_074526533.jpg';
        $action = new GetDateFromFilename;

        // When
        $result = $action->handle($filename);

        // Then
        $this->assertIsBool($result);
        $this->assertFalse($result);
    }

    #[Test]
    public function it_cannot_parse_a_date_string_less_then_8_characters()
    {
        // Given
        $filename = 'IMG_2019070_074526533.jpg';
        $action = new GetDateFromFilename;

        // When
        $result = $action->handle($filename);

        // Then
        $this->assertIsBool($result);
        $this->assertFalse($result);
    }

    #[Test]
    public function it_cannot_parse_a_date_string_more_then_8_characters()
    {
        // Given
        $filename = 'IMG_201907011_074526533.jpg';
        $action = new GetDateFromFilename;

        // When
        $result = $action->handle($filename);

        // Then
        $this->assertIsBool($result);
        $this->assertFalse($result);
    }
}
