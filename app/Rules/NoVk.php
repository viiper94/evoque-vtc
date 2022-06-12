<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoVk implements Rule{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        return !str_contains($value, 'userapi.com');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(){
        return 'Запрещено использование ссылок на изображения из ВК! Используйте Imgur, например.';
    }

}
