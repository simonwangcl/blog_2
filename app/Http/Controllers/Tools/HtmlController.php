<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HtmlController extends Controller
{
    public function index()
    {
        return view('tools.html.index');
    }

    public function newPage(Request $request)
    {
        $html = $request->input('html');

        return view('tools.html.newPage', compact('html'));
    }
}
