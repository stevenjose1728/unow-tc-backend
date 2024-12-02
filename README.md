# Backend Symfony - Sistema de Gestión de Empleados

API REST desarrollada en Symfony 6.4 para la gestión de empleados y JWT.

## Tecnologías Utilizadas

- Symfony 6.4
- PHP 8.1+
- MySQL 8.0
- JWT Authentication
- Doctrine ORM

## Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL
- Extensiones PHP: json, mbstring, xml, mysql

## Instalación

1. Clona el repositorio:

```bash
git clone <repositorio>
cd backend
```

2. Instala dependencias:

```bash
composer install
```

3. Configura el .env:

```env
DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_name
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
EMAIL_SERVICE_URL=http://localhost:5000
```

4. Configura la base de datos:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Genera las claves JWT:

```bash
php bin/console lexik:jwt:generate-keypair
```

6. Inicia el servidor:

```bash
symfony server:start
```

## Endpoints API

### Autenticación

- `POST /api/login` - Inicio de sesión

### Usuarios

- `GET /api/users` - Lista de usuarios (paginada)
- `POST /api/users` - Crear usuario
- `PUT /api/users/{id}` - Actualizar usuario
- `DELETE /api/users/{id}` - Eliminar usuario
- `GET /api/users/me` - Perfil de usuario actual

## Estructura del Proyecto

```
src/
  ├── Controller/     # Controladores
  ├── Entity/         # Entidades Doctrine
  ├── Repository/     # Repositorios
  ├── Request/        # Request Validators
  └── Service/        # Servicios
```

## Características Implementadas

- Autenticación JWT
- Request Validators
- Manejo de excepciones centralizado
- Paginación
- CORS configurado
- Soft deletes
- Timestamps automáticos

## Comandos Útiles

```bash
# Limpia la caché
php bin/console cache:clear

# Verifica rutas
php bin/console debug:router

# Verifica servicios
php bin/console debug:container
```

## Autor

Steven Varela - Desarrollador Backend Senior
