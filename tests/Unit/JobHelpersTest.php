<?php

namespace Tests\Unit;

use App\Exceptions\NonExistingPathException;
use App\Jobs\ArchiveJob;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobHelpersTest extends TestCase
{
    /**
     * @var \App\Jobs\ArchiveJob
     */
    protected $mock;

    /**
     * Setup test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new ArchiveJob(base_path('tests/archive'));
    }

    #[Test]
    public function it_can_parse_a_valid_filename()
    {
        // Given
        $filename = 'IMG_20190708_074526533.jpg';

        // When
        $result = $this->mock->generateSubFolderPath($filename);

        // Then
        $this->assertEquals('2019-07', $result);
    }

    #[Test]
    public function it_cannot_parse_a_filename_without_the_img_prefix()
    {
        // Given
        $filename = '20190708_074526533.jpg';

        // When
        $result = $this->mock->generateSubFolderPathFromFilename($filename);

        // Then
        $this->assertFalse($result);
    }

    #[Test]
    public function it_cannot_parse_a_date_string_less_then_8_characters()
    {
        // Given
        $filename = 'IMG_2019070_074526533.jpg';

        // When
        $result = $this->mock->generateSubFolderPathFromFilename($filename);

        // Then
        $this->assertFalse($result);
    }

    #[Test]
    public function it_cannot_parse_a_date_string_more_then_8_characters()
    {
        // Given
        $filename = 'IMG_201907011_074526533.jpg';

        // When
        $result = $this->mock->generateSubFolderPathFromFilename($filename);

        // Then
        $this->assertFalse($result);
    }

    #[Test]
    public function it_can_throw_an_error_when_the_image_folder_does_not_exists()
    {
        $this->expectException(NonExistingPathException::class);

        new ArchiveJob('/does/not/exists');
    }
}
