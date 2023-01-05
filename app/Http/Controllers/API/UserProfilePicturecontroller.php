<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserProfilePicturecontroller extends BaseController
{
    public function store(Request $request): jsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = auth()->user();

        try {
            $file = request()->file('image');
            $imageName = time().'.'.$request->image->extension();
            $filePath = 'images/' . $imageName;

            $img = Image::make($file);

            $img->resize(null, 256, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();

            Storage::disk('s3')->put($filePath, $resource);
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

    public function destroy(): jsonResponse
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
