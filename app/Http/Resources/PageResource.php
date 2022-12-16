<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'background_color' => $this->background_color,
            'text_color' => $this->text_color,
            'custom_url' => $this->custom_url,
            'profile_pic' => $this->user->profile_pic,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'theme' => $this->theme,
            'social_networks' => SocialNetworkResource::collection(
                $this->socialNetworks()->orderBy('order')->get()
            ),
            'sections' =>
                SectionResource::collection(
                    $this->sections()->orderBy('order')->get()
                ) ?: []
        ];
    }
}
