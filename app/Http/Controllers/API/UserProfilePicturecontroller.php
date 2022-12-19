<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfilePicturecontroller extends BaseController
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = auth()->user();


        try {
            $imageName = time().'.'.$request->image->extension();
            $filePath = 'images/' . $imageName;

            Storage::disk('s3')->put($filePath, file_get_contents($request->image));
            $path = Storage::disk('s3')->url($filePath);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), []);
        }

        $user->profile_pic = $path;
        $user->save();

        return $this->sendResponse(
            ['image' =>  $path],
            'Profile picture changed successfully'
        );
    }

    public function destroy()
    {
        $user = auth()->user();
        $user->profile_pic = null;
        $user->save();

        return $this->sendResponse(
            ['image' =>  ''],
            'Profile picture removed successfully'
        );
    }
}
