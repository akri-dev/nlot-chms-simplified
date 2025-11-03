<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Profile;

class MarriageController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'husband_id' => 'required|exists:profiles,id',
            'wife_id' => 'required|exists:profiles,id',
            'marriage_date' => 'required|date',
        ]);

        // 2. Retrieve Models
        $husband = Profile::find($validated['husband_id']);
        $wife = Profile::find($validated['wife_id']);

        $anniversary_date = Carbon::parse($validated['marriage_date'])->format('Y-m-d');

        $husband->marriageAsHusband()->attach($wife->id, [
            'anniversary_date' => $anniversary_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // If this line is reached, the save worked.
        return redirect()->route('profiles.anniversaries')
            ->with('success', 'Marriage record created successfully using Eloquent!');
    }
}
