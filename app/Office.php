<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

    protected $table = 'tbl_offices';

    protected $guarded = [];

    /**
     * Eloquent Relationship
     * Office to Sticker : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function sticker()
    {

        return $this->hasMany(Sticker::class);
        
    }
    
}
