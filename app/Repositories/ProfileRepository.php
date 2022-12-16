<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;
use GuzzleHttp\Psr7\Uri;

class ProfileRepository implements SectionInterface
{
    private mixed $formData;
    private string $name;
    private string $tagline;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);
        $this->name = $this->formData->name;
        $this->tagline = $this->formData->tagline;
    }

    public function create(): string
    {
        return json_encode([
            'name' => $this->name,
            'tagline' => $this->tagline
        ]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
