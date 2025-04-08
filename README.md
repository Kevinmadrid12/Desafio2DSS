# Sistema de Gestión de Proyectos Colaborativos

Aplicación web para registrar empleados y gestionar proyectos colaborativos con documentos adjuntos.

## 📥 Instalación

### Requisitos previos
- Servidor web (XAMPP, WAMP, LAMP)
- PHP 7.4+ 
- MySQL 5.7+
- Composer (recomendado)

### Pasos de instalación
1. **Clonar repositorio**:
   En tu terminal ya sea PowerShell o CMD, ingrese el siguiente comando: 
    git clone https://github.com/Kevinmadrid12/Desafio2DSS.git
    cd Desafio2DSS


### Estructura de los archivos
/proyecto/
├── assets/               # Archivos estáticos
│   ├── css/              # Hojas de estilo
│   └── uploads/          # Documentos subidos por los usuarios
│
├── classes/              # Clases del sistema
│   ├── Database.php      # Conexión a BD
│   ├── User.php          # Gestión de usuarios
│   ├── Project.php       # Gestión de proyectos
│   └── File.php          # Manejo de archivos
│
├── includes/             # Includes principales
│   ├── config.php        # Configuración
│   ├── auth.php          # Autenticación
│   └── functions.php     # Funciones helpers
│
├── index.php             # Pagina principal
├── login.php             # Inicio de sesión
├── register.php          # Registro
├── projects.php          # Gestión de proyectos
└── project_detail.php    # Detalle de proyecto
