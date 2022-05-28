<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class RpLevel implements ImplicitRule, DataAwareRule{

    protected $data = [];
    protected $check;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($check){
        $this->check = $check;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        if(isset($this->data['accept'])){
            if(!isset($value) && !isset($this->data[$this->check])) return false; // none of the fields are fulfilled
            if(isset($value) && isset($this->data[$this->check])) return false; // both fields are fulfilled
            if(isset($this->data[$this->check])) return true; // other field is fulfilled
            elseif(isset($value)) return true; // current field is fulfilled
            else return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(){
        return 'При приеме отчёта одно из значений уровня должно быть заполнено!';
    }

}
