<?php
class File {
    private $conn;
    private $table = 'files';

    public $id;
    public $nombre_archivo;
    public $tipo_archivo;
    public $ruta;
    public $id_project;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function upload() {
        $query = "INSERT INTO " . $this->table . " 
                  SET nombre_archivo=:nombre_archivo, tipo_archivo=:tipo_archivo, ruta=:ruta, id_project=:id_project";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre_archivo", $this->nombre_archivo);
        $stmt->bindParam(":tipo_archivo", $this->tipo_archivo);
        $stmt->bindParam(":ruta", $this->ruta);
        $stmt->bindParam(":id_project", $this->id_project);

        return $stmt->execute();
    }

    public function getFilesByProject($projectId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_project = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $projectId);
        $stmt->execute();
        return $stmt;
    }

    public function delete() {
        // Primero obtenemos la información del archivo para borrarlo del servidor
        $query = "SELECT ruta FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if($file && file_exists($file['ruta'])) {
            unlink($file['ruta']);
        }

        // Luego borramos el registro de la base de datos
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>