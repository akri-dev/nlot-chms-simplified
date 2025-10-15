<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;

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
        $all_profiles = $this->profile_m->orderBy('last_name', 'asc')->get();
        return view('profiles.index')
            ->with('all_profiles', $all_profiles);
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function save(Request $request)
    {
        // $pro_m = $this->profile_m;

        $gender_values = array_column(GenderEnum::cases(), 'value');
        $marital_status_values = array_column(MaritalStatusEnum::cases(), 'value');

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'street_address' => 'nullable|string',
            'city_address' => 'nullable|string',
            'contact_number' => 'nullable|regex:/(09)[0-9]{9}/',
            'email_address' => 'nullable|email|unique:profiles,email_address',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:' . implode(',', $gender_values),
            'marital_status' => 'nullable|in:' . implode(',', $marital_status_values),
            'joined_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
        ]);

        $validatedData['added_by_id'] = Auth::id();

        $profile = Profile::create($validatedData);

        return redirect()->route('profiles');
    }

    public function anniversaries()
    {
        return view('profiles.anniversaries');
    }

    public function profile(Profile $profile)
    {
        return view('profiles.profile-list.data', ['profile' => $profile]);
    }

    public function update()
    {
        
    }
}
