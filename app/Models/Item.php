<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_id',
        'guid',
        'title',
        'url',
        'summary',
        'content',
        'image',
        'published'
    ];

    /**
     * Get the source that owns the item.
     */
    public function source()
    {
        return $this->belongsTo('App\Models\Source');
    }
}
