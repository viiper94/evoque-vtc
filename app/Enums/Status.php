<?php

namespace App\Enums;

enum Status :int{

    case NEW = 0;
    case ACCEPTED = 1;
    case DECLINED = 2;
    case IN_PROGRESS = 3;

    public function colorClass($prefix = null) :string{
        return ($prefix ?? '') . match($this->name){
            'NEW' => 'primary',
            'ACCEPTED' => 'success',
            'DECLINED' => 'danger',
            'IN_PROGRESS' => '',
        };
    }

}
