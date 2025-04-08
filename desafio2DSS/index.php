<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'classes/Database.php';
require_once 'classes/Project.php';

// Verificar autenticación
requireAuth();

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener proyectos del usuario
$project = new Project($db);
$projects = $project->getProjectsByUser($_SESSION['user_id']);

// Contar proyectos
$total_projects = $projects->rowCount();

// Obtener los 3 proyectos más recientes para mostrar
$recent_projects = $project->getProjectsByUser($_SESSION['user_id'], 3);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Sistema de Gestión de Proyectos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Barra de navegación -->
        <header class="dashboard-header">
            <h1>Bienvenido, <?php echo e($_SESSION['user_name']); ?></h1>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Inicio</a></li>
                    <li><a href="projects.php">Mis Proyectos</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <!-- Contenido principal -->
        <main class="dashboard-content">
            <section class="stats-section">
                <div class="stat-card">
                    <h3>Proyectos Totales</h3>
                    <p><?php echo $total_projects; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Acciones Rápidas</h3>
                    <ul>
                        <li><a href="projects.php?action=create">Crear Nuevo Proyecto</a></li>
                        <li><a href="projects.php">Ver Todos los Proyectos</a></li>
                    </ul>
                </div>
            </section>

            <section class="recent-projects">
                <h2>Proyectos Recientes</h2>
                <?php if ($recent_projects->rowCount() > 0): ?>
                    <div class="project-grid">
                        <?php while ($project = $recent_projects->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="project-card">
                                <h3><?php echo e($project['titulo']); ?></h3>
                                <p><?php echo e(substr($project['descripcion'], 0, 100)); ?>...</p>
                                <a href="project_detail.php?id=<?php echo $project['id']; ?>" class="btn">Ver Proyecto</a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No tienes proyectos aún. <a href="projects.php?action=create">Crea tu primer proyecto</a></p>
                <?php endif; ?>
            </section>
        </main>

        <!-- Pie de página -->
        <footer class="dashboard-footer">
            <p>Sistema de Gestión de Proyectos &copy; <?php echo date('Y'); ?></p>
        </footer>
    </div>
</body>
</html>