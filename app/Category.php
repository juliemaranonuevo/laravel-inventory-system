<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_categories';

    protected $guarded = [];

    /**
     * Eloquent Relationship
     * Category to CategoryField : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function category_field()
    {

        return $this->hasMany(CategoryField::class);

    }

     /**
     * Eloquent Relationship
     * Category to Item : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item()
    {

        return $this->hasMany(Item::class);
        
    }


}
