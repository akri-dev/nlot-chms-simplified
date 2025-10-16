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
        $profile_active = \App\Models\Profile::where('member_status', 'Active')->count();
        $profile_inactive = \App\Models\Profile::where('member_status', 'Inactive')->count();
        $profiles_role = Profile::whereHas('roles', function ($query) {
        $query->where('role_id', '>=', 2);
    })->count();
        return view('home')
                ->with('profile_count', $profile_count)
                ->with('profile_active', $profile_active)
                ->with('profile_inactive', $profile_inactive)
                ->with('profiles_role', $profiles_role);
    }
}
