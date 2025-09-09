<?php
$antiguedad_meses= 12;
function calcular_promocion($antiguedad_meses) {

    if($antiguedad_meses=<3){
        echo "No tiene antiguedad,no aplica promocióm";
    }
    elseif($antiguedad_meses>=12){
        echo "Tiene un 8% de descuento";
    }
    elseif($antiguedad_meses>=24){
        echo"Tiene un 12% de descuento";
    }
    elseif($antiguedad_meses>24){
        echo"Tiene un 20% de descuento";
    }

    return $mensaje;
}

$cuota_base= 75.50;
function calcular_seguro_medico($cuota_base){
    $resultado=0;
    resultado=cuota_base*0.05;
    return $resultado;
}

function calcular_cuota_final($cuota_base,$porcentaje_descuento,$seguro_medico){
    descuento=9.75;
    descuento=
  

}

    




