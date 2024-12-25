<?php

namespace Tests\Feature\Jobs;

use App\Exceptions\UnknownStrategyException;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class ArchiveCommandTest extends TestCase
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
    public function it_can_run_the_archive_command_with_the_default_strategy()
    {
        // When
        $this->artisan("archive {$this->path}")->assertExitCode(0);

        // Then
        $filesAfter = collect(File::files($this->path));
        $directoriesAfter = collect(File::directories($this->path));
        $expectedFilename = base_path('tests/archive/2018-09/2018-09-28').'/IMG_20180928_082102_1.JPG';

        $this->assertFileExists($expectedFilename);
        $this->assertEquals(0, $filesAfter->count());
        $this->assertEquals(4, $directoriesAfter->count());
    }

    #[Test]
    public function it_cannot_run_the_archive_command_when_the_path_is_ommitted()
    {
        $this->expectException(RuntimeException::class);

        // When
        $this->artisan('archive')->assertExitCode(1);
    }

    #[Test]
    public function it_cannot_run_the_archive_command_when_a_non_existing_strategy_is_used()
    {
        $this->expectException(UnknownStrategyException::class);

        // When
        $this->artisan("archive {$this->path} --strategy=foobar")->assertExitCode(1);
    }
}
