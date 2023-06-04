<?php

$array = [
    'TruckersMP' => 'TruckersMP',
    'TrucksBook' => 'TrucksBook',
    'from' => 'С',
    'to' => 'До',
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    '6' => '6',
];

$roles = \Illuminate\Support\Facades\Lang::get('roles');

return array_merge($array, $roles);
