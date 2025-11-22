<?php
require_once 'config/configuracion.php';

class reseñamodel {
    protected $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_deploy();
        } catch (PDOException $e) {
        
        }
    }

    private function _deploy() {
        $query = $this->db->query('SHOW TABLES LIKE "reseñas"');
        $tables = $query->fetchAll();
        if(count($tables) == 0) {
            $sql = <<<END
                CREATE TABLE `reseñas` (
                  `id_reseña` int(11) NOT NULL AUTO_INCREMENT,
                  `comentario` text NOT NULL,
                  `puntuacion` tinyint(1) NOT NULL CHECK (puntuacion >= 1 AND puntuacion <= 5),
                  `id_libro_fk` int(11) NOT NULL,
                  PRIMARY KEY (`id_reseña`),
                  KEY `FK_id_libro` (`id_libro_fk`),
                  CONSTRAINT `FK_id_libro` FOREIGN KEY (`id_libro_fk`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            END;
            $this->db->query($sql);
        }
    }

    public function obtenerReseñas($sort = 'id_reseña', $order = 'ASC') {
        try {
            $columnasPermitidas = ['id_reseña', 'comentario', 'puntuacion', 'id_libro_fk', 'libro_titulo'];
            
            if (empty($sort) || !in_array($sort, $columnasPermitidas)) {
                $sort = 'id_reseña';
            }
            if (empty($order) || !in_array(strtoupper($order), ['ASC', 'DESC'])) {
                $order = 'ASC';
            }

            $sql = 'SELECT reseñas.*, libros.titulo AS libro_titulo 
                    FROM reseñas 
                    JOIN libros ON reseñas.id_libro_fk = libros.id
                    ORDER BY ' . $sort . ' ' . $order;
                    
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function obtenerReseñaPorId($id) {
        try {
            $query = $this->db->prepare(
                'SELECT reseñas.*, libros.titulo AS libro_titulo 
                 FROM reseñas 
                 JOIN libros ON reseñas.id_libro_fk = libros.id
                 WHERE reseñas.id_reseña = ?'
            );
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertarReseña($comentario, $puntuacion, $idLibro) {
        try {
            $query = $this->db->prepare(
                'INSERT INTO reseñas (comentario, puntuacion, id_libro_fk) 
                 VALUES (?, ?, ?)'
            );
            $query->execute([$comentario, $puntuacion, $idLibro]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarReseña($id, $comentario, $puntuacion, $idLibro) {
        try {
            $query = $this->db->prepare(
                'UPDATE reseñas 
                 SET comentario = ?, puntuacion = ?, id_libro_fk = ? 
                 WHERE id_reseña = ?'
            );
            $query->execute([$comentario, $puntuacion, $idLibro, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}