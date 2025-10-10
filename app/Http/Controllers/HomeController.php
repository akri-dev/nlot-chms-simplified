<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Profile;

class HomeController extends Controller
{
    private $profile_m;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->middleware('auth');
        $this->profile_m = $profile;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $profile_count = $this->profile_m->count();
        return view('home')
                ->with('profile_count', $profile_count);
    }
}
