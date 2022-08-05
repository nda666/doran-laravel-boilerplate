<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Home extends Controller
{
    /**
     * Home
     *
     * This endpoint allows you to add a word to the list.
     * It's a really useful endpoint, and you should play around
     * with it for a bit.
     * <aside class="notice">We mean it; you really should.😕</aside>
     *
     * @queryParam name string Add your name here
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return ['message' => 'Hello'.($request->has('name') ? ', '.$request->get('name') : '')];
    }
}
