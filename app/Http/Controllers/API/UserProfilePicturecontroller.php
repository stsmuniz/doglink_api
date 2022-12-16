<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfilePicturecontroller extends BaseController
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = auth()->user();

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('profile_picture'), $imageName);

        $user->profile_pic = url('/profile_picture/'.$imageName);
        $user->save();

        return $this->sendResponse(
            ['image' =>  url('/profile_picture/'.$imageName)],
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
