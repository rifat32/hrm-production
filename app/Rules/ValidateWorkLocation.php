<?php

namespace App\Rules;

use App\Models\WorkLocation;
use Illuminate\Contracts\Validation\Rule;

class ValidateWorkLocation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

            $created_by  = NULL;
            if(auth()->user()->business) {
                $created_by = auth()->user()->business->created_by;
            }

            $exists = WorkLocation::where("work_locations.id",$value)
            ->when(empty(auth()->user()->business_id), function ($query) use ( $created_by) {
                $query->when(auth()->user()->hasRole('superadmin'), function ($query)  {
                    $query->forSuperAdmin('work_locations');
                }, function ($query) use ($created_by) {
                    $query->forNonSuperAdmin('work_locations', 'disabled_work_locations', $created_by);
                });
            })
            ->when(!empty(auth()->user()->business_id), function ($query) use ( $created_by) {
                $query->forBusiness('work_locations', "disabled_work_locations", $created_by);
            })

            ->exists();

            return $exists;


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid.';
    }
}