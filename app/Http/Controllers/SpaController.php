<?php

namespace App\Http\Controllers;

class SpaController extends Controller
{
    /**
     * Redirect all requests to single entry point for SPA.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('app');
    }
}
