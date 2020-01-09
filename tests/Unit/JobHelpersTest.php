<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\TestData\TraitMockClass;

class JobHelpersTest extends TestCase
{
    /**
     * @var \Tests\TestData\TraitMockClass
     */
    protected $mock;

    /**
     * Setup test.
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->mock = new TraitMockClass();
    }

    /** @test */
    public function it_can_parse_a_valid_filename()
    {
        // Given
        $filename = 'IMG_20190708_074526533.jpg';

        // When
        $result = $this->mock->callGenerateSubFolderPath($filename);

        // Then
        $this->assertEquals('2019-07', $result);
    }

    /** @test */
    public function it_cannot_parse_a_filename_without_the_img_prefix()
    {
        // Given
        $filename = '20190708_074526533.jpg';

        // When
        $result = $this->mock->callGenerateSubFolderPath($filename);

        // Then
        $this->assertFalse($result);
    }

    /** @test */
    public function it_cannot_parse_a_date_string_less_then_8_characters()
    {
        // Given
        $filename = 'IMG_2019070_074526533.jpg';

        // When
        $result = $this->mock->callGenerateSubFolderPath($filename);

        // Then
        $this->assertFalse($result);
    }

    /** @test */
    public function it_cannot_parse_a_date_string_more_then_8_characters()
    {
        // Given
        $filename = 'IMG_201907011_074526533.jpg';

        // When
        $result = $this->mock->callGenerateSubFolderPath($filename);

        // Then
        $this->assertFalse($result);
    }
}
