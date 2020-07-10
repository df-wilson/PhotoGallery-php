<?php

namespace App\Http\Controllers\Api;

use Auth;
use Image;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        logger("PhotoController::index ENTER", ["User Id" => $userId]);
        $photos = Photo::getAllForUser($userId);
        logger("PhotoController::index LEAVE", ["Photos" => $photos]);

        return $photos;
    }

    public function getAllPublic()
    {
        $photos = Photo::getAllPublic();

        return $photos;
    }

    public function show($photoId)
    {
        $userId = Auth::id();
        $photo = Photo::getforUser($userId, $photoId);

        return $photo;
    }
    
    public function search(Request $request)
    {
        logger("Api/PhotoController::search - ENTER", ["Data" => $request->all()]);

        $userId = Auth::id();

        if($userId == null) {
            $userId = 0;
        }

        $viewPublic = $request->public_checkbox;
        $viewPublic = $viewPublic ? true : false;

        $viewPrivate = $request->private_checkbox;
        $viewPrivate = $viewPrivate ? true : false;

        $keywordId = intval($request->keyword_id);
        logger("Keyword ID: $keywordId");
        
        $text = $request->text;
        if($text == null) {
            $text="";
        }

        $fromDate = $request->from_date ? $request->from_date : "";
        $toDate = $request->to_date ? $request->to_date : "";

        $photos = Photo::search($userId, $viewPublic, $viewPrivate, $fromDate, $toDate, $keywordId, $text);

        logger("Api/PhotoController::search - LEAVE", ["Photos" => $photos]);
        return response()->json(['msg' => 'ok','photos' => $photos]);
    }

    public function showForKeyword(Request $request, $keywordId)
    {
        $userId = Auth::id();
        $keywordId = intval($keywordId);

        if($userId) {
            if($keywordId == 0) {
                $photos = Photo::getAllForUser($userId);
            } else {
                $photos = Photo::getforUserAndKeyword($userId, $keywordId);
            }
        } else {
            $photos = $this->showPublicForKeyword($keywordId);
        }

        return $photos;
    }

    public function showPublicForKeyword($keywordId)
    {
        logger("PhotoController::showPublicForKeyword: ENTER $keywordId");

        $keywordId = intval($keywordId);

        if($keywordId == 0) {
            $photos = Photo::getAllPublic();
        } else {
            $photos = Photo::getPublicForKeyword($keywordId);
        }

        return $photos;
    }

    public function updateDescription(Request $request, $photoId)
    {
        logger("PhotoController::updateDescription: ENTER $photoId");

        $code = 500;
        $message = "Server Error";
        $userId = Auth::id();
        $photo = Photo::find($photoId);

        if($photo->user_id == $userId) {
            $photo->description = $request->input("description");
            $photo->save();
            $code = 200;
            $message = "updated";
        } else {
            $code = 403;
            $message = "photo does not belong to user.";
        }

        return response($message, $code);
    }

    public function updateIsPublic(Request $request, $photoId)
    {
        $code = 500;
        $message = "Server Error";
        $userId = Auth::id();
        $photo = Photo::find($photoId);
        
        if($photo && $photo->user_id == $userId) {
            $photo->is_public = $request->input("checked");
            $photo->save();
            $code = 200;
            $message = "updated";
        } else {
            $code = 403;
            $message = "photo does not belong to user.";
        }

        return response($message, $code);
    }

    public function updateTitle(Request $request, $id)
    {
        $code = 500;
        $message = "Server Error";
        $userId = Auth::id();
        $photo = Photo::find($id);

        if($photo->user_id == $userId) {
            $photo->name = $request->input("title");
            $photo->save();
            $code = 200;
            $message = "updated";
        } else {
            $code = 403;
            $message = "photo does not belong to user.";
        }

        return response($message, $code);
    }

    public function upload(Request $request)
    {
        logger("PhotoController::upload. " . $request);

        $userId = Auth::id();

        $returnData = [];
        $files = $request->file('photos');
        if ($request->hasFile('photos')) {
            foreach($files as $file) {
                $name = $file->getClientOriginalName();
                $extension = $file->extension();
                $path = $file->storeAs('public/images', $name);
                $path = "/storage/images/".$name;
                $thumbnailPath = "/storage/images/thumb_".$name;
                Image::make("./".$path)
                    ->orientate()
                    ->fit(200, 150)
                    ->save("./".$thumbnailPath);
                Image::make("./".$path)
                    ->orientate()
                    ->save("./".$path);

                $photo = new Photo;
                $photo->user_id = $userId;
                $photo->name = $name;
                $photo->is_public = false;
                $photo->filepath = $path;
                $photo->thumbnail_filepath = $thumbnailPath;
                $photo->description = "";
                $photo->save();

                $content = ["id" => 1, "fileName" => $name, "originalName" => $name];
                array_push($returnData, $content);
            }
        }

        return $returnData;
    }
}
