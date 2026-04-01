# MonetizaYa

> Plataforma de monetización para creadores de contenido digital. Suscripciones recurrentes, contenido premium y pagos gestionados con Stripe.

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=flat-square&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-4.x-38BDF8?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

---

## Índice

1. [Descripción](#descripción)
2. [Problema que resuelve](#problema-que-resuelve)
3. [Propuesta de valor](#propuesta-de-valor)
4. [Funcionalidades principales](#funcionalidades-principales)
5. [Roles de usuario](#roles-de-usuario)
6. [Flujo del sistema](#flujo-del-sistema)
7. [Stack tecnológico](#stack-tecnológico)
8. [Arquitectura general](#arquitectura-general)
9. [Consideraciones de producción](#consideraciones-de-producción)
10. [Instalación local](#instalación-local)
11. [Estructura del proyecto](#estructura-del-proyecto)
12. [Roadmap](#roadmap)
13. [Buenas prácticas](#buenas-prácticas)
14. [Licencia](#licencia)

---

## Descripción

**MonetizaYa** es una plataforma SaaS que permite a creadores de contenido digital —escritores, educadores, artistas, desarrolladores— monetizar su audiencia mediante suscripciones mensuales recurrentes.

Los creadores publican contenido exclusivo (posts, recursos descargables, cursos) al que solo acceden sus suscriptores activos. La plataforma gestiona el ciclo completo: registro, pagos, acceso al contenido y distribución de ingresos, reteniendo una comisión configurable por transacción.

El proyecto está construido para producción real desde el primer día: arquitectura limpia, pagos seguros vía Stripe y una base de código mantenible y escalable.

---

## Problema que resuelve

Los creadores de contenido en habla hispana no disponen de una plataforma de membresías con buen soporte local, precios razonables y control real sobre su contenido y audiencia. Las opciones existentes (Patreon, Ko-fi, Buy Me a Coffee) están orientadas al mercado anglosajón, aplican comisiones elevadas y ofrecen poca flexibilidad para adaptarse a modelos de negocio específicos.

MonetizaYa cubre este hueco con:

- Una plataforma en español, pensada para el mercado hispanohablante.
- Comisiones transparentes y configurables desde el panel de administración.
- Control total sobre los tipos de contenido y las estructuras de suscripción.
- Infraestructura propia, sin dependencia de terceros para la gestión de la audiencia.

---

## Propuesta de valor

| Para el creador | Para el suscriptor |
|---|---|
| Publica posts, archivos y cursos en un solo lugar | Accede a todo el contenido exclusivo de los creadores que sigue |
| Cobra automáticamente cada mes vía Stripe | Gestiona sus suscripciones activas desde un panel unificado |
| Visualiza métricas de ingresos y suscriptores | Paga de forma segura; sus datos no los gestiona el creador |
| Controla qué contenido es gratuito y cuál es premium | Recibe notificaciones cuando se publica contenido nuevo |

---

## Funcionalidades principales

### Gestión de creadores

- Perfil público personalizable: avatar, portada, descripción, redes sociales y enlace de suscripción.
- Panel de creador con métricas: ingresos del mes, suscriptores activos, churn mensual y contenido más visitado.
- Configuración del precio mensual de suscripción.
- Creación y edición de contenido premium con control de visibilidad.

### Tipos de contenido

- **Posts**: entradas de texto enriquecido con soporte para imágenes y embeds. Pueden ser gratuitas o exclusivas para suscriptores.
- **Recursos descargables**: archivos PDF, ZIP, imágenes, audio o cualquier formato. Descarga protegida mediante URLs firmadas temporales.
- **Cursos**: estructura de módulos y lecciones ordenadas. Acceso secuencial opcional. Progreso del alumno persistido por usuario.

### Suscripciones y pagos

- Flujo de suscripción gestionado íntegramente con Stripe Checkout y Laravel Cashier.
- Cobros recurrentes mensuales con renovación automática.
- Gestión de fallos de pago: reintentos automáticos de Stripe y notificación al usuario.
- Cancelación y reactivación desde el panel del suscriptor.
- Revenue share: la plataforma retiene un porcentaje configurable; el creador ve su ingreso neto en tiempo real.
- Historial de cobros y descarga de facturas en PDF.

### Panel de administración

- Gestión de usuarios y creadores: activación, suspensión y eliminación.
- Configuración global de la comisión de la plataforma.
- Visualización de transacciones, disputas y reembolsos.
- Revisión de contenido denunciado.
- Estadísticas globales: GMV, ingresos netos, creadores activos, retención.

### Notificaciones

- Email transaccional (nuevo suscriptor, cobro exitoso, cobro fallido, cancelación).
- Notificaciones en plataforma para suscriptores (nuevo contenido publicado).

---

## Roles de usuario

### `user` — Suscriptor

Usuario registrado que puede explorar la plataforma, suscribirse a creadores y acceder al contenido premium de sus suscripciones activas. Gestiona sus métodos de pago y suscripciones desde su panel personal.

### `creator` — Creador

Usuario con perfil de creador habilitado (puede solicitarlo desde su cuenta o ser asignado por un administrador). Puede publicar contenido, configurar su precio de suscripción y acceder a su panel de analíticas e ingresos.

### `admin` — Administrador

Acceso total a la plataforma. Gestiona usuarios, creadores, contenido, configuración de comisiones y supervisa el estado financiero del sistema. No puede acceder a los métodos de pago individuales de los usuarios (gestionados directamente por Stripe).

---

## Flujo del sistema

```
[Visitante]
    │
    ├── Explora perfiles de creadores y contenido gratuito
    │
    └── Se registra como usuario
            │
            ├── Busca un creador → visita su perfil público
            │
            └── Inicia suscripción
                    │
                    ├── Redirige a Stripe Checkout (pago seguro)
                    │
                    └── Stripe confirma el pago
                            │
                            ├── Webhook → Laravel activa la suscripción en la BD
                            │
                            ├── El usuario accede al contenido premium del creador
                            │
                            └── Cada mes: Stripe cobra → webhook → registro de transacción
                                    │
                                    ├── Stripe calcula el neto del creador (descuento de comisión)
                                    │
                                    └── El creador visualiza el ingreso en su panel
```

Los eventos críticos de Stripe (pagos, cancelaciones, fallos, disputas) se gestionan exclusivamente a través de webhooks firmados, nunca mediante polling ni redirecciones de cliente.

---

## Stack tecnológico

| Capa | Tecnología | Motivo |
|---|---|---|
| Backend | Laravel 13 | Framework PHP maduro, ecosistema completo, excelente soporte para colas, jobs y eventos |
| Frontend | Blade + Livewire 3 | Interactividad reactiva sin SPA; menor complejidad y mejor SEO que React/Vue para este tipo de producto |
| Estilos | Tailwind CSS 4 | Utilidades atómicas, sin CSS custom innecesario, fácil de mantener a escala |
| Base de datos | MySQL 8 / PostgreSQL 15 | Relacional; ambas soportadas; PostgreSQL recomendado para producción por robustez y soporte de tipos avanzados |
| Pagos | Stripe + Laravel Cashier | Cashier abstrae la gestión de suscripciones sobre la API de Stripe; reduce código boilerplate y errores |
| Colas | Laravel Queues + Redis | Jobs asíncronos para emails, webhooks y procesamiento de archivos |
| Almacenamiento | Laravel Filesystem (S3 / local) | Archivos premium almacenados fuera del webroot, servidos mediante URLs firmadas |
| Despliegue | Coolify / Dokploy | Plataforma PaaS self-hosted sobre Docker; CI/CD con pipelines sencillos |

---

## Arquitectura general

### Backend

La lógica de negocio se organiza en servicios (`app/Services`) desacoplados de los controladores. Los controladores son delgados: validan la entrada, delegan al servicio correspondiente y devuelven la respuesta.

Los jobs de larga duración (procesamiento de archivos, envío de emails, sincronización con Stripe) se despachan a colas Redis para no bloquear el ciclo de petición-respuesta.

### Frontend

Las vistas Blade se complementan con componentes Livewire para las partes que requieren reactividad: formularios de publicación, panel de suscripciones, progreso de cursos y notificaciones en tiempo real. El JavaScript personalizado se limita a integraciones específicas (Stripe.js para el formulario de pago).

### Pagos

El flujo de cobro no pasa por los servidores de MonetizaYa: el usuario es redirigido a Stripe Checkout, que gestiona la captura de datos de tarjeta de forma segura. Los eventos de pago llegan de vuelta mediante webhooks firmados con la clave secreta de Stripe, verificados antes de procesarse.

La comisión de la plataforma se implementa mediante **Stripe Connect** (modelo de destino), donde MonetizaYa actúa como plataforma y los creadores como cuentas conectadas. Esto garantiza la separación de fondos y el cumplimiento normativo.

### Seguridad

- Autenticación con Laravel Breeze / Fortify (soporte para 2FA).
- Autorización mediante Policies de Laravel; ningún endpoint de creador es accesible sin verificar el rol.
- Contenido premium servido exclusivamente con URLs firmadas de S3 de corta duración (15 minutos); no existen URLs permanentes.
- Webhooks de Stripe verificados con `StripeSignatureVerificationException` antes de procesar cualquier evento.
- Variables sensibles (claves de API, credenciales de BD) gestionadas con `.env` y nunca versionadas.
- Headers de seguridad HTTP configurados a nivel de servidor (HSTS, CSP, X-Frame-Options).
- Rate limiting en endpoints de autenticación y API pública.

---

## Consideraciones de producción

### Escalabilidad

- La aplicación es stateless: puede escalar horizontalmente añadiendo instancias sin modificar código.
- Las sesiones y caché se almacenan en Redis, compartido entre instancias.
- Los archivos de usuario residen en S3 (o compatible), no en el disco de la instancia.
- Las colas permiten absorber picos de carga sin afectar tiempos de respuesta.

### Seguridad en pagos

- MonetizaYa nunca almacena datos de tarjeta; cumplimiento PCI delegado a Stripe.
- Los `payment_method` y `customer_id` de Stripe son los únicos identificadores financieros guardados en la BD.
- Todas las transacciones son registradas localmente para auditoría, incluso cuando el origen es un webhook.

### Protección de contenido premium

- Los archivos no son accesibles por URL directa; el servidor de almacenamiento los mantiene privados.
- Cada solicitud de descarga genera una URL firmada nueva, verificando en el backend que el usuario tiene suscripción activa.
- El streaming de vídeo (si aplica en el roadmap) se implementará con HLS y tokens de acceso de corta duración.

### Manejo de errores de pago

- Stripe gestiona los reintentos automáticos según su lógica de Smart Retries.
- El webhook `invoice.payment_failed` dispara un email al suscriptor y marca la suscripción como `past_due`.
- Tras el período de gracia configurable, la suscripción se cancela y el acceso al contenido se revoca automáticamente.

---

## Instalación local

### Requisitos previos

- PHP 8.3+
- Composer 2.x
- Node.js 20+ y npm
- MySQL 8+ o PostgreSQL 15+
- Redis
- Cuenta de Stripe con claves de prueba

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/tudor-constantin/monetizaya.git
cd monetizaya

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias de frontend
npm install

# 4. Copiar el archivo de entorno
cp .env.example .env

# 5. Generar la clave de aplicación
php artisan key:generate

# 6. Ejecutar migraciones
php artisan migrate

# 7. Compilar assets (desarrollo)
npm install & npm run dev

# 8. Iniciar el servidor
php artisan serve
```

#### Configurar `.env`

Edita el archivo `.env` con los valores de tu entorno local:

```env
APP_NAME=MonetizaYa
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monetizaya
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

CASHIER_CURRENCY=eur
PLATFORM_COMMISSION_PERCENTAGE=10
```

```bash
# 6. Ejecutar migraciones y seeders base
php artisan migrate --seed

# 7. Compilar assets
npm run dev

# 8. Levantar el servidor de desarrollo
php artisan serve

# 9. (En otra terminal) Iniciar el worker de colas
php artisan queue:work redis

# 10. (Opcional) Escuchar webhooks de Stripe en local
stripe listen --forward-to localhost:8000/stripe/webhook
```

La aplicación estará disponible en `http://localhost:8000`.

El seeder crea un usuario administrador por defecto:

```
Email: admin@monetizaya.test
Password: password
```

---

## Estructura del proyecto

```
monetizaya/
├── app/
│   ├── Console/            # Comandos Artisan personalizados
│   ├── Events/             # Eventos del dominio (SubscriptionCreated, ContentPublished...)
│   ├── Http/
│   │   ├── Controllers/    # Controladores delgados
│   │   ├── Livewire/       # Componentes Livewire
│   │   └── Middleware/     # Middlewares personalizados (CheckSubscription, etc.)
│   ├── Jobs/               # Jobs asíncronos (SendWelcomeEmail, ProcessDownload...)
│   ├── Listeners/          # Listeners de eventos
│   ├── Models/             # Modelos Eloquent
│   ├── Policies/           # Políticas de autorización
│   ├── Services/           # Lógica de negocio (SubscriptionService, RevenueService...)
│   └── Webhooks/           # Handlers de webhooks de Stripe
├── config/                 # Configuración de la aplicación
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── components/     # Componentes Blade reutilizables
│       ├── creator/        # Vistas del panel de creador
│       ├── admin/          # Vistas del panel de administración
│       └── layouts/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── webhooks.php        # Rutas de webhooks separadas del resto
├── storage/
│   └── app/private/        # Archivos temporales (no para producción permanente)
└── tests/
    ├── Feature/
    └── Unit/
```

---

## Roadmap

### v1.0 — Lanzamiento inicial

- [x] Autenticación y roles (usuario, creador, admin)
- [x] Perfil público de creador
- [x] Posts con control de visibilidad
- [x] Recursos descargables con URLs firmadas
- [x] Suscripciones mensuales vía Stripe Checkout
- [x] Revenue share con Stripe Connect
- [x] Panel de creador con métricas básicas
- [x] Panel de administración
- [x] Emails transaccionales
- [x] Navegación SPA con Livewire Volt (wire:navigate)
- [x] Modo oscuro completo con toggle persistente
- [x] UI/UX profesional con Tailwind CSS 4
- [ ] Formularios de autenticación con mensajes seguros en inglés
- [ ] Traducciones completas en `lang/en/` (auth, passwords, validation, profile)
- [ ] Toast notifications profesionales con auto-dismiss y cierre manual responsive
- [x] Políticas de autorización para contenido premium
- [x] Middleware de roles (creator, admin)
- [x] Servicios de negocio (SubscriptionService, RevenueService)
- [x] Webhook handler de Stripe para eventos de pago
- [x] Modelos y migraciones (Post, Resource, Course, Module, Lesson, Transaction)

### v1.1 — Cursos

- [ ] Módulos y lecciones con orden configurable
- [ ] Progreso del alumno
- [ ] Acceso secuencial opcional
- [ ] Emisión de certificado de finalización (PDF)

### v1.2 — Monetización avanzada

- [ ] Niveles de suscripción (tiers) con beneficios diferenciados
- [ ] Contenido de pago único (fuera de suscripción)
- [ ] Códigos de descuento y acceso gratuito temporal

### v1.3 — Comunidad y engagement

- [ ] Sistema de comentarios en posts y lecciones
- [ ] Notificaciones push (web push API)
- [ ] Feed de actividad del suscriptor

### v2.0 — Plataforma multi-tenant / white-label

- [ ] Dominio personalizado por creador
- [ ] Temas visuales configurables
- [ ] API pública para integraciones externas

---

## Buenas prácticas

- **Arquitectura en capas**: los controladores no contienen lógica de negocio; esta reside en servicios testables.
- **Eventos y listeners**: las acciones secundarias (emails, notificaciones, registro de métricas) se desacoplan mediante el sistema de eventos de Laravel.
- **Tests desde el inicio**: cobertura de Feature tests para los flujos críticos (suscripción, acceso a contenido, webhooks). Se utiliza `RefreshDatabase` y factories para aislar cada test.
- **Migraciones atómicas**: cada migración hace una sola cosa; se evitan migraciones que modifican y crean en el mismo archivo.
- **Convenciones de nomenclatura**: se sigue el estándar de Laravel para modelos, controladores, policies y jobs.
- **Variables de entorno tipadas**: valores booleanos y numéricos casteados en `config/`; nunca se llama a `env()` directamente desde el código de aplicación.
- **Secrets fuera del repositorio**: `.env` en `.gitignore`; `.env.example` documentado y actualizado en cada PR.
- **Logs estructurados**: uso de canales de log diferenciados para pagos, errores de webhook y actividad de usuario.

---

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).

---

<p align="center">
  Construido con ☕ y Laravel · <a href="https://github.com/tudor-constantin/monetizaya">github.com/tudor-constantin</a>
</p>