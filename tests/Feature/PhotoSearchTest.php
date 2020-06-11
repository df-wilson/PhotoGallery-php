<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoSearchTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByKeywordPublicPhotos()
    {
        logger("testSearchByKeywordPublicPhotos - ENTER");

        $this->seed();

        // Test public users only get public photos
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '2',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '',
                'to_date'          => ''
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"]]
                ]
            );


        // Test user getting neither public nor own photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => '',
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => []
                ]
            );

        // Test user getting public photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '1',
                    'private_checkbox' => '',
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => '',
                    'public_checkbox'  => true,
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                                 ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                                 ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        logger("testSearchByKeywordPublicPhotos - LEAVE");
    }

    public function testSearchByKeywordOwnPhotos()
    {
        logger("testSearchByKeywordOwnPhotos - ENTER");

        $this->seed();


        // test user getting only own photos
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '1',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        // test user searching only own photos when none for keyword
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '2',
                    'text'             => '',
                    'public_checkbox'  => '',
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => []
                ]
            );

        logger("testSearchByKeywordOwnPhotos - LEAVE");
    }

    public function testSearchByTextPublicPhotos()
    {
        logger("testSearchByTextPublicPhotos - ENTER");

        $this->seed();

        // Test public users only get public photos
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '',
                'text'             => 'Vancouver',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '',
                'to_date'          => ''
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        // test user searching only own photos when none for text
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => 'picture',
                    'public_checkbox'  => true,
                    'private_checkbox' => false,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                                 ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"]]
                ]
            );


        // Test searching for public and private photos does not return other users private photos.

        logger("testSearchByTextPublicPhotos - LEAVE");
    }

    public function testSearchByTextOwnPhotos()
    {
        logger("testSearchByTextOwnPhotos - LEAVE");

        $this->seed();

        // test user searching only own photos when none for text
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => 'picture',
                    'public_checkbox'  => '',
                    'private_checkbox' => true,
                    'from_date'        => '',
                    'to_date'          => ''
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"]]
                ]
            );

        logger("testSearchByTextOwnPhotos - LEAVE");
    }

   public function testSearchByFromDatePublicPhotos()
   {
       logger("testSearchByFromDatePublicPhotos - ENTER");

       $this->seed();

       // Test public users only get public photos
       $response = $this->json('GET',
           '/api/photos/search',
           [
               'keyword_id'       => '',
               'text'             => '',
               'public_checkbox'  => '',
               'private_checkbox' => '',
               'from_date'        => '2018-05-16',
               'to_date'          => ''
           ]);

       $response->assertStatus(200)
           ->assertExactJson(
               [
                   'msg' => 'ok',
                   'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                                ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"],
                                ["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"]]
               ]
           );

       // Test public users only get public photos from from_date
       $response = $this->json('GET',
           '/api/photos/search',
           [
               'keyword_id'       => '',
               'text'             => '',
               'public_checkbox'  => '',
               'private_checkbox' => '',
               'from_date'        => '2018-05-17',
               'to_date'          => ''
           ]);

       $response->assertStatus(200)
           ->assertExactJson(
               [
                   'msg' => 'ok',
                   'photos' => [["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                                ["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"]]
               ]
           );

       // test user searching only own photos when none for text
       $user = User::find(1);
       $response = $this->actingAs($user)
           ->json('GET',
               '/api/photos/search',
               [
                   'keyword_id'       => '',
                   'text'             => '',
                   'public_checkbox'  => true,
                   'private_checkbox' => false,
                   'from_date'        => '2018-05-17',
                   'to_date'          => ''
               ]);

       $response->assertStatus(200)
           ->assertExactJson(
               [
                   'msg' => 'ok',
                   'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                       ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"]]
               ]
           );

       logger("testSearchByFromDatePublicPhotos - LEAVE");
   }

    public function testSearchByToDatePublicPhotos()
    {
        logger("testSearchByToDatePublicPhotos - ENTER");

        $this->seed();

        // Test public users only get public photos
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '',
                'to_date'          => '2018-05-17'
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        // Test public users only get public photos from from_date
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '',
                'to_date'          => '2018-05-15'
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => []
                ]
            );

        // test user searching only own photos when none for text
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => '',
                    'public_checkbox'  => true,
                    'private_checkbox' => false,
                    'from_date'        => '',
                    'to_date'          => '2020-06-01'
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                                 ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                                 ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        logger("testSearchByDatePublicPhotos - LEAVE");
    }
    public function testSearchByFromDateToDatePublicPhotos()
    {
        logger("testSearchByFromDateToDatePublicPhotos - LEAVE");

        $this->seed();

        // Test public users only get public photos between from and to dates
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '2018-05-15',
                'to_date'          => '2018-05-17'
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"]]
                ]
            );

        // Test public users only get public photos between from and to dates
        $response = $this->json('GET',
            '/api/photos/search',
            [
                'keyword_id'       => '',
                'text'             => '',
                'public_checkbox'  => '',
                'private_checkbox' => '',
                'from_date'        => '2018-05-15',
                'to_date'          => '2020-05-15'
            ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [
                        ["description" => "Vancouver near Stanley Park", "id" => "1","name" => "Vancouver","thumbnail_filepath" => "/storage/images/thumb_2012_04_14_070.jpg"],
                        ["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"]
                    ]
                ]
            );

        // test logged in user
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->json('GET',
                '/api/photos/search',
                [
                    'keyword_id'       => '',
                    'text'             => '',
                    'public_checkbox'  => true,
                    'private_checkbox' => false,
                    'from_date'        => '2018-05-17',
                    'to_date'          => '2020-06-01'
                ]);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg' => 'ok',
                    'photos' => [
                                   ["description" => "A picture of a flamingo.","id" => "4","name" => "Flamingo","thumbnail_filepath" => "/storage/images/thumb_2012_04_09_016.jpg"],
                                   ["description" => "Railroad tracks looking south towards Kerrisdale in Vancouver. The track have been removed since this picture was taken and replaced by a bike/pedestrian path.","id" => "2","name" => "Kerrisdale Tracks","thumbnail_filepath" => "/storage/images/thumb_RailroadToKerrisdale.jpg"],
                                ]
                ]
            );
        
        logger("testSearchByFromDateToDatePublicPhotos - LEAVE");
    }

    /*
           public function testSearchByKeywordAndText()
           {
               logger("testSearchByKeywordAndText - ENTER");

               logger("testSearchByKeywordAndText - LEAVE");
           }

           public function testSearchByKeywordAndDate()
           {

           }

           public function testSearchByTextAndDate()
           {

           }

           public function testSearchByKeywordTextAndDate()
           {

           }

           */
}
