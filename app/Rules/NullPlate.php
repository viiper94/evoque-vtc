<?php

namespace App\Rules;

use App\Member;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class NullPlate implements Rule{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        $member = Member::where('user_id', Auth::id())->first();
        if($member->isTrainee()) return true;
        else{
            preg_match('%([0-9]{1,3})%', $value, $match);
            return isset($value) && count($match) > 0;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Номерной знак не должен быть пустым.';
    }

}
