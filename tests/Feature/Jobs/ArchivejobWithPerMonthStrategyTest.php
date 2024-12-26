<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\ArchiveJob;
use App\Strategies\PerMonthStrategy;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class ArchivejobWithPerMonthStrategyTest extends TestCase
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
    public function it_can_execute_the_archive_job_and_complete_the_archive_process()
    {
        // Check the initial state
        $filesBefore = collect(File::files($this->path));
        $directoriesBefore = collect(File::directories($this->path));

        $this->assertEquals(11, $filesBefore->count()); // Check the number of files used for this test
        $this->assertEquals(0, $directoriesBefore->count()); // Check the number of files used for this test

        // Execute job
        $archiver = new ArchiveJob($this->path, new PerMonthStrategy);
        $archiver->handle();

        // Check the files after running the job. All files must be moved.
        $filesAfter = collect(File::files($this->path));
        $directoriesAfter = collect(File::directories($this->path));
        $expectedFilename = base_path('tests/archive/2018-09') . '/IMG_20180928_082102_1.JPG';

        $this->assertFileExists($expectedFilename);
        $this->assertEquals(0, $filesAfter->count());
        $this->assertEquals(4, $directoriesAfter->count());
    }
}
