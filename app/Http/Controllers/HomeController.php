<?php

namespace App\Http\Controllers;

use App\Classes\FTP;

class HomeController extends Controller
{
    public function index(FTP $ftp)
    {
        dd($ftp->getFile());
    }
}
