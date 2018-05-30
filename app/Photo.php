<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Photo extends Model
{
    public static function getAllForUser($userId)
    {
        logger("Enter Photo::getAllForUser");
        return DB::select('select photos.id, photos.name, photos.description, photos.thumbnail_filepath, photos.filepath from photos, users where user_id=? and users.id = photos.user_id order by photos.created_at',[$userId]);
    }

    public static function getAllPublic()
    {
        logger("Enter Photo::getAllPublic");
        return DB::select('select photos.id, photos.name, photos.description, photos.thumbnail_filepath, photos.filepath from photos where is_public=1 order by photos.created_at');
    }

    public static function getForUser($userId, $photoId)
    {
        $photo = [];
        $result = DB::select('select photos.id, photos.name, photos.description, photos.filepath from photos, users where photos.id=? and (photos.user_id =? or photos.is_public = 1) and users.id = photos.user_id order by photos.created_at',[$photoId, $userId]);

        if(count($result)) {
            $photo = $result[0];
        }
        return $photo;
    }

    public static function getForUserAndKeyword(int $userId, int $keywordId)
    {
        $photo = [];
        $result = DB::select('SELECT photos.id, photos.name, photos.description, photos.thumbnail_filepath
                              FROM photos, users, photo_keywords
                              WHERE users.id = photos.user_id AND photos.id = photo_keywords.photo_id AND users.id = ? AND photo_keywords.keyword_id = ?
                              ORDER BY photos.created_at',
                              [$userId, $keywordId]);

        if(count($result)) {
            $photo = $result;
        }
        return $photo;
    }

    public static function getPublic($photoId)
    {
        $photo = null;

        $photoId = intval($photoId);

        $result = DB::select('SELECT photos.id, photos.name, photos.description, photos.filepath
                              FROM photos
                              WHERE photos.id = ? AND is_public=1',
                              [$photoId]);
        if(count($result)) {
            $photo = $result[0];
        }

        return $photo;
    }

    public static function search(bool $publicPhotos, bool $ownPhotos, string $startDate, string $endDate, int $keywordId, string $text)
    {
        $photos = [];
        $result = DB::select('SELECT photos.id, photos.name, photos.description, photos.thumbnail_filepath
                              FROM photos, photo_keywords
                              WHERE (photos.id = photo_keywords.photo_id AND photos.is_public=1 AND photo_keywords.keyword_id = ?) OR 
                                    (photos.name = ? AND photos.is_public = 1) OR 
                                    (photos.description = ? AND photos.is_public = 1)
                              ORDER BY photos.created_at',
            [$keywordId, $text, $text]);

        if(count($result)) {
            $photos = $result;
        }

        logger("Photo::search LEAVE", ["photos" => $photos]);
        return $photos;
    }

    public static function getPublicForKeyword(int $keywordId)
    {
        $photo = [];
        $result = DB::select('SELECT photos.id, photos.name, photos.description, photos.thumbnail_filepath
                              FROM photos, photo_keywords
                              WHERE photos.id = photo_keywords.photo_id AND photos.is_public=1 AND photo_keywords.keyword_id = ?
                              ORDER BY photos.created_at',
            [$keywordId]);

        if(count($result)) {
            $photo = $result;
        }
        return $photo;
    }
}
