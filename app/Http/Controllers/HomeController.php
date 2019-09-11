<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DealDetail;
use App\File;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application main page
     *
     * @return void
     */
    public function index(){

        /* Get metadata from last csv file uploaded on application if exists */
        $lastFile = File::lastFile();
        /* Get all deals imported into database if exists */
        $deals = DealDetail::getAll();
        return view('home',['deals'=>$deals,'lastFile'=>$lastFile]);
    }

    

    
}