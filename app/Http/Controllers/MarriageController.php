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

    public function destroy(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            // Ensure both IDs are provided and exist in the profiles table
            'husband_id' => 'required|exists:profiles,id',
            'wife_id' => 'required|exists:profiles,id',
        ]);

        // 2. Retrieve the Husband Model
        $husband = Profile::find($validated['husband_id']);

        // 3. Detach the relationship
        // The detach() method removes the entry from the pivot table based on the given ID(s).
        $detached_count = $husband->marriageAsHusband()->detach($validated['wife_id']);

        if ($detached_count > 0) {
            // Success response if a record was deleted
            $message = 'Marriage record deleted successfully!';
            $status = 'success';
        } else {
            // Handle case where the marriage record didn't exist
            $message = 'No marriage record found between these two profiles.';
            $status = 'error';
        }

        // 4. Return Redirect
        return redirect()->route('profiles.anniversaries')
            ->with($status, $message);
    }
}
