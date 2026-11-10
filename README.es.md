# Backend Laravel - API REST con JWT, Imágenes e IA

API RESTful construida con **Laravel 13**, **PHP 8.3** y **MySQL/MariaDB**.  
Incluye autenticación con **JWT**, subida de imágenes de avatar y posters, búsqueda de artículos y generación de artículos con **Inteligencia Artificial (OpenAI GPT-4o-mini)**.

---

## 🚀 Características

- Autenticación de usuarios (registro y login con JWT)
- Perfil de usuario y actualización de datos
- Subida y visualización de avatar de usuario
- CRUD completo de artículos (crear, listar, detalle, buscar, actualizar, eliminar)
- Subida de imágenes (posters) para artículos
- Búsqueda de artículos por título o contenido
- Generación automática de artículos con IA (OpenAI)
- Rutas públicas y protegidas por middleware JWT
- Respuestas JSON estructuradas
- Base de datos relacional con migraciones

---

## 🛠️ Stack Tecnológico

| Tecnología | Versión | Uso |
|------------|---------|-----|
| Laravel | 13.x | Framework PHP |
| PHP | 8.3 | Lenguaje de backend |
| MySQL / MariaDB | 10.11 | Base de datos |
| firebase/php-jwt | 7.x | Generación y verificación de JWT |
| openai-php/client | 0.x | Integración con OpenAI |
| Laravel Storage | - | Gestión de archivos (avatars, posters) |

---

## 📋 Requisitos Previos

- PHP 8.3 o superior
- Composer
- MySQL o MariaDB (puerto 3306)
- Servidor web (Apache/Nginx) o `php artisan serve`
- Extensiones PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`

---

## 📦 Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/dannizj/backend-laravel.git
cd backend-laravel

# 2. Instalar dependencias de PHP
composer install

# 3. Copiar el archivo de entorno
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Configurar la base de datos en .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=backend_laravel
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Ejecutar las migraciones
php artisan migrate

# 7. Crear el enlace simbólico para almacenamiento público
php artisan storage:link

# 8. Levantar el servidor de desarrollo
php artisan serve
```

La API estará disponible en: `http://127.0.0.1:8000/api`

---

## ⚙️ Variables de Entorno (.env)

Crea tu archivo `.env` basado en `.env.example` y configura:

```env
APP_NAME=BackendLaravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_laravel
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local

# Clave secreta para firmar los tokens JWT
JWT_SECRET=tu_clave_secreta_aqui

# Clave de OpenAI para generación de artículos con IA
OPENAI_API_KEY=sk-tu_clave_de_openai
```

> ⚠️ Nunca subas tu archivo `.env` al repositorio. Está incluido en `.gitignore`.

---

## 🔗 Endpoints de la API

### Usuarios (Públicos)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/user/register` | Registrar nuevo usuario |
| `POST` | `/api/user/login` | Iniciar sesión y obtener token JWT |
| `GET` | `/api/user/profile/{id}` | Obtener perfil de un usuario por ID |
| `GET` | `/api/user/avatar/{file}` | Obtener imagen de avatar |

### Usuarios (Protegidas - requieren JWT)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `PUT` | `/api/user/update` | Actualizar datos del usuario autenticado |
| `POST` | `/api/user/upload` | Subir imagen de avatar |

### Artículos (Públicos)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/article/items/{page}` | Listar artículos paginados |
| `GET` | `/api/article/item/{id}` | Obtener un artículo por ID |
| `GET` | `/api/article/user/{user_id}` | Artículos de un usuario específico |
| `GET` | `/api/article/search/{searchString}` | Buscar artículos por título o contenido |
| `GET` | `/api/article/poster/{file}` | Obtener imagen de poster de un artículo |

### Artículos (Protegidas - requieren JWT)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/article/save` | Crear un nuevo artículo |
| `POST` | `/api/article/upload/{id}` | Subir imagen de poster a un artículo |
| `PUT` | `/api/article/update/{id}` | Actualizar un artículo (solo el autor) |
| `DELETE` | `/api/article/delete/{id}` | Eliminar un artículo (solo el autor) |
| `POST` | `/api/article/generate/{theme}` | Generar artículo con IA según tema |

---

## 🔐 Autenticación JWT

La API utiliza **JSON Web Tokens (JWT)** para autenticar las peticiones.

1. Inicia sesión en `POST /api/user/login` con email y contraseña.
2. Recibirás un token JWT en la respuesta.
3. Para acceder a rutas protegidas, envía el token en el header:

```
Authorization: Bearer TU_TOKEN_AQUI
```

---

## 🖼️ Subida de Archivos

Se utilizan dos discos de almacenamiento configurados en `config/filesystems.php`:

- **avatars**: `storage/app/avatars/` — Imágenes de perfil de usuario
- **posters**: `storage/app/posters/` — Imágenes de portada de artículos

Estas carpetas están incluidas en `.gitignore` y no se suben al repositorio.

---

## 🤖 Generación de Artículos con IA

El endpoint `POST /api/article/generate/{theme}` utiliza **OpenAI GPT-4o-mini** para crear automáticamente el título y contenido de un artículo basado en un tema. El artículo generado se valida y se guarda asociado al usuario autenticado.

Requiere configurar `OPENAI_API_KEY` en tu archivo `.env`.

---

## 📁 Estructura del Proyecto

```
backend-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ArticleController.php
│   │   │   └── UserController.php
│   │   ├── Middleware/
│   │   │   └── JwtMiddleware.php
│   │   └── ...
│   ├── Helpers/
│   │   └── JwtAuth.php
│   ├── Models/
│   │   ├── Article.php
│   │   └── User.php
│   └── ...
├── config/
│   └── filesystems.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── public/
├── storage/
│   └── app/
│       ├── avatars/      (ignorado en git)
│       └── posters/      (ignorado en git)
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## 🧪 Pruebas

El proyecto incluye configuración para pruebas con Pest/PHPUnit:

```bash
php artisan test
```

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Consulta el archivo `LICENSE` para más información.

---

## 👨‍💻 Autor

**Danni ZJ**  
Backend Developer — PHP, Laravel, SQL, APIs REST

- GitHub: [@dannizj](https://github.com/dannizj)
- Repositorio: [backend-laravel](https://github.com/dannizj/backend-laravel)

---

## 📌 Historial de Versiones

| Versión | Descripción |
|---------|-------------|
| v1.0.0 | Generación de artículos con IA y versión final para portafolio |
| v0.8.0 | CRUD completo de artículos con posters |
| v0.7.0 | Endpoints de avatar (subida y visualización) |
| v0.6.0 | Perfil de usuario por ID |
| v0.5.0 | Actualización de usuario protegida por JWT |
| v0.4.0 | Middleware JWT para rutas protegidas |
| v0.3.0 | Login con generación de token JWT |
| v0.2.1 | Limpieza de esquema SQL legado |
| v0.2.0 | Registro de usuarios con validación y hash de contraseña |
| v0.1.0 | Conexión a MySQL y rutas de API |
| v0.0.0 | Esqueleto inicial de Laravel 13 |
