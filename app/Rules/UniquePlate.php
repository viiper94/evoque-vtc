<?php

namespace App\Rules;

use App\Member;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UniquePlate implements Rule{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        if (!isset($value)) return true;
        if(strlen($value) === 1) $value = '00'.$value;
        if(strlen($value) === 2) $value = '0'.$value;
        $match = Member::where([
            ['plate', '=', $value],
            ['user_id', '!=', Auth::id()]
        ])->first();
        return !$match;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Номер уже занят.';
    }

}
