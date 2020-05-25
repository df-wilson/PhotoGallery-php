<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Photo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    
    public function testSearch()
    {
        // Search for private photos
        $photos = Photo::search(2, false, true, "", "", 3, "Hippo");
        $this->assertEquals(count($photos), 1);
        $this->assertEquals($photos[0]->id,"4");
        $this->assertEquals($photos[0]->name, "Giraffe");
        $this->assertEquals($photos[0]->thumbnail_filepath, "/storage/images/thumb_2012_04_09_012.jpg");

        $photos = Photo::search(1, false, true, "", "", 3, "Hippo");
        $this->assertEquals(count($photos), 1);
        $this->assertEquals($photos[0]->id,"1");
        $this->assertEquals($photos[0]->name, "Hippo");
        $this->assertEquals($photos[0]->thumbnail_filepath, "/storage/images/thumb_2012_04_09_014.jpg");
    }
}

