<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Resources\PageResource;
use App\Http\Controllers\API\BaseController;

class PageController extends BaseController
{
    public function show(string $customUrl)
    {
        $page = Page::where('custom_url', $customUrl)->firstOrFail();

        return $this->sendResponse(new PageResource($page), 'Page retrieved successfully');
    }
}
