<?php

namespace App\Http\Controllers\API;

use App\Enums\SocialNetworkEnum;
use App\Http\Resources\SocialNetworkResource;
use App\Models\Page;
use App\Models\SocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class PageSocialNetworkController extends BaseController
{
    public function store(Request $request, Page $page)
    {
        $input = $request->all();

        $network = SocialNetworkEnum::from($input['type']);

        $validator  = Validator::make($input, [
            'type' => [new Enum((SocialNetworkEnum::class)), 'required'],
            'url' => 'required|url|starts_with:' . implode(',', $network->url()),
            'order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $socialNetwork = new SocialNetwork();
        $result = $socialNetwork->store($input, $page);

        return $this->sendResponse(new SocialNetworkResource($result), 'Social Network created successfully');
    }

    public function show(SocialNetwork $socialNetwork)
    {
        return $this->sendResponse(new SocialNetworkResource($socialNetwork), 'Social Network retrieved successfully');
    }

    public function destroy(Page $page, SocialNetwork $socialNetwork)
    {
        $socialNetwork->delete();

        return $this->sendResponse([], 'Social Network deleted successfully');
    }
}
