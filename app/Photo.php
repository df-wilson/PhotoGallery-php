<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Photo extends Model
{
    public static function getAllForUser($userId)
    {
        return DB::select('select photos.id, photos.name, photos.description, photos.filepath from photos, users where user_id=? and users.id = photos.user_id order by photos.created_at',[$userId]);
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
}
