<?php

namespace App\Http\Controllers\API;

use App\Enums\PageSectionEnum;
use App\Http\Resources\PageResource;
use App\Http\Resources\SectionResource;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class PageSectionController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(
            SectionResource::collection($this->page->sections()->orderBy('order')->get()),
            'Sections retrieved successfully'
        );
    }

    public function store(Request $request, Page $page)
    {
        $input = $request->all();

        $validator  = Validator::make($input, [
            'type' => [new Enum((PageSectionEnum::class)), 'required'],
            'data' => 'required|json',
            'order' => 'required|integer',
        ]);

        try {
            $className = 'App\Repositories\\'.$input['type'].'Repository';
            $ref = new \ReflectionClass($className);
            $obj = $ref->newInstanceArgs([$input['data']]);
        } catch (\TypeError $e) {
            return $this->sendError($e->getMessage(), []);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }

        $section = new Section();
        $section->store($input, $page);

        return $this->sendResponse(new PageResource($page), 'Section created successfully');
    }

    public function show(Section $section)
    {
        return $this->sendResponse(new SectionResource($section), 'Section retrieved successfully');
    }

    public function destroy(Page $page, Section $section)
    {
        $section->delete();

        return $this->sendResponse([], 'Section deleted successfully');
    }
}
