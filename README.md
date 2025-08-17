# Sistema Online de Administración de Restaurante

Un sistema completo de administración para restaurantes desarrollado en PHP puro con arquitectura MVC, diseñado para gestionar todos los aspectos operativos de un restaurante.

## 🚀 Características Principales

### Gestión de Usuarios y Roles
- **Administrador**: Acceso total al sistema
- **Gerente**: Control de inventario, reportes y personal
- **Mesero**: Toma de pedidos, seguimiento de mesas
- **Cajero**: Cobros, facturación y cierre de caja
- **Chef/Cocina**: Recepción de pedidos y actualización de estatus

### Módulos del Sistema
- ✅ **Dashboard** con estadísticas en tiempo real
- ✅ **Gestión de Mesas** con layout visual del salón
- ✅ **Toma de Pedidos** con sincronización en tiempo real
- ✅ **Menú Digital** con categorías y disponibilidad
- ✅ **Inventario** con alertas de stock bajo
- ✅ **Facturación y Pagos** con múltiples métodos
- ✅ **Reportes** de ventas e inventario
- ✅ **Reservaciones** con confirmación automática
- ✅ **Sistema de Usuarios** con control de permisos

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7+ (puro, sin framework)
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **Gráficas**: Chart.js
- **Autenticación**: Sessions con password_hash()
- **Arquitectura**: MVC (Model-View-Controller)

## 📋 Requisitos del Sistema

- **Servidor Web**: Apache 2.4+
- **PHP**: 7.4+ con extensiones:
  - PDO
  - PDO_MySQL
  - Session
  - JSON
  - OpenSSL
- **MySQL**: 5.7+ o MariaDB 10.2+
- **Módulos Apache**:
  - mod_rewrite
  - mod_headers (opcional)

## 🔧 Instalación

### 1. Descargar y Configurar Archivos

```bash
# Clonar o descargar el repositorio
git clone https://github.com/danjohn007/restaurante.git

# Mover los archivos al directorio del servidor web
sudo cp -r restaurante/* /var/www/html/restaurante/
```

### 2. Configurar la Base de Datos

```bash
# Conectar a MySQL
mysql -u root -p

# Crear la base de datos
mysql> SOURCE /var/www/html/restaurante/database/schema.sql;
```

### 3. Configurar la Aplicación

Editar el archivo `config/config.php`:

```php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'restaurante_db');
define('DB_USER', 'tu_usuario_mysql');
define('DB_PASS', 'tu_contraseña_mysql');

// URL base (cambiar según tu instalación)
define('BASE_URL', '/restaurante/'); // Para subdirectorio
// define('BASE_URL', '/'); // Para dominio raíz
```

### 4. Configurar Permisos

```bash
# Dar permisos de escritura a directorios necesarios
sudo chown -R www-data:www-data /var/www/html/restaurante/
sudo chmod -R 755 /var/www/html/restaurante/
sudo chmod -R 777 /var/www/html/restaurante/assets/images/uploads/
```

### 5. Configurar Apache

El archivo `.htaccess` ya está incluido. Asegúrate de que mod_rewrite esté habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 6. Configurar Virtual Host (Opcional)

Para usar un dominio específico, crear un virtual host:

```apache
<VirtualHost *:80>
    ServerName restaurante.local
    DocumentRoot /var/www/html/restaurante
    
    <Directory /var/www/html/restaurante>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/restaurante_error.log
    CustomLog ${APACHE_LOG_DIR}/restaurante_access.log combined
</VirtualHost>
```

## 🎯 Acceso Inicial

### Credenciales por Defecto

El sistema incluye usuarios de ejemplo con contraseña `password123`:

| Email | Rol | Contraseña |
|-------|-----|------------|
| admin@restaurante.com | Administrador | password123 |
| manager@restaurante.com | Gerente | password123 |
| mesero@restaurante.com | Mesero | password123 |
| cajero@restaurante.com | Cajero | password123 |
| chef@restaurante.com | Chef | password123 |

### Primer Acceso

1. Navegar a: `http://localhost/restaurante/` o `http://tu-dominio.com/`
2. Usar las credenciales de administrador
3. Cambiar contraseñas por defecto desde el panel de usuarios
4. Configurar datos básicos del restaurante

## 📁 Estructura del Proyecto

```
restaurante/
├── config/              # Configuración del sistema
│   ├── config.php       # Configuración general
│   └── database.php     # Conexión a base de datos
├── controllers/         # Controladores MVC
│   ├── BaseController.php
│   ├── AuthController.php
│   ├── DashboardController.php
│   └── ...
├── models/             # Modelos de datos
├── views/              # Vistas del sistema
│   ├── layouts/        # Plantillas base
│   ├── auth/           # Vistas de autenticación
│   ├── dashboard/      # Panel principal
│   └── ...
├── assets/             # Recursos estáticos
│   ├── css/            # Estilos CSS
│   ├── js/             # JavaScript
│   └── images/         # Imágenes
├── includes/           # Archivos de funciones
├── database/           # Esquema de base de datos
├── .htaccess          # Configuración Apache
├── index.php          # Punto de entrada
└── README.md          # Este archivo
```

## 🔒 Seguridad

El sistema incluye las siguientes medidas de seguridad:

- Autenticación basada en sesiones
- Contraseñas hasheadas con password_hash()
- Protección CSRF
- Validación y sanitización de datos
- Control de acceso basado en roles
- Protección contra SQL injection (PDO)
- Headers de seguridad en .htaccess

## 🔄 Funcionalidades por Rol

### Administrador
- Acceso completo al sistema
- Gestión de usuarios y roles
- Configuración del sistema
- Todos los reportes

### Gerente
- Control de inventario
- Reportes de ventas
- Gestión de menú
- Supervisión de operaciones

### Mesero
- Toma de pedidos
- Gestión de mesas
- Visualización de órdenes
- Reservaciones

### Cajero
- Procesamiento de pagos
- Cierre de órdenes
- Reportes de caja
- Facturación

### Chef
- Visualización de órdenes de cocina
- Actualización de estados de platos
- Gestión de tiempos de preparación

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos
1. Verificar credenciales en `config/config.php`
2. Asegurar que MySQL esté corriendo
3. Verificar que la base de datos exista

### URLs no Funcionan (404)
1. Verificar que mod_rewrite esté habilitado
2. Comprobar permisos del archivo .htaccess
3. Verificar BASE_URL en config.php

### Problemas de Permisos
```bash
sudo chown -R www-data:www-data /var/www/html/restaurante/
sudo chmod -R 755 /var/www/html/restaurante/
```

## 📞 Soporte

Para reportar bugs o solicitar características:
1. Crear un issue en GitHub
2. Incluir detalles del error
3. Especificar versión de PHP y MySQL

## 📄 Licencia

Este proyecto es open source y está disponible bajo la licencia MIT.

## 🚀 Próximas Funcionalidades

- [ ] Módulo de delivery y pedidos online
- [ ] Integración con APIs de reparto
- [ ] Sistema de fidelización de clientes
- [ ] Notificaciones por SMS/WhatsApp
- [ ] App móvil
- [ ] Integración con sistemas de punto de venta
- [ ] Multisucursal
- [ ] API REST completa

---

**Desarrollado con ❤️ para la industria restaurantera**
