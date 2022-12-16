<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SpotifyPlayerRepository implements SectionInterface
{
    private mixed $formData;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);
        $this->uri = new Uri($this->formData->url);

        $validator = Validator::make(['network' => $this->network, 'uri' => $this->uri], [
            'uri' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function create(): string
    {
        return json_encode([
            'url' => $this->uri
        ]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
