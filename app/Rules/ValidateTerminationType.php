<?php

namespace App\Rules;

use App\Models\TerminationType;
use Illuminate\Contracts\Validation\Rule;

class ValidateTerminationType implements Rule
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

        $exists = TerminationType::where("termination_types.id",$value)
        ->when(empty(auth()->user()->business_id), function ($query) use ( $created_by) {
            $query->when(auth()->user()->hasRole('superadmin'), function ($query)  {
                $query->forSuperAdmin('termination_types');
            }, function ($query) use ($created_by) {
                $query->forNonSuperAdmin('termination_types', 'disabled_termination_types', $created_by);
            });
        })
        ->when(!empty(auth()->user()->business_id), function ($query) use ( $created_by) {
            $query->forBusiness('termination_types', "disabled_termination_types", $created_by);
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
