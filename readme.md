

# Sistema Hoja Ruta V2


## Documentacion ,Instalacion e Instrucciones de Uso


## Descripción
Versión 2 del proyecto Sistema de Ruta - Apuesta Total

Desarrollado en Laravel 5.8

## Requerimientos

Descargar y ejecutar proyecto [SupervisoresAPI](https://github.com/dcancharir/SupervisoresAPI.git)

Ejecutar metodo `api/v1/generarVentaDiaria`

## Instalación

Instalar paquetes con:

```php
// Instalacion de paquetes
composer install || composer update

```

Agregar variables en archivo .env, modificar los valores si desea

```php
#Distancia en metros aceptada para realizar la visita
DISTANCIA_ACEPTADA=20000000
#Usuario Administrador
USUARIO_ADMINISTRADOR=admin@admin.com
```
Crear el usuario agregado en `USUARIO_ADMINISTRADOR` en tabla supervisores

Ejecutar comando :

```php
    php artisan crear:rutas
```
Para generar las rutas diarias por supervisor, previa generacion de venta diaria en [SupervisoresAPI](https://github.com/dcancharir/SupervisoresAPI.git)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
