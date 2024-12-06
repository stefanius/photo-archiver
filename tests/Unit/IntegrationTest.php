<?php

namespace Tests\Unit;

use App\Exceptions\NonExistingPathException;
use App\Jobs\ArchiveJob;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * @var \App\Jobs\ArchiveJob
     */
    protected $archiver;

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
        File::copyDirectory(base_path($this->testFilesSource), $this->path);

        // Instantiate archiver
        $this->archiver = new ArchiveJob($this->path);
    }

    #[Test]
    public function it_can_execute_the_archive_job_and_complete_the_archive_process()
    {
        // Check the initial state
        $filesBefore = collect(File::files($this->path));
        $directoriesBefore = collect(File::directories($this->path));

        $this->assertEquals(11, $filesBefore->count()); // Check the number of files used for this test

        // Given
        $archiver = $this->archiver;

        // When
        $archiver->handle();

        // Then
        $expectedFilename = base_path('tests/archive/2018-09').'/IMG_20180928_082102_1.JPG';

        // Check the files after running the job
        $filesAfter = collect(File::files($this->path));
        $this->assertFileExists($expectedFilename);
        $this->assertEquals(3, $filesAfter->count()); // Check the number of files used for this test
    }

    // #[Test]
    // public function it_can_throw_an_error_when_the_image_folder_does_not_exists()
    // {
    //     $this->expectException(NonExistingPathException::class);

    //     new ArchiveJob('/does/not/exists');
    // }
}
