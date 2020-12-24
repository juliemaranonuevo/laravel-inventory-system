<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $table = 'tbl_audit_trails';
    
    protected $casts = [
        'updated_at' => 'datetime:m-d-Y - H:i:s',
    ];

    protected $guarded = [];

}
