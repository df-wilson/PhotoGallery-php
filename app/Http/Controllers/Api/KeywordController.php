<?php

namespace App\Http\Controllers\Api;

use App\Keywords;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAll()
    {
        return Keywords::allKeywordNames();
    }

    public function addPhotoKeyword(Request $request, $photoId)
    {
        logger("KeywordController::addPhotoKeyword - ENTER - Photo Id $photoId");

        $code = 500;
        $message = "server error.";
        $userId = Auth::id();

        $photo = Photo::find($photoId);
        if($photo && $photo->user_id == $userId) {
            $keywordId = Keywords::findOrCreateId(mb_strtolower($request->keyword));
            logger("KeywordController::addPhotoKeyword - $keywordId.");

            $exists = Keywords::addKeywordToPhoto($keywordId, $photoId);

            if($exists) {
                $message = 'exists';
                $code = 200;
            } else {
                $message = 'ok';
                $code = 201;
            }
        } else {
            $code = 403;
            $message = "photo does not belong to user.";
        }

        return response($message, $code);
    }

    public function store(Request $request)
    {
        $this->saveKeyword(mb_strtolower($request->name));
    }

    private function saveKeyword(string $keyword)
    {
        $lowercase_name = mb_strtolower($keyword);

        if(!Keywords::exists($lowercase_name))
        {
            $keyword = new Keywords;
            $keyword->name = $lowercase_name;
            $keyword->save();
        }
    }
}
