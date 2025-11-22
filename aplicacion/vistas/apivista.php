<?php

class apivista {
    public function respuesta($datos, $codigo) {
        header("Content-Type: application/json");
        http_response_code($codigo);
        echo json_encode($datos);
    }
}