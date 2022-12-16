<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HeaderBlockRepository implements SectionInterface
{
    private mixed $formData;
    private string $text;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);
        $this->text = $this->formData->text;

        $validator = Validator::make(['text' => $this->text], [
            'text' => 'required|string|between:3,40',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function create(): string
    {
        return json_encode([
            'text' => $this->text,
        ]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
