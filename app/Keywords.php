<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Keywords extends Model
{
    public static function findOrCreateId(string $keyword)
    {
        logger("Keywords::findOrCreateId", ["Keyword" => $keyword]);

        $id = 0;

        $result = DB::select('select id from keywords where name = ?', [$keyword]);

        logger("Keywords::findOrCreateId", ["Result" => $result]);

        if(count($result)) {
            $id = $result[0]->id;
            logger("Keywords::findOrCreateId", ["Keyword ID is" => $id]);
        }

        if($id == 0) {
            logger("Adding new keyword to database");
            $keywordModel = new Keywords;
            $keywordModel->name = $keyword;
            $keywordModel->save();
            $id = $keywordModel->id;
        }

        logger("Keywords::findOrCreateId:", ["Returning ID" => $id]);
        return $id;
    }


    public static function findKeywordsForPhoto($photoId)
    {
        $keywords = DB::select('select keywords.name from keywords, photo_keywords where keywords.id = photo_keywords.keyword_id and photo_keywords.photo_id = ?', [$photoId]);
        return $keywords;
    }

    public static function addKeywordToPhoto(int $keywordId, int $photoId)
    {
        $exists = false;
        $currentDate = \Carbon\Carbon::now();

        try {
            DB::insert("INSERT INTO photo_keywords VALUES (?,?,?,?)", [$photoId, $keywordId, $currentDate, $currentDate]);
            $exists = false;
        }
        catch(\Exception $e) {
            logger("Keywords::addKeywordToPhoto", ["Exception" => $e->getMessage()]);
            $exists = true;
        }

        logger('Keywords::findKeywordsForPhoto - LEAVE', ["Exists" => $exists]);
        return $exists;
    }
}
