<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Item extends Model
{
    protected $table = 'tbl_items';
    
    protected $guarded = [];

    /**
     * Eloquent Relationship
     * Item to ItemField : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item_field()
    {
        return $this->hasMany(ItemField::class);
    }

    /**
     * Eloquent Relationship
     * Item to Sticker : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function sticker()
    {
        return $this->hasMany(Sticker::class);
    }

    /**
     * Eloquent Relationship
     * Item to Category : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
