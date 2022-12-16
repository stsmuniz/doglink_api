<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type', 'data', 'order', 'page_id'];

    public function store(array $input, Page $page)
    {
        $section = new Section();
        $repositoryName = 'App\\Repositories\\'.$input['type'].'Repository';

        $section->order = $input['order'];
        $section->type = $input['type'];
        $section->data = (new $repositoryName($input['data']))->create();

        $page->sections()->save($section);
    }

    public function getData()
    {
        $repositoryName = 'App\\Repositories\\'.$this->type.'Repository';

        $data = (new $repositoryName($this->data))->retrieve();

        return $data;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
