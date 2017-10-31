<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;
use App\Mail\SendTestMail;
use Illuminate\Support\Facades\Mail;

class TestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $when = Carbon::now()->addMinutes(1);

//        Mail::to("714355794@qq.com")->queue(new SendTestMail("simon"));
        Mail::to("714355794@qq.com")->later($when, new SendTestMail("simon"));
//        $name = 'simonwang';
//        dispatch((new SendEmailJob($name))->delay(Carbon::now()->addMinutes(1)));
    }
}
