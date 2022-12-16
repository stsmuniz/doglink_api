<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary_color', 'secondary_color', 'background_color', 'text_color',
        'custom_url', 'theme', 'title', 'tagline'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function socialNetworks()
    {
        return $this->hasMany(SocialNetwork::class);
    }
}

