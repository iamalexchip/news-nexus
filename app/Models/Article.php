<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
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

    /**
     * Scope a query to only include articles with images.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasImage($query)
    {
        return $query->where('image', '<>', null);
    }

    /**
     * Generates an article link
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        return '/article/'.str_slug($this->title).'_p'.$this->id;
    }

    /**
     * Generates an article link
     *
     * @return string
     */
    public function getTimeAttribute()
    {
        return '10 hours';
    }
      
}
