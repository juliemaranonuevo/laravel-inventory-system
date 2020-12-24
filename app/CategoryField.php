<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    protected $table = 'tbl_category_fields';
    
    protected $guarded = [];

    /**
     * Eloquent Relationship
     * CategoryField to Category : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Eloquent Relationship
     * CategoryField to Field : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

}
