# AGENTS.md — Menú Cafetería AGORA

> Documento de contexto para IAs. Léeme antes de explorar el repo: te ahorra tokens y
> te da el mapa mental del proyecto completo en una sola lectura.

---

## 1. ¿Qué es?

Aplicación web de **menú digital para una cafetería** llamada **AGORA**. Tiene dos vistas principales:

1. **Menú público** (`/index.php` → vista-menu): cualquier visitante ve categorías y productos organizados de forma dinámica y responsiva.
2. **Panel de administración** (mismo `index.php` → vista-admin): CRUD de productos, requiere login y maneja búsquedas de imágenes web e inserciones/ediciones.

El sistema completo es PHP 8.2 + MySQL, estructurado sin frameworks en el backend (vanilla PHP con endpoints JSON en `api/`) y con frontend SPA renderizado desde `index.php` con JS vanilla y Tailwind CSS 4.

---

## 2. Stack

| Capa            | Tecnología                                              |
| --------------- | ------------------------------------------------------- |
| Backend         | PHP 8.2 (Apache, mod_rewrite habilitado)                |
| Base de datos   | MySQL 8 (extensiones `mysqli`, charset `utf8mb4`)       |
| Frontend        | HTML5 + JS vanilla + **Tailwind CSS 4** compilado        |
| Build CSS       | `@tailwindcss/cli` v4.3 (npm) → `dist/output.css`       |
| Despliegue      | Docker (PHP-Apache) en **Railway** (lee `$PORT`)        |
| Auth            | Sesiones PHP + `password_hash` / `password_verify`      |
| Buscador img    | Scrapeo de imágenes web vía DuckDuckGo Images (scraping manual de token VQD + `i.js`) |

---

## 3. Estructura de carpetas

```
agora_menu/
├── index.php               # Frontend completo (SPA): menú público, login, dashboard admin, modals
├── conexion.php            # Conexión MySQL. Soporta env vars estándar y específicas de Railway
├── hash_passwords.php      # Script de migración one-shot: hashea contraseñas planas a bcrypt
├── login_data.json         # Credenciales de respaldo / testing en desarrollo (admin/admin)
├── test_json.php           # Script temporal / test para verificar la entrada php://input
├── package.json            # Scripts npm de compilación para Tailwind v4
├── package-lock.json       # Control de versiones del CLI de Tailwind
├── src/
│   └── input.css           # CSS fuente Tailwind con @import "tailwindcss", @source y tema personalizado
├── dist/
│   └── output.css         # CSS compilado y minificado servido en producción y desarrollo
├── assets/
│   ├── productos/          # Imágenes físicas subidas o cacheadas, nombradas `{ID_PROD}.{ext}`
│   └── placeholders/       # Iconos vectoriales (.svg) por categoría (cocina, cafe, bebida, mostrador, barra, default)
├── api/                    # API Endpoints que retornan JSON con header utf-8
│   ├── menu.php            # GET menú público (categorías ordenadas + productos con Stock=1)
│   ├── categorias.php      # GET catálogo de categorías (id_catego, categoria, f_preparacion)
│   ├── productos_admin.php # GET productos completo (requiere admin session, expone imagen real y resuelta)
│   ├── guardar_producto.php# POST agregar/editar producto (requiere admin, valida duplicados de ID y guarda url)
│   ├── eliminar_producto.php# POST eliminar producto (requiere admin, limpia tablas hijas primero)
│   ├── login.php           # POST login credentials / GET estado de sesión actual
│   ├── logout.php          # GET/POST para cerrar sesión y limpiar cookies/sesión PHP
│   └── buscar_imagen.php   # GET ?q=... búsqueda de imágenes en DuckDuckGo (requiere admin)
├── lib/
│   └── imagen_producto.php # Helper backend: resuelve la prioridad y validez de la imagen de un producto
├── migrations/             # Migraciones SQL de base de datos
├── docker/
│   ├── 000-default.conf    # Configuración de VirtualHost Apache para Docker
│   └── entrypoint.sh       # Script de inicio: fuerza mpm_prefork y reescribe puerto con $PORT dinámico
├── docs/
│   ├── imagenes-productos.md # Manual sobre cómo asignar y preparar imágenes
│   └── menu-template.html    # Referencia histórica de diseño estático
├── Dockerfile              # Build multi-stage: compilación Tailwind + runtime PHP-Apache
└── .gitignore              # Excluye node_modules, logs, base de datos local y el config de conexión específico
```

