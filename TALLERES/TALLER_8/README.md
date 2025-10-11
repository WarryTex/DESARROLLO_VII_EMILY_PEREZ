# Sistema de Gestión de Biblioteca

Este proyecto implementa un sistema simple de gestión de biblioteca utilizando PHP con dos enfoques: MySQLi y PDO. Permite gestionar libros, usuarios y préstamos con funcionalidades de creación, lectura, actualización, eliminación, búsqueda, paginación y manejo de transacciones.

## Instrucciones de Configuración

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache, Nginx, etc.)

### Configuración de la Base de Datos
1. Crea una base de datos en MySQL ejecutando el siguiente script SQL:

```sql
CREATE DATABASE biblioteca;
USE biblioteca;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    isbn VARCHAR(13) NOT NULL UNIQUE,
    anio_publicacion INT NOT NULL,
    cantidad_disponible INT NOT NULL
);

CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    libro_id INT,
    fecha_prestamo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion TIMESTAMP NULL,
    estado ENUM('activo', 'devuelto') DEFAULT 'activo',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);





Actualiza las credenciales de la base de datos en mysqli/config.php y pdo/config.php si es necesario (por ejemplo, DB_USER, DB_PASS).

Configuración del Proyecto





Clona o descarga el repositorio en la carpeta de tu servidor web.



Asegúrate de que la carpeta TALLER_8 esté accesible desde tu servidor web.



Configura los permisos necesarios para que el archivo errors.log pueda escribirse (si no existe, se creará automáticamente).



Accede al sistema a través de:





MySQLi: http://localhost/TALLER_8/mysqli/index.php



PDO: http://localhost/TALLER_8/pdo/index.php

Estructura del Proyecto





mysqli/: Contiene la implementación con MySQLi.





config.php: Configuración de la conexión a la base de datos y función de registro de errores.



libros.php: Gestión de libros (añadir, listar, buscar, actualizar, eliminar).



usuarios.php: Gestión de usuarios (registrar, listar, buscar, actualizar, eliminar).



prestamos.php: Gestión de préstamos (registrar, listar activos, registrar devoluciones, mostrar historial).



index.php: Página principal con enlaces a las funcionalidades.



pdo/: Contiene la implementación con PDO, siguiendo la misma estructura que MySQLi.



README.md: Este archivo con instrucciones y descripción.

Consideraciones Especiales





Las contraseñas de los usuarios se almacenan hasheadas usando password_hash.



Las operaciones de préstamo y devolución usan transacciones para garantizar la integridad de los datos.



La paginación está configurada para mostrar 5 elementos por página.



Los errores se registran en un archivo errors.log en la carpeta correspondiente (mysqli o pdo).



Las entradas de usuario se validan y sanitizan para prevenir inyecciones SQL y ataques XSS.



Se recomienda añadir estilos CSS para mejorar la presentación, ya que los formularios y tablas son básicos.

Comparación entre MySQLi y PDO





MySQLi:





Ventajas: Fácil de usar para quienes están acostumbrados a MySQL, ofrece funciones específicas para MySQL, como mysqli_insert_id. La sintaxis es más explícita para consultas preparadas.



Desventajas: Solo funciona con MySQL, lo que limita la portabilidad. El manejo de excepciones requiere configuración explícita (mysqli_report).



Experiencia: Más verboso, pero claro para operaciones simples. La gestión de transacciones y errores es directa, aunque menos elegante que PDO.



PDO:





Ventajas: Soporta múltiples bases de datos, lo que lo hace más portable. El manejo de excepciones es más integrado y la sintaxis es más limpia para consultas preparadas con parámetros con nombre.



Desventajas: Puede ser ligeramente más lento que MySQLi en algunos casos. Requiere un poco más de configuración inicial para establecer atributos como el modo de error.



Experiencia: Más flexible y moderno, ideal para proyectos que puedan cambiar de base de datos. La sintaxis es más concisa, especialmente para consultas complejas.

En general, PDO es preferible para proyectos más grandes o que requieran portabilidad, mientras que MySQLi es adecuado para aplicaciones más simples centradas en MySQL.