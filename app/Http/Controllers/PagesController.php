<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class PagesController extends Controller
{
    public function show($slug){
        $page = Page::findBySlug($slug);
        if(!$page) abort(404);
        return view('page.view', ['page'=>$page]);
    }
}
