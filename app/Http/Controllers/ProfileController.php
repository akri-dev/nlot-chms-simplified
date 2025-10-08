<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    private $profile_m;

    public function __construct(Profile $profile)
    {
        $this->profile_m = $profile;
    }

    public function index()
    {
        return view('profiles.index');
    }
}
