# 2. ARQUITECTURA DE BASE DE DATOS

## 2.1 Orden de Migraciones
Ejecutar en este orden estricto para respetar foreign keys:
1. create_users_table (Laravel default + campos adicionales)
2. create_permission_tables (Spatie Permission - requerido por Shield)
3. create_units_table
4. create_unit_conversions_table
5. create_categories_table
6. create_locations_table
7. create_products_table
8. create_inventory_entries_table

## 2.2 Estructura de Tablas

### Tabla: users
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| name | string(255) | NO | Nombre completo del usuario |
| email | string(255) | NO | Email único para login |
| password | string(255) | NO | Contraseña hasheada |
| avatar_url | string(255) | SI | URL de imagen de perfil |
| is_active | boolean | NO | Default: true, permite desactivar usuarios |
| email_verified_at | timestamp | SI | Fecha de verificación de email |
| remember_token | string(100) | SI | Token para 'recordarme' |
| timestamps | timestamp | NO | created_at, updated_at |

*Nota: Las tablas de Spatie Permission (roles, permissions, model_has_roles, model_has_permissions, role_has_permissions) se crean automáticamente al instalar el paquete.*

### Tabla: units
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| name | string(50) | NO | Pzas, Pies, Cajas, Pallet |
| abbreviation | string(10) | NO | pcs, ft, box, plt |
| is_base_unit | boolean | NO | Default: false. True para unidad base de conversión |
| timestamps | timestamp | NO | created_at, updated_at |

### Tabla: unit_conversions
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| category_id | bigint FK | SI | FK a categories.id (null = global) |
| from_unit_id | bigint FK | NO | FK a units.id - Unidad origen |
| to_unit_id | bigint FK | NO | FK a units.id - Unidad destino |
| conversion_factor | decimal(10,4) | NO | Factor de conversión |
| timestamps | timestamp | NO | created_at, updated_at |

*Índice único: [category_id, from_unit_id, to_unit_id]*

### Tabla: categories
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| name | string(100) | NO | Madera, Aluminio, Vinyl, Steel, Chain Link, Accesorios |
| slug | string(100) | NO | Identificador URL-friendly único |
| icon | string(50) | SI | Heroicon para navegación |
| default_unit_id | bigint FK | SI | FK a units.id - Unidad por defecto |
| specifications_schema | json | SI | Esquema de campos dinámicos por categoría |
| is_active | boolean | NO | Default: true |
| sort_order | integer | NO | Default: 0 - Orden de visualización |
| timestamps | timestamp | NO | created_at, updated_at |

### Tabla: locations
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| code | string(20) | NO | Código único: R-01, A-12, etc. |
| rack | string(50) | SI | Identificador de rack |
| bin | string(50) | SI | Identificador de bin/contenedor |
| description | text | SI | Descripción de ubicación |
| is_active | boolean | NO | Default: true |
| timestamps | timestamp | NO | created_at, updated_at |

### Tabla: products
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| category_id | bigint FK | NO | FK a categories.id |
| sku | string(50) | NO | Código único: WD-PKT-1x6x6 |
| name | string(200) | NO | Nombre descriptivo del producto |
| specifications | json | SI | Especie, Tratamiento, Color, Calibre, etc. |
| dimensions | string(100) | SI | 1x4x6, 4x4x8, 48", 72", etc. |
| color | string(50) | SI | Black, White, Tan, Galvanized |
| notes | text | SI | Notas adicionales |
| is_active | boolean | NO | Default: true |
| timestamps | timestamp | NO | created_at, updated_at |

### Tabla: inventory_entries
| Campo | Tipo | Nullable | Descripción |
|-------|------|----------|-------------|
| id | bigint PK | NO | Identificador único |
| product_id | bigint FK | NO | FK a products.id |
| location_id | bigint FK | SI | FK a locations.id |
| unit_id | bigint FK | NO | FK a units.id |
| user_id | bigint FK | NO | FK a users.id - Usuario que registró |
| quantity | decimal(10,2) | NO | Cantidad en unidad seleccionada |
| quantity_base | decimal(10,2) | SI | Cantidad convertida a unidad base |
| entry_type | enum | NO | entrada, salida, ajuste |
| notes | text | SI | Notas adicionales de entrada |
| entry_date | timestamp | NO | Fecha/hora de entrada |
| timestamps | timestamp | NO | created_at, updated_at |