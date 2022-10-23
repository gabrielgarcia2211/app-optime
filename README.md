#  CRUD Productos - OPTIME

[![N|Solid](https://www.rananegra.es/uploads/images/programacion-web-symfony.png)](https://laravel.com)

Esta aplicacion fue realizada en Symfony como framework de Backend con persistencia de datos en MySQL y Frontend Twig


## Caracteristicas

- Crear entidades.
- Agregar Nuevos Productos.
- Validaciones.
- Listar productos.
- Editar Productos.
- Eliminar Productos.
- Instalacion e implementacion EXCEL.

## Tecnologias

* [Symfony](https://twig.symfony.com/)  Es un framework PHP de tipo full stack construido con varios componentes independientes creados por el propio proyecto Symfony”, es decir, por si mismo. Esa es la respuesta que ofrece la propia marca a la pregunta sobre qué es Symfony. A su vez, está planteado para trabajar lo que a nivel de desarrollo se conoce como backend.


* [Twig](https://twig.symfony.com/) Twig es un motor de plantillas desarrollado para el lenguaje de programación PHP y que nace con el objetivo de facilitar a los desarrolladores de aplicaciones web que utilizan la arquitectura MVC el trabajo con la parte de las vistas, gracias a que se trata de un sistema que resulta muy sencillo de aprender y capaz de generar plantillas con un código preciso y fácil de leer.

* [MySQL](https://dev.mysql.com/downloads/mysql/) MySQL es el sistema de gestión de bases de datos relacional más extendido en la actualidad al estar basada en código abierto. Desarrollado originalmente por MySQL AB, fue adquirida por Sun MicroSystems en 2008 y esta su vez comprada por Oracle Corporation en 2010, la cual ya era dueña de un motor propio InnoDB para MySQL.

* [Postman](https://www.postman.com/downloads/) es una aplicación que nos permite realizar pruebas API. Es un cliente HTTP que nos da la posibilidad de testear 'HTTP requests' a través de una interfaz gráfica de usuario, por medio de la cual obtendremos diferentes tipos de respuesta que posteriormente deberán ser validados


* [Laragon](https://laragon.org/download/index.html) Es una herramienta bastante robusta que trae consigo aplicaciones utiles para el desarrollo del aplicativo, por ejemplo, Apache 2.4, Nginx, MySQL 5.7, PHP 7.4, Redis, Memcached Node.js 14, npm, git.

## NOTA

`PHP VERSION, DEBE SER >= 8.0 PARA EVITAR INCOMPATIBILIDAD CON COMPOSER`


## Instalación (Uso local)
- Primero se debe instalar [Laragon](https://laragon.org/download/index.html) en la maquina.

- Clonar proyecto, en el server apache local (htdocs/www)
    ```bash
    git clone https://github.com/gabrielgarcia2211/optime.git
    ```

- Despues se debe ir al directorio del proyecto
    ```bash
    cd my-project
    ```

- Luego se **Instala/Actualiza** las dependencias composer en la terminal del proyecto, con el siguiente comando:
    ```sh
    composer install
    ```
- Luego se **Instala/Actualiza** las dependencias npm  en la terminal del proyecto, con el siguiente comando:
    ```sh
    npm install
    ```
- Compilamos:
    ```sh
     yarn run encore dev
    ```

- Luego se debemos duplicar el archivo **.env.example** cambiamos el nombre a **.env**, y debemos configurar lo siguiente:
    ```sh
    DATABASE_URL=mysql://root:@127.0.0.1:3306/{name_database}?serverVersion=8&charset=utf8mb4
    ```
- Despues abrimos nuestro navegador web, en la ruta:
    ```bash
    http://localhost/optime/public/products
    ```
## Autores

- [@gabrielgarcia2211](https://github.com/gabrielgarcia2211)

## Contribuyentes
*¡Las contribuciones son siempre bienvenidas!*

## Licencia

[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://github.com/tterb/atomic-design-ui/blob/master/LICENSEs)