<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'classes/Database.php';
require_once 'classes/Project.php';

$database = new Database();
$db = $database->getConnection();

$project = new Project($db);
$projects = $project->getProjectsByUser($_SESSION['user_id']);

// Crear nuevo proyecto
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_project'])) {
    $project->titulo = $_POST['titulo'];
    $project->descripcion = $_POST['descripcion'];
    $project->id_user = $_SESSION['user_id'];
    
    if($project->create()) {
        header("Location: projects.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mis Proyectos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
        
        <!-- Barra de navegación -->
        <div class="navigation">
            <a href="index.php" class="btn">Inicio</a>
            <a href="logout.php" class="btn">Cerrar Sesión</a>
        </div>
        
        <h3>Mis Proyectos</h3>
        
        <!-- Formulario para crear proyecto -->
        <div class="project-form">
            <h4>Crear Nuevo Proyecto</h4>
            <form method="post">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" name="create_project" value="Crear Proyecto" class="btn">
                </div>
            </form>
        </div>
        
        <!-- Lista de proyectos -->
        <div class="project-list">
            <?php while($row = $projects->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="project-item">
                    <h4><?php echo htmlspecialchars($row['titulo']); ?></h4>
                    <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                    <a href="project_detail.php?id=<?php echo $row['id']; ?>" class="btn">Ver Detalles</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>