<?php


return [
    'after' => 'Pole :attribute musi zawierać datę późniejsza niż w polu :date.',
    'after_or_equal' => 'Pole :attribute musi zawierać datę równą lub późniejsza od daty pola :date.',
    'before' => 'Pole :attribute musi być datą przed :date.',
    'required' => 'Pole :attribute jest wymagane.',
    'min' => [
        'array' => 'Pole :attribute musi mieć co najmniej :min elementów.',
        'file' => 'Pole :attribute musi mieć co najmniej :min kilobajtów.',
        'numeric' => 'Pole :attribute musi mieć co najmniej :min.',
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],
    'password' => [
        'letters' => 'Pole :attribute musi zawierać co najmniej jedną literę.',
        'mixed' => 'Pole :attribute musi zawierać co najmniej jedną wielką i jedną małą literę.',
        'numbers' => 'Pole :attribute musi zawierać co najmniej jedną cyfrę.',
        'symbols' => 'Pole :attribute musi zawierać co najmniej jeden symbol.',
        'uncompromised' => 'Podane :attribute pojawiło się w wycieku danych. Wybierz inne :attribute.',
    ],

    'attributes' => [
        'name' => 'nazwa',
        'description' => 'opis',
        'image' => 'obraz',
        'is_active' => 'aktywny',
        'email' => 'email',
        'password' => 'hasło',
        'start_time' => 'czas rozpoczęcia',
        'end_time' => 'czas zakończenia',
    ]

];
