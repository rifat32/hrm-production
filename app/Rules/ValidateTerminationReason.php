<?php

namespace App\Rules;

use App\Models\TerminationReason;
use Illuminate\Contracts\Validation\Rule;

class ValidateTerminationReason implements Rule
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

        $exists = TerminationReason::where("termination_reasons.id",$value)
        ->when(empty(auth()->user()->business_id), function ($query) use ( $created_by) {
            $query->when(auth()->user()->hasRole('superadmin'), function ($query)  {
                $query->forSuperAdmin('termination_reasons');
            }, function ($query) use ($created_by) {
                $query->forNonSuperAdmin('termination_reasons', 'disabled_termination_reasons', $created_by);
            });
        })
        ->when(!empty(auth()->user()->business_id), function ($query) use ( $created_by) {
            $query->forBusiness('termination_reasons', "disabled_termination_reasons", $created_by);
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
