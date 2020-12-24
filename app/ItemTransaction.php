<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ItemTransaction extends Model
{

    protected $table = 'tbl_item_transactions';

    protected $casts = [
        'updated_at' => 'datetime:m-d-Y - H:i:s',
        'transaction_date' => 'date:m-d-Y',
    ];

    protected $guarded = [];

    /**
     * Eloquent Relationship
     * ItemTransaction to ItemQuantity : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function item_quantity()
    {

        return $this->belongsTo(ItemQuantity::class);
        
    }
    
}
