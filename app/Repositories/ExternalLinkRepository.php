<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExternalLinkRepository implements SectionInterface
{
    private mixed $formData;
    private string $title;
    private Uri $uri;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);

        $this->title = $this->formData->text;
        $this->uri = new Uri($this->formData->url);

        $validator = Validator::make(['title' => $this->formData->text, 'uri' => $this->formData->url], [
           'title' => 'required|string|between:3,40',
           'uri' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function create(): string
    {
        return json_encode(['text' => $this->title, 'url' => $this->uri]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
