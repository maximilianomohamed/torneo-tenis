# Test Desarrollador Backend torneo-tenis by Mohamed Maximiliano 

# Modelo de Objetos UML
- Este diagrama refleja las entidades principales como Jugador, Torneo, y Partido, junto con sus relaciones y atributos.

https://lucid.app/lucidchart/9984ef01-0c89-467b-9a0c-a709cedc5429/edit?viewport_loc=83%2C207%2C2219%2C1076%2CHWEp-vi-RSFO&invitationId=inv_2e292041-80f1-4cf9-bb11-eb18b017f0da

# Estructura y arquitectura
Dado que se prefiere un modelado en capas o arquitectura limpia seguí los principios de DDD (Domain-Driven Design) estructurando el proyecto de la siguiente manera:
    - Capa de dominio : adonde se modelaron los objetos y las reglas de negocio, como jugadores, torneos y reglas paa calcular ganadores.
    - Capa de aplicación: adonde se realiza la coordinación del flujo de aplicación, como la creación de torneos y simulaciones. (Servicios)
    - Capa de infraestructura : adonde se integra la base de datos y servicios externos (en caso de tener)
    - Capa de presentación : adonde se configuran las rutas de la api y documentación

### Documentación de la API

La API REST fue documentada utilizando Swagger, lo que facilita la interacción con los endpoints de manera clara y concisa.

    - Para acceder a la documentación, ejecutar el proyecto y acceder a: http://localhost:8000/api/doc (no pude configurarlo para que muestre los datos del json)
    - La documentación se encuentra en /public/swagger.json
    - Se realizaron los endpoint de jugar torneo y obtener resultados con filtros

# Pruebas Unitarias e Integración

    El proyecto incluye pruebas unitarias y de integración, implementadas con PHPUnit. Las pruebas aseguran el correcto funcionamiento de las reglas de negocio

# Instrucciones para la Ejecución

El proyecto utiliza Docker para garantizar un entorno controlado y reproducible.

    Requisitos -> Docker - Docker Compose 

    Pasos para ejecutar el proyecto:
        Clonar el repositorio: https://github.com/maximilianomohamed/torneo-tenis.git
        Levantar los contenedores: 
        ``` docker-compose up -d```
        Acceder a la aplicación http://localhost:8000
    
# Ejemplos de uso

    - Crear torneo masculino 
        POST http://localhost:8080/api/torneos/hombres/jugar 
         
        ```
        {
            "nombreTorneo": "Torneo de Hombres",
            "jugadores": [
                {
                    "nombre": "Jugador 1",
                    "habilidad": 80,
                    "fuerza": 70,
                    "velocidad": 75
                },
                {
                    "nombre": "Jugador 2",
                    "habilidad": 85,
                    "fuerza": 65,
                    "velocidad": 80
                },
                {
                    "nombre": "Jugador 3",
                    "habilidad": 90,
                    "fuerza": 75,
                    "velocidad": 85
                },
                {
                    "nombre": "Jugador 4",
                    "habilidad": 70,
                    "fuerza": 80,
                    "velocidad": 65
                }
            ]
        }
        ```
    - Crear torneo femenino 

        POST http://localhost:8080/api/torneos/mujeres/jugar 
         
        ```
        {
            "nombreTorneo": "Torneo de Mujeres",
            "jugadores": [
                {
                    "nombre": "Jugadora 1",
                    "habilidad": 80,
                    "tiempoReaccion": 0.5
                },
                {
                    "nombre": "Jugadora 2",
                    "habilidad": 90,
                    "tiempoReaccion": 0.6
                },
                {
                    "nombre": "Jugadora 3",
                    "habilidad": 78,
                    "tiempoReaccion": 0.4
                },
                {
                    "nombre": "Jugadora 4",
                    "habilidad": 82,
                    "tiempoReaccion": 0.5
                }
            ]
        }
        ```
    - Resultados con filtros 

        GET http://localhost:8080/api/torneos/resultados?genero=Femenino&nombre=Torneo%20de%20Hombres 
