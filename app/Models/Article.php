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

    protected $dates = ['published'];

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
    public function scopeWithImage($query)
    {
        return $query->where('image', '<>', null);
    }

    /**
     * Scope a query to order by publish time, showing latest first
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published', 'desc');
    }

    /**
     * Scope a query to order by clicks on the site
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->orderBy('site_clicks', 'desc');
    }
      
}
