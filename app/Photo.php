<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;

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

    public static function search(int $userId, bool $publicPhotos, bool $ownPhotos, string $startDate, string $endDate, int $keywordId, string $text)
    {
        logger("Photos::search - ENTER", ["userId" => $userId, "Public Photos" => $publicPhotos, "Private Photos" => $ownPhotos, "Start Date" => $startDate, "End Date" => $endDate, "Keyword Id" => $keywordId, "Text" => $text]);

        $photos = [];
        $inputs = [];
        $whereClause = "";

        if($keywordId) {
            logger("Photos::search - Searching for keywords.");
            $whereClause = "photo_keywords.keyword_id = :keyword_id";
            $inputs += ['keyword_id' => $keywordId];
        }

        if($text && strlen($text) > 1) {
            logger("Photos::search - Searching for text.");
            if($whereClause) {
                $whereClause .= " OR ";
            }
            $whereClause .= "(description LIKE :text OR name = :text)";
            $inputs += ['text' => "%$text%"];
        }

        if($publicPhotos == true && $ownPhotos == true) {
            // Get public and private photos
        } else if ($publicPhotos == true || $userId == 0) {
            // Public photos only
            logger("Photos::search - Public photos only.");
            if($whereClause) {
                $whereClause .= " AND ";
            }
            $whereClause .= "is_public = :is_public";
            $inputs += ['is_public' => true];
        }
        else if ($ownPhotos === true) {
            logger("Photos::search - Own photos only.");
            if($whereClause) {
                $whereClause .= " AND ";
            }
            $whereClause .= "user_id = :user_id";
            $inputs += ['user_id' => $userId];
        } else {
            Log::error("Photos::search - Error determining public or own photos.");
            return [];
        }

        $sql = "SELECT DISTINCT photos.id, photos.name, photos.description, photos.thumbnail_filepath
                FROM photos
                JOIN photo_keywords ON photos.id = photo_keywords.photo_id
                WHERE $whereClause";

        logger("Photo::search - ", ["sql" => $sql, "inputs" => $inputs]);

        $photos = DB::select($sql, $inputs);

        logger("Photo::search - LEAVE", ["photos" => $photos]);

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
