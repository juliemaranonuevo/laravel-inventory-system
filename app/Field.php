<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    
    protected $table = 'tbl_fields';
    
    protected $guarded = [];
    
    /**
     * Eloquent Relationship
     * Field to FieldOption : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function option()
    {
        return $this->hasMany(FieldOption::class);
    }
    
     /**
     * Eloquent Relationship
     * Field to CategoryField : hasMany
     * @param Model, Parent Foreign Key, Parent Primary Key
     */
    public function category_field()
    {
        return $this->hasMany(CategoryField::class);
    }
    
}
