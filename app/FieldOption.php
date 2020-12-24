<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{
    protected $table = 'tbl_field_options';
    
    protected $guarded = [];

    /**
     * Eloquent Relationship
     * FieldOption to Field : belongsTo
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function field()
    {

        return $this->belongsTo(Field::class);

    }

}