---

## 4. Base de datos (Esquema y Conexiones)

**Base de datos:** `comercial` (charset default `utf8mb4`).
El archivo [conexion.php](file:///c:/xampp/htdocs/agora_menu/conexion.php) intenta conectarse usando variables de entorno con fallback al entorno local de XAMPP (`localhost`, usuario `root`, sin contraseña).

### Matriz de variables de entorno soportadas

| Configuración | Variable Estándar | Variable Railway | Valor por Defecto Local |
| ------------- | ----------------- | ---------------- | ----------------------- |
| Host          | `DB_HOST`         | `MYSQLHOST`      | `'localhost'`           |
| Puerto        | `DB_PORT`         | `MYSQLPORT`      | `3306`                  |
| Usuario       | `DB_USER`         | `MYSQLUSER`      | `'root'`                |
| Contraseña    | `DB_PASSWORD`     | `MYSQLPASSWORD`  | `''`                    |
| Base de Datos | `DB_NAME`         | `MYSQLDATABASE`  | `'comercial'`           |

### DDL / Esquema SQL Sugerido para Inicialización
Dado que el repositorio no incluye un dump SQL estructurado, se proporciona este DDL completo para recrear el entorno local en MySQL/XAMPP:

```sql
CREATE DATABASE IF NOT EXISTS comercial CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE comercial;

-- 1. Tabla de categorías
CREATE TABLE IF NOT EXISTS `cat_productos` (
  `id_catego` INT AUTO_INCREMENT PRIMARY KEY,
  `categoria` VARCHAR(100) NOT NULL UNIQUE,
  `f_preparacion` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de productos
CREATE TABLE IF NOT EXISTS `productos` (
  `ID_PROD` INT PRIMARY KEY,
  `DESCRIP` VARCHAR(255) NOT NULL,
  `COSTO` DECIMAL(10,2) NOT NULL,
  `Stock` TINYINT(1) DEFAULT 1,
  `CATEGO` VARCHAR(100) NOT NULL,
  `imagen_url` VARCHAR(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla de usuarios administradores
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(100) NOT NULL UNIQUE,
  `contraseña` VARCHAR(255) NOT NULL,
  `Rol` VARCHAR(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabla de histórico de ventas (con integridad referencial)
CREATE TABLE IF NOT EXISTS `venta` (
  `id_venta` INT AUTO_INCREMENT PRIMARY KEY,
  `ID_PRODUCTO` INT NOT NULL,
  `cantidad` INT DEFAULT 1,
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_venta_producto` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PROD`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserción de datos iniciales
INSERT IGNORE INTO `cat_productos` (`id_catego`, `categoria`, `f_preparacion`) VALUES
(1, 'Cocina', 15),
(2, 'Cafe', 5),
(3, 'Bebida', 2),
(4, 'Mostrador', 0),
(5, 'Barra', 5);

-- Inserción de usuario administrador por defecto (usuario: admin, contraseña: hash de admin)
-- Generado con password_hash('admin', PASSWORD_DEFAULT)
INSERT IGNORE INTO `usuarios` (`usuario`, `contraseña`, `Rol`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

---

## 5. Mapeo de Endpoints de la API (`api/`)

Todos los endpoints que modifican datos o consultan información interna requieren de una sesión PHP activa verificada en la parte superior del script.

| Endpoint | Método | Requiere Auth | Payload Entrada | Formato de Salida | Propósito |
| -------- | ------ | ------------- | --------------- | ----------------- | --------- |
| `categorias.php` | `GET` | No | Ninguno | `[ { id_catego, categoria, f_preparacion }, ... ]` | Retorna catálogo de categorías ordenado. |
| `menu.php` | `GET` | No | Ninguno | `{ categorias: [ { nombre, productos: [...] } ], placeholderDefault }` | Retorna el menú público filtrado por `Stock=1` con imágenes resueltas. |
| `login.php` | `GET` | No | Ninguno | `{ logged_in: boolean, user?, role? }` | Verifica el estado de la sesión actual de PHP. |
| `login.php` | `POST` | No | `{ usuario, contraseña }` (JSON) | `{ success: bool, user?, role?, error? }` | Autentica un usuario. Setea variables de sesión `$_SESSION['admin_logged_in']`... |
| `logout.php` | `POST`/`GET`| No | Ninguno | `{ success: true }` | Destruye la sesión de PHP y limpia las cookies correspondientes. |
| `productos_admin.php` | `GET` | **Sí** | Ninguno | `[ { ID_PROD, DESCRIP, COSTO, Stock, CATEGO, imagen_url_raw, imagen_resuelta }, ... ]` | Obtiene el catálogo completo de productos incluyendo los agotados. |
| `guardar_producto.php`| `POST` | **Sí** | `{ action: 'add'/'edit', id_prod, descrip, costo, stock, catego, imagen_url }` | `{ success: bool, message?, error? }` | Registra un nuevo producto o edita uno existente. Valida duplicados de IDs en inserción. |
| `eliminar_producto.php`| `POST` | **Sí** | `{ id_prod }` (JSON) | `{ success: bool, message?, error? }` | Elimina un producto. Primero limpia registros en la tabla hija `venta` para evitar FK error. |
| `buscar_imagen.php` | `GET` | **Sí** | Query string `?q=...` | `{ success: bool, images: [ { image, thumbnail, title } ] }` | Realiza web scraping en DuckDuckGo Images para obtener miniaturas de productos. |

---

## 6. Frontend SPA & Ciclo de Vida (`index.php`)

El frontend está programado bajo el patrón SPA (Single Page Application) sin cargadores de módulos externos o frameworks JS.

### Estados y Variables Globales en JS
- `menuData`: Almacena el árbol completo devuelto por `api/menu.php` en la carga inicial.
- `categoriaActiva`: Nombre de la categoría que se visualiza actualmente en la vista pública.
- `adminProductos`: Listado local de productos cargados para el panel de administración.
- `modalMode`: Define la acción a tomar por el modal de edición (`'add'` para registrar o `'edit'` para actualizar).

### Sistema de Navegación Reactivo
La UI se conmuta mediante la función `mostrarVista(vista)`, que remueve o añade clases `hidden` en tres contenedores primarios:
- `vista-menu` (Menú público)
- `vista-login` (Acceso administrativo)
- `vista-admin` (Panel CRUD)

### Barra de Navegación Lateral (Sidebar)
Se compone de un botón collapse (`collapse-btn`) que conmuta el ancho de la barra entre `w-64` y `w-16`, rota la flecha indicadora 180° y oculta selectivamente los textos marcados con la clase `.sidebar-text` agregando `hidden`.

### Manejo de Fallbacks de Imágenes
El frontend y backend trabajan en conjunto para garantizar que nunca se rompa el diseño visual por una imagen inexistente:
1. `resolverImagenProducto()` en el backend valida si las rutas de los archivos físicos locales existen o si se trata de URLs externas correctas.
2. Si falla lo anterior, devuelve el placeholder de categoría (ej. `assets/placeholders/cafe.svg`).
3. Si la URL externa cargada de forma dinámica en el elemento `<img>` falla en tiempo de renderizado, el evento `onerror` en el JS de `index.php` captura el fallo y reemplaza inmediatamente el `src` con el placeholder default del sistema (`assets/placeholders/default.svg`).

---

## 7. Convenciones de Código y Estilos

### Tailwind CSS v4
- **Scraping de clases**: `@source "../index.php";` en [input.css](file:///c:/xampp/htdocs/agora_menu/src/input.css) permite escanear las clases utilitarias insertadas dinámicamente por JavaScript.
- **Paleta de Colores Custom**: Definida bajo la directiva `@theme` en `src/input.css` usando tonalidades de marrón café con nombre `agora` (`--color-agora-50` hasta `--color-agora-900`). Toda la UI utiliza estas tonalidades para garantizar una estética premium.

### Reglas de Diseño Backend (API)
- **UTF-8**: Todas las respuestas PHP inyectan cabeceras `Content-Type: application/json; charset=utf-8` y utilizan el flag `JSON_UNESCAPED_UNICODE` en `json_encode` para evitar problemas con acentos o caracteres como la `ñ`.
- **Seguridad**: Todas las sentencias dinámicas usan sentencias preparadas de MySQLi (`prepare()` y `bind_param()`) para resguardo contra ataques de inyección SQL.

---

## 8. Build y Ejecución

### Local (XAMPP / Servidores locales)
1. Clonar el repositorio dentro de `C:\xampp\htdocs\agora_menu`.
2. Importar el DDL provisto en la **Sección 4** en tu gestor de base de datos MySQL.
3. Si requieres inicializar el build de Tailwind v4:
   ```bash
   npm install
   npm run watch-css     # desarrollo
   npm run build-css     # producción
   ```
4. Visitar `http://localhost/agora_menu/` en el navegador.

### Docker y Railway (Producción)
- **Dockerfile**: Compila Tailwind v4 en una imagen temporal de NodeJS y la copia junto a todo el código en una imagen base de `php:8.2-apache`.
  - *Advertencia*: El build de NodeJS escribe a `public/css/tailwind.css` en lugar de `dist/output.css`. Esto se subsana temporalmente en producción dado que `dist/output.css` se incluye al copiar todo el directorio de la aplicación, pero de modificarse la ruta del CSS en el index, se debe unificar el destino en el Dockerfile.
- **entrypoint.sh**: Configura los archivos de puerto de Apache y los Hosts Virtuales dinámicamente a través de la variable `$PORT` inyectada por Railway, forzando la carga de `mpm_prefork` para estabilidad del backend de PHP.

---

## 9. Problemas conocidos y Mantenimiento

- **Truncamiento de Contraseñas (VARCHAR)**: Si se redefine la tabla `usuarios` en el futuro, el campo `contraseña` debe mantenerse en `VARCHAR(255)`. La longitud de 50 caracteres truncará silenciosamente las cadenas bcrypt resultantes de `password_hash`, haciendo imposible la autenticación exitosa.
- **DuckDuckGo CSS/Scraper break**: DuckDuckGo cambia con relativa frecuencia la estructura de su DOM interno. Si el regex de extracción del token `vqd` en `buscar_imagen.php` falla, el buscador web de imágenes del panel retornará un error `502`.
- **Integridad de Ventas en Borrado**: Debido a las foreign keys, no se puede borrar un producto si tiene registros de venta asociados. [eliminar_producto.php](file:///c:/xampp/htdocs/agora_menu/api/eliminar_producto.php) limpia preventivamente la tabla `venta` para evitar excepciones SQL.

---

## 10. Resumen ultra-corto

> PHP 8.2 + MySQL + Tailwind 4 (compilado en `dist/output.css`). SPA de un solo archivo (`index.php`) que conmuta entre menú público y panel administrativo. Consumo de endpoints JSON securizados en `api/`. Búsquedas automáticas de imágenes web en DuckDuckGo. Despliegue en Railway mediante un contenedor Docker optimizado que expone dinámicamente el puerto HTTP.
