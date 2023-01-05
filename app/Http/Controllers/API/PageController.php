<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Rules\IsSlug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;

class PageController extends BaseController
{
    public function index(): ResourceCollection
    {
        $this->middleware('needsRole:admin');
        return PageResource::collection(Page::paginate(10));
    }

    public function store(Request $request): jsonResponse
    {
        $this->middleware('needsPermission:page.create');
        $input = $request->all();

        $validator = Validator::make($input, [
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

    public function show(Page $page): jsonResponse
    {
        return $this->sendResponse(new PageResource($page), 'Page retrieved successfully');
    }

    public function update(Request $request, $id): jsonResponse
    {
        $this->middleware('needsPermission:page.edit');

        $input = $request->all();

        $page = Page::findOrFail($id);

        $page->update($input);

        if (array_key_exists('social_networks', $input)) {
            foreach ($input['social_networks'] as $index => $network) {
                $page->socialNetworks()->findOrFail($network['id'])
                    ->update([
                        'type' => $network['network'],
                        'url' => $network['url'],
                        'order' => $index
                    ]);
            }
        }

        if (array_key_exists('sections', $input)) {
            foreach ($input['sections'] as $index => $section) {
                $page->sections()->findOrFail($section['id'])
                    ->update([
                        'type' => $section['type'],
                        'data' => $section['data'],
                        'order' => $index
                    ]);
            }
        }

        return $this->sendResponse(new PageResource($page), 'Page updated successfully');
    }

    public function destroy(Page $page): jsonResponse
    {
        $this->middleware('needsPermission:page.delete');

        $page->delete();
        return $this->sendResponse([], 'Product deleted successfully');
    }
}
