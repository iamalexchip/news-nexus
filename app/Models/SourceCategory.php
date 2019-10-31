<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceCategory extends Model
{
    /**
     * Get the source.
     */
    public function source()
    {
    	return $this->belongsTo('App\Models\Source');
    }

    /**
     * Get the category.
     */
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
}
