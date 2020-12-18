<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function locale()
    {
        $cookie = cookie()->forever('locale__myapp', request('locale'));
        cookie()->queue($cookie);

        return ($return = request('return'))
            ? redirect(urldecode($return))->withCookie($cookie)
            : redirect('/')->withCookie($cookie);
    }
}
