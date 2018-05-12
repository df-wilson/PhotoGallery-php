<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Photo extends Model
{
    public static function getAllForUser($userId)
    {
        return DB::select('select photos.id, photos.name, photos.description, photos.thumbnail_filepath, photos.filepath from photos, users where user_id=? and users.id = photos.user_id order by photos.created_at',[$userId]);
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
        $result = DB::select('SELECT photos.id, photos.name, photos.description, photos.filepath
                              FROM photos, users, photo_keywords
                              WHERE users.id = photos.user_id AND photos.id = photo_keywords.photo_id AND users.id = ? AND photo_keywords.keyword_id = ?
                              ORDER BY photos.created_at',
                              [$userId, $keywordId]);

        logger("Temp", ["Result" => $result]);
        if(count($result)) {
            $photo = $result;
        }
        return $photo;
    }
}
