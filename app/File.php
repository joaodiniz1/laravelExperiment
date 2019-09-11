<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Client;
use App\ClientDeal;
use App\DealDetail;

class File extends Model
{

    /* Get metadata from last csv file uploaded on application if exists */
    public static function lastFile(){
        if(Storage::exists('/csvFiles/csvFile.csv')) {
            $lastFile['lastModified'] = date('d/m/Y H:i:s',Storage::lastModified('/csvFiles/csvFile.csv'));     
            $file = new \SplFileObject(storage_path().'\app\csvFiles\csvFile.csv');
            $file->seek(PHP_INT_MAX);
            $lastFile['rows'] = $file->key();
            return $lastFile;
        }else{
            return false;
        }
    }

    /* Process .csv file after upload  */
    public static function processCsv(String $filePath){
        $file = fopen($filePath, "r");
        $clients = array();
        $clientDeals = array();
        $dealDetails = array();
        while (($data = fgetcsv($file, 0, ",")) !==FALSE) {
            $client = array_map('trim', explode('@', $data[0]));
            if (isset($client[1])){
                /* Extract clients removing duplicate ones */
                $clients[$client[1]]['id'] = $client[1];
                $clients[$client[1]]['name'] = $client[0];
                $clientDeal = array_map('trim', explode('#', $data[1]));
                if (isset($clientDeal[1])){
                      /* Extract deals from client already removing duplicate ones */
                    $clientDeals[$clientDeal[1]]['id'] = $clientDeal[1];
                    $clientDeals[$clientDeal[1]]['title'] = $clientDeal[0];  
                    $clientDeals[$clientDeal[1]]['client_id'] = $client[1];  
                    if (isset($data[4])){
                        /* Extract all deal details from deal */
                        $dealDetail = array();
                        $dealDetail['client_deal_id'] = $clientDeal[1];
                        $dealDetail['hour'] = trim($data[2]);
                        $dealDetail['accepted'] = trim($data[3]);
                        $dealDetail['refused'] = trim($data[4]);
                        $dealDetails[] = $dealDetail;
                    }
                }   
            }
        }

        /* Clear database from last file imported */
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DealDetail::truncate();
        ClientDeal::truncate();
        Client::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /* Insert data extracted to clean database */
        foreach ($clients as $client){
            Client::insert($client);
        }
        foreach ($clientDeals as $clientDeal){
            ClientDeal::insert($clientDeal);
        }
        foreach ($dealDetails as $dealDetail){
            DealDetail::insert($dealDetail);
        }
    }
}
