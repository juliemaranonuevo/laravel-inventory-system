<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemField extends Model
{
    
    protected $table = 'tbl_item_fields';
    
    protected $guarded = [];

    /**
     * Eloquent Relationship
     * ItemField to Item : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Eloquent Relationship
     * ItemField to CategoryField : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function category_field()
    {
        return $this->belongsTo(CategoryField::class);
    }

}
