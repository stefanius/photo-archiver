<?php

namespace Tests\Unit\Actions;

use App\Actions\GetDateFromExifData;
use App\Actions\GetDateFromLastModifiedDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetDateFromLastModifiedTest extends TestCase
{
    /**
     * @var string
     */
    protected $testFilesSource = 'tests/files';

    /**
     * @var string
     */
    protected $relativePath = 'tests/archive';

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

        $this->path = base_path($this->relativePath);

        // Prepare test location
        File::cleanDirectory($this->path);
        File::copyDirectory(base_path($this->testFilesSource), $this->path);
    }

    #[Test]
    public function it_can_get_the_last_modified_date_from_a_file()
    {
        // Given
        $today = Carbon::now();
        $filename = 'IMG_20181324_031442029.jpg';
        $action = new GetDateFromLastModifiedDate;

        // When
        $result = $action->handle("{$this->path}/{$filename}");

        // Then
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals($today->format('Ymd'), $result->format('Ymd'));
        $this->assertEquals($today->year, $result->year);
        $this->assertEquals($today->month, $result->month);
        $this->assertEquals($today->day, $result->day);
    }

    #[Test]
    public function it_cannot_get_a_date_from_a_file_that_not_exists()
    {
        // Given
        $filename = 'the-image-that-is-not-here.jpg';
        $action = new GetDateFromExifData;

        // When
        $result = $action->handle("{$this->path}/{$filename}");

        // Then
        $this->assertIsBool($result);
        $this->assertFalse($result);
    }
}
