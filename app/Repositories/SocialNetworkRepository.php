<?php

namespace App\Repositories;

use App\Enums\SocialNetworkEnum;
use App\Interfaces\SectionInterface;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SocialNetworkRepository implements SectionInterface
{
    private mixed $formData;
    private SocialNetworkEnum $network;
    private Uri $uri;

    public function __construct(string $data)
    {
        $this->formData = json_decode($data);
        $this->network = SocialNetworkEnum::from($this->formData->network);
        $this->uri = new Uri($this->formData->url);

        $validator = Validator::make(['network' => $this->network, 'uri' => $this->uri], [
            'network' => 'required|string|between:3,40',
            'uri' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function create(): string
    {
        return json_encode(['network' => $this->network, 'url' => $this->uri]);
    }

    public function retrieve()
    {
        return $this->formData;
    }
}
