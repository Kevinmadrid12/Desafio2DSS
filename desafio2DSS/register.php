<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->nombre = trim($_POST['nombre']);
    $user->email = trim($_POST['email']);
    $user->password = trim($_POST['password']);
    
    if($user->register()) {
        $_SESSION['success_msg'] = "Registro exitoso. Por favor inicie sesión.";
        header("Location: login.php");
    } else {
        $register_err = "Error al registrar. Intente nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <?php if(!empty($register_err)) echo "<div class='alert'>$register_err</div>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrarse">
            </div>
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </form>
    </div>
</body>
</html>