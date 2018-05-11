<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        logger("PhotoController::index ENTER", ["User Id" => $userId]);
        $photos = Photo::getAllForUser($userId);
        logger("PhotoController::index LEAVE", ["Photos" => $photos]);

        return $photos;
    }
    
    public function show($photoId)
    {
        $userId = Auth::id();
        $photo = Photo::getforUser($userId, $photoId);

        return $photo;
    }

    public function updateDescription(Request $request, $photoId)
    {
        logger("PhotoController::updateDescription: Enter $photoId");

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

    public function updateTitle(Request $request, $id)
    {
        logger("PhotoController::updateTitle: Enter $id");

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
                logger("PhotoController::upload.", ["Path" => $path, "Name" => $name, "Extension" => $extension]);

                $photo = new Photo;
                $photo->user_id = $userId;
                $photo->name = $name;
                $photo->is_public = false;
                $photo->filepath = $path;
                $photo->description = "This is the description";
                $photo->save();

                $content = ["id" => 1, "fileName" => $name, "originalName" => $name];
                array_push($returnData, $content);
            }
        }

        return $returnData;
    }
}
