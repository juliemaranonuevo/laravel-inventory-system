<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{

    protected $table = 'tbl_stickers';

    protected $casts = [
        'updated_at' => 'datetime:m-d-Y - H:i:s',
    ];

    protected $guarded = [];

    /**
     * Eloquent Relationship
     * Sticker to Item : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item()
    {

        return $this->belongsTo(Item::class);

    }

    /**
     * Eloquent Relationship
     * Sticker to Office : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function office()
    {

        return $this->belongsTo(Office::class);

    }

    /**
     * Eloquent Relationship
     * Sticker to ItemTransaction : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item_transaction()
    {

        return $this->belongsTo(ItemTransaction::class);
        
    }
    
}
