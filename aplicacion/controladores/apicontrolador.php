<?php
require_once 'aplicacion/modelos/reseñamodel.php';
require_once 'aplicacion/vistas/apivista.php';

class apicontrolador {
    private $modelo;
    private $vista;
    private $data;

    public function __construct() {
        $this->modelo = new reseñamodel();
        $this->vista = new apivista();
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function manejarReseñas($params) {
        $method = $_SERVER['REQUEST_METHOD'];
        $id = isset($params[1]) && is_numeric($params[1]) ? $params[1] : null;

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getReseña($id);
                } else {
                    $this->getReseñas();
                }
                break;
            case 'POST':
                $this->addReseña();
                break;
            case 'PUT':
                if ($id) {
                    $this->updateReseña($id);
                } else {
                    $this->vista->respuesta("Debe especificar un ID para modificar.", 400);
                }
                break;
            default:
                $this->vista->respuesta("Método no permitido", 405);
                break;
        }
    }

    public function getReseña($id) {
        $reseña = $this->modelo->obtenerReseñaPorId($id);

        if ($reseña) {
            return $this->vista->respuesta($reseña, 200);
        } else {
            return $this->vista->respuesta("La reseña con el ID $id no existe.", 404);
        }
    }

    public function getReseñas() {
        $sort = $_GET['sort'] ?? 'id_reseña';
        $order = $_GET['order'] ?? 'ASC';

        $reseñas = $this->modelo->obtenerReseñas($sort, $order);
        return $this->vista->respuesta($reseñas, 200);
    }

    public function addReseña() {
        $datos = $this->getData();

        if (empty($datos->comentario) || empty($datos->puntuacion) || empty($datos->id_libro_fk)) {
            return $this->vista->respuesta("Datos incompletos. Se requiere comentario, puntuacion e id_libro_fk.", 400);
        }

        $id = $this->modelo->insertarReseña($datos->comentario, $datos->puntuacion, $datos->id_libro_fk);
        
        if ($id) {
            $nuevaReseña = $this->modelo->obtenerReseñaPorId($id);
            return $this->vista->respuesta($nuevaReseña, 201);
        } else {
            return $this->vista->respuesta("Error al insertar la reseña.", 500);
        }
    }

    public function updateReseña($id) {
        $reseñaExiste = $this->modelo->obtenerReseñaPorId($id);
        if (!$reseñaExiste) {
            return $this->vista->respuesta("La reseña con el ID $id no existe.", 404);
        }

        $datos = $this->getData();

        if (empty($datos->comentario) || empty($datos->puntuacion) || empty($datos->id_libro_fk)) {
            return $this->vista->respuesta("Datos incompletos.", 400);
        }

        $this->modelo->actualizarReseña($id, $datos->comentario, $datos->puntuacion, $datos->id_libro_fk);
        
        $reseñaActualizada = $this->modelo->obtenerReseñaPorId($id);
        return $this->vista->respuesta($reseñaActualizada, 200);
    }
}