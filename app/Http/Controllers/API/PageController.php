<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(
            PageResource::collection(auth()->user()->pages),
            'Pages retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        Validator::extend('slug', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });

        $input = $request->all();

        $validator  = Validator::make($input, [
            'primary_color' => 'required',
            'secondary_color' => 'required',
            'custom_url' => 'required|slug',
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

    public function show(Page $page)
    {
        return $this->sendResponse(new PageResource($page), 'Page retrieved successfully');
    }

    public function update(Request $request, $id)
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

    public function destroy(Page $page)
    {
        $page->delete();

        return $this->sendResponse([], 'Product deleted successfully');
    }
}
