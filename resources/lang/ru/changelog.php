<?php

$array = [
    'TruckersMP' => 'TruckersMP',
    'TrucksBook' => 'TrucksBook',
    'from' => 'С',
    'to' => 'До'
];

$roles = \Illuminate\Support\Facades\Lang::get('roles');

return array_merge($array, $roles);
