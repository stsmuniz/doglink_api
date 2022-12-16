<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type', 'url', 'order'];

    public function store(array $input, Page $page)
    {
        $socialNetwork = new SocialNetwork();

        $socialNetwork->order = $input['order'];
        $socialNetwork->type = $input['type'];
        $socialNetwork->url = $input['url'];

        $result = $page->socialNetworks()->save($socialNetwork);

        return $result;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
