<?php

// app/Models/Profile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\MemberStatusEnum;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'street_address',
        'city_address',
        'contact_number',
        'email_address',
        'birthday',
        'gender',
        'marital_status',
        'joined_date',
        'baptism_date',
        'added_by_id',
        'last_updated_by_id',
        'member_status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gender' => GenderEnum::class,
        'marital_status' => MaritalStatusEnum::class,
        'joined_date' => 'date',
        'baptism_date' => 'date',
        'birthday' => 'date',
        'member_status' => MemberStatusEnum::class
    ];

    protected function middleNameInitial(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => strtoupper(substr($attributes['middle_name'], 0, 1)),
        );
    }

    protected function contactNumberSpaced(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                // Get the raw contact number from the database
                $number = $attributes['contact_number'];

                // Clean the number (remove non-digits)
                $cleanedNumber = preg_replace('/[^0-9]/', '', $number);

                // Format the number with spaces
                // For a Philippine number (e.g., 09171234567), format as 0917 123 4567
                if (strlen($cleanedNumber) === 11) {
                    return substr($cleanedNumber, 0, 4) . ' ' . substr($cleanedNumber, 4, 3) . ' ' . substr($cleanedNumber, 7);
                }

                return $cleanedNumber;
            },
        );
    }

    // Define a new accessor for the 'birthday' attribute
    protected function formattedBirthday(): Attribute
    {
        return Attribute::make(
            // This is the 'getter' logic when you call $profile->formatted_birthday
            get: fn(mixed $value, array $attributes) =>
            // We access the original 'birthday' attribute's value, which is a Carbon instance,
            // and format it.
            $this->birthday ? $this->birthday->format('F d, Y') : null,
        );
    }

    // Repeat for other date fields (e.g., joined_date)
    protected function formattedJoinedDate(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) =>
            $this->joined_date ? $this->joined_date->format('F d, Y') : null,
        );
    }

    // Repeat for other date fields (e.g., joined_date)
    protected function formattedBaptismDate(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) =>
            $this->baptism_date ? $this->baptism_date->format('F d, Y') : null,
        );
    }

    // Notes:

    // + Multiple Roles can be assigned
    // + By default, new profile will be 'Member'
    // + Staff and Board are not Volunteers
    // + If inactive, status will default to 'Member'
    // + Ministry Leader can be 'Staff' or 'Volunteer'
    // + Lead Pastor cannot be 'Staff' or 'Volunteer'

    public function roles()
    {
        // The second argument is the pivot table name (optional if following Laravel conventions)
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleSlug): bool
    {
        // Check if the related roles collection contains the slug
        return $this->roles->contains('slug', $roleSlug);
    }

    protected function setMemberStatusAttribute($value)
    {
        // If status is being set to 'Inactive'
        if ($value === MemberStatusEnum::INACTIVE) {
            // Find the Member role
            $memberRole = \App\Models\Role::where('slug', 'member')->first();

            if ($memberRole) {
                // Detach all current roles, and only attach the 'Member' role.
                // This ensures "If inactive, status will default to 'Member'".
                $this->roles()->sync([$memberRole->id]);
            }
        }

        $this->attributes['member_status'] = $value;
    }

    protected static function booted()
    {
        static::created(function (Profile $profile) {
            // By default, new profile will be 'Member'
            $memberRole = \App\Models\Role::where('slug', 'member')->first();
            if ($memberRole) {
                $profile->roles()->attach($memberRole->id);
            }
        });
    }
}
