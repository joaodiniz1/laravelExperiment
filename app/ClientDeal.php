<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientDeal extends Model
{
    protected $fillable = [
        'id',
        'title',
        'client_id'
     ];

     public $timestamps = false;
}
