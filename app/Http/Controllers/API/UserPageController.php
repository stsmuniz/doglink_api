<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Rules\IsSlug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPageController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $pages = $user->pages()->paginate(10);
        return PageResource::collection($pages);
    }

    public function get()
    {
        $user = auth()->user();
        $page = $user->pages()->first();
        return $this->sendResponse(new PageResource($page), 'Page retrieved successfully');
    }

    public function show($id): jsonResponse
    {
        $user = auth()->user();
        $page = $user->pages()->findOrFail($id);
        return $this->sendResponse(new PageResource($page), 'Page retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator  = Validator::make($input, [
            'primary_color' => 'required',
            'secondary_color' => 'required',
            'custom_url' => ['required', new IsSlug],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        if (!array_key_exists('theme', $input)) {
            $input['theme'] = 'default';
        }

        $user = auth()->user();
        $page = new Page($input);
        $user->pages()->save($page);

        return $this->sendResponse(new PageResource($page), 'Page created successfully');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
        $page = $user->pages()->findOrFail($id);

        $page->update($input);

        foreach ($input['social_networks'] as $index => $network) {
            $page->socialNetworks()->findOrFail($network['id'])
                ->update([
                    'type' => $network['network'],
                    'url' => $network['url'],
                    'order' => $index
                ]);
        }

        foreach ($input['sections'] as $index => $section) {
            $page->sections()->findOrFail($section['id'])
                ->update([
                    'type' => $section['type'],
                    'data' => $section['data'],
                    'order' => $index
                ]);
        }

        return $this->sendResponse(new PageResource($page), 'Page updated successfully');
    }

    public function destroy($id): jsonResponse
    {
        $user = auth()->user();
        $page = $user->pages()->findOrFail($id);
        $page->delete();
        return $this->sendResponse([], 'Page deleted successfully');
    }
}
