<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'classes/Database.php';
require_once 'classes/Project.php';
require_once 'classes/File.php';

$database = new Database();
$db = $database->getConnection();

$project = new Project($db);
$file = new File($db);

$project_id = $_GET['id'] ?? 0;
$project_data = $project->getProjectById($project_id);

// Verificar que el proyecto pertenece al usuario
if(!$project_data || $project_data['id_user'] != $_SESSION['user_id']) {
    header("Location: projects.php");
    exit;
}

// Subir archivo
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo'])) {
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
    
    if(in_array($_FILES['archivo']['type'], $allowed_types)) {
        $upload_dir = 'assets/uploads/';
        $file_name = basename($_FILES['archivo']['name']);
        $file_path = $upload_dir . time() . '_' . $file_name;
        
        if(move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path)) {
            $file->nombre_archivo = $file_name;
            $file->tipo_archivo = strpos($_FILES['archivo']['type'], 'image') !== false ? 'img' : 'pdf';
            $file->ruta = $file_path;
            $file->id_project = $project_id;
            
            $file->upload();
        }
    }
}

// Eliminar archivo
if(isset($_GET['delete_file'])) {
    $file->id = $_GET['delete_file'];
    $file->delete();
    header("Location: project_detail.php?id=$project_id");
}

$files = $file->getFilesByProject($project_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($project_data['titulo']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($project_data['titulo']); ?></h2>
        <p><?php echo htmlspecialchars($project_data['descripcion']); ?></p>
        <a href="projects.php">Volver a proyectos</a>
        
        <h3>Documentos del Proyecto</h3>
        
        <!-- Formulario para subir archivos -->
        <div class="upload-form">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Subir documento (PDF o imagen):</label>
                    <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Subir Archivo">
                </div>
            </form>
        </div>
        
        <!-- Lista de archivos -->
        <div class="file-list">
            <?php while($file_row = $files->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="file-item">
                    <h4><?php echo htmlspecialchars($file_row['nombre_archivo']); ?></h4>
                    <p>Tipo: <?php echo strtoupper($file_row['tipo_archivo']); ?></p>
                    <a href="<?php echo $file_row['ruta']; ?>" target="_blank">Ver</a>
                    <a href="project_detail.php?id=<?php echo $project_id; ?>&delete_file=<?php echo $file_row['id']; ?>" 
                       onclick="return confirm('¿Estás seguro de eliminar este archivo?')">Eliminar</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>