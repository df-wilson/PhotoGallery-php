<?php
namespace App\Http\Controllers;

use App\Keywords;
use App\Photo;

use Auth;

class PhotoController extends Controller
{
    public function __construct()
    {
    }

    public function show($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $photo = Photo::getForUser($userId, $id);

            if(count($photo)) {
                $keywords = Keywords::findKeywordsForPhoto($id);
                logger("Keywords are ", ["Keywords" => $keywords]);
                return view('photos.single', ['name' => $photo->name, 'description' => $photo->description, 'src' => $photo->filepath, 'id' => $photo->id, 'keywords' => $keywords]);

            } else {
                return "Photo not found.";
            }
        } else {
            return redirect('/login');
        }
    }
}

