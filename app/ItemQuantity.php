<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemQuantity extends Model
{
    protected $table = 'tbl_item_quantities';

    protected $guarded = [];

    /**
     * Eloquent Relationship
     * ItemQuantity to Item : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Eloquent Relationship
     * ItemQuantity to Office : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Eloquent Relationship
     * ItemQuantity to ItemTransaction : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item_transaction()
    {
        return $this->hasMany(ItemTransaction::class);
    }

}
