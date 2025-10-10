<?php

// app/Models/Profile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;


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
    ];

    protected function middleNameInitial(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => strtoupper(substr($attributes['middle_name'], 0, 1)),
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
}
