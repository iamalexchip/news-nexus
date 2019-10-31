<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    /**
     * Get the website that owns the source.
     */
    public function website()
    {
    	return $this->belongsTo('App\Models\Website');
    }

    /**
     * Get the categories of a source.
     */
    public function categoryLinks()
    {
        return $this->hasMany('App\Models\SourceCategory');
    }

    /**
     * Get the categories of a source.
     */
    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 'source_categories'
        );
    }

    /**
     * Scope a query to only include active sources.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Generates an article link
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        return '/source/'.str_slug($this->title).'_s'.$this->id;
    }
}
