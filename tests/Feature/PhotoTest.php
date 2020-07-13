<?php

namespace Tests\Feature;

use App\Photo;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIsUserPhotoOwner()
    {
        $this->seed();

        $isOwner = Photo::isUserPhotoOwner(1,1);
        $this->assertTrue($isOwner);

        $isOwner = Photo::isUserPhotoOwner(1,3);
        $this->assertTrue(!$isOwner);
    }
}
