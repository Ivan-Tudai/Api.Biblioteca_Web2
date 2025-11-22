API de Reseñas de Libros
Esta es la documentación para usar la API de reseñas. La base de datos es la misma que la del trabajo anterior.

Endpoints
GET
http://localhost/TPE_API_Biblioteca/reseñas

Obtiene todas las reseñas cargadas.

Para ordenar: Se pueden pasar parámetros opcionales para ordenar la lista.

Ejemplo: http://localhost/TPE_API_Biblioteca/reseñas?sort=puntuacion&order=DESC

Parámetros:

sort: Campo por el cual ordenar (ej: puntuacion, comentario, id_reseña).

order: ASC (ascendente) o DESC (descendente).

http://localhost/TPE_API_Biblioteca/reseñas/:id

Ejemplo: http://localhost/TPE_API_Biblioteca/reseñas/10

Obtiene una reseña específica por su ID. Si no existe, devuelve error 404.

POST
http://localhost/TPE_API_Biblioteca/reseñas

Agrega una nueva reseña. Se debe enviar el JSON en el body del request.

Body (JSON):

JSON

{
    "comentario": "Muy buen libro, recomendado.",
    "puntuacion": 5,
    "id_libro_fk": 2
}
Requisitos:

comentario: Texto de la opinión.

puntuacion: Número del 1 al 5.

id_libro_fk: El ID de un libro que ya exista en la base de datos.

Si falta algún dato, devuelve error 400.

PUT
http://localhost/TPE_API_Biblioteca/reseñas/:id

Ejemplo URL: http://localhost/TPE_API_Biblioteca/reseñas/10

Modifica una reseña existente.

Body (JSON):

JSON

{
    "comentario": "Cambio de opinión, no me gustó tanto.",
    "puntuacion": 3,
    "id_libro_fk": 2
}
Se deben enviar todos los campos para actualizar.

Si falta algún dato, devuelve error 400.

Si el ID de la reseña no existe, devuelve error 404.
