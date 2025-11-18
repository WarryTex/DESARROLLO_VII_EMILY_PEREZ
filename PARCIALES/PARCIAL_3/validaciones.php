<?php

$usuarios = [

    'profesor' => [
        'profe25'     => 'DS7EP',
    ],
    'estudiante' => [
        'estu25'     => 'DS7ET',
    ]
    ];


function validarNombre($nombre) {
    return !empty($nombre) && strlen($nombre) <= 3;
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}



?>