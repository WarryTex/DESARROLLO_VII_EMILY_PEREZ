<?php
// Encabezado de la página
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Libros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: #0077cc;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .contenedor {
            width: 80%;
            margin: 20px auto;
        }
        .libro {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .libro:hover {
            transform: scale(1.02);
        }
        .libro h2 {
            color: #0077cc;
            margin-top: 0;
        }
        footer {
            text-align: center;
            background: #333;
            color: #ccc;
            padding: 15px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1>📚 Catálogo de Libros</h1>
    </header>
    <div class="contenedor">
