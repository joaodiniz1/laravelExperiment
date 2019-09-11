<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DealDetail extends Model
{
    protected $fillable = [
        'id',
        'hour',
        'accepted',
        'refused'
     ];

     public $timestamps = false;

     /* Returns all data imported on database from the last file */
     public static function getAll(){
        $rows = DB::table('clients')
        ->join('client_deals', 'clients.id', '=', 'client_deals.client_id')
        ->join('deal_details', 'client_deals.id', '=', 'deal_details.client_deal_id')
        ->orderby('hour')->get(array('clients.id as cid','client_deals.id as cdid','clients.*','deal_details.*','client_deals.*'));
        return $rows;
     }
}
