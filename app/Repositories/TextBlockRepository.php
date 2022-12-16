<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;

class TextBlockRepository implements SectionInterface
{
    private mixed $formData;
    private string $title;
    private string $text;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);
        $this->title = $this->formData->name;
        $this->text = $this->formData->name;
    }

    public function create(): string
    {
        return json_encode([
            'title' => $this->title,
            'text' => $this->text,
        ]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
