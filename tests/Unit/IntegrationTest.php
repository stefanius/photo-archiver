<?php

namespace Tests\Unit;

use App\Exceptions\NonExistingPathException;
use App\Jobs\ArchiveJob;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * @var \App\Jobs\ArchiveJob
     */
    protected $archiver;

    /**
     * Setup test.
     */
    public function setUp() : void
    {
        parent::setUp();

        // Prepare test location
        File::copyDirectory(base_path('tests/files'), base_path('tests/archive'));

        // Instantiate archiver
        $this->archiver = new ArchiveJob(base_path('tests/archive'));
    }

    /** @test */
    public function it_can_split_a_list_of_files_into_separate_folders()
    {
        // Given
        $archiver = $this->archiver;

        // When
        $archiver->handle();

        // Then
        $expectedFilename = base_path('tests/archive/2018-09') . '/IMG_20180928_082102_1.JPG';

        $this->assertFileExists($expectedFilename);
    }

    /** @test */
    public function it_can_throw_an_error_when_the_image_folder_does_not_exists()
    {
        $this->expectException(NonExistingPathException::class);

        new ArchiveJob('/does/not/exists');
    }
}
