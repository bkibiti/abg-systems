<?php

namespace App\Http\Controllers;

class MoreController extends Controller
{
    /**
     * Phone-only "More" tab: profile header plus grouped links to the pages that don't fit
     * on the bottom tab bar. Every row is permission-gated the same way layouts.menu is.
     */
    public function index()
    {
        return view('more.index');
    }
}
