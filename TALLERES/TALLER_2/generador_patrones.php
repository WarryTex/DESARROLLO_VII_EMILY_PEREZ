<?php
// generador  patrones
echo "Triángulo rectángulo:\n";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "\n"; // Salto de línea después de cada fila
}
?>