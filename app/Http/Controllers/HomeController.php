<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Carbon\Carbon;

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
        $currentMonth = Carbon::now()->month;

        // Fetch current month's birthdays, ordered by the day of the month
        $birthdays = Profile::whereMonth('birthday', $currentMonth)
            ->orderBy('birthday', 'asc')
            ->get();

        // Fetch current month's anniversaries
        // This query fetches all "husband" profiles who have an anniversary this month,
        // and eager loads the corresponding "wife" profiles and the pivot data (anniversary date).
        $anniversaries = Profile::whereHas('marriageAsHusband', function ($query) use ($currentMonth) {
            // Check the pivot table ('marriages' or 'profile_profile') for the current month
            $query->whereMonth('anniversary_date', $currentMonth);
        })
            ->with(['marriageAsHusband' => function ($query) use ($currentMonth) {
                // Eager load only the relationships that match the current month
                $query->whereMonth('anniversary_date', $currentMonth)
                    ->withPivot('anniversary_date');
            }])
            ->get();

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
            ->with('profiles_role', $profiles_role)
            ->with('birthdays', $birthdays)
            ->with('anniversaries', $anniversaries);
    }
}
