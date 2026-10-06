<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    public function tentang(): View
    {
        return view('pages.tentang');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function privasi(): View
    {
        return view('pages.privasi');
    }

    public function syarat(): View
    {
        return view('pages.syarat');
    }

    public function kontak(): View
    {
        return view('pages.kontak');
    }
}
