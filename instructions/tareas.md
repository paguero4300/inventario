# 8. SECUENCIA DE TAREAS PARA LA IA

## FASE 1: Proyecto Base
1. Crear proyecto Laravel 12: composer create-project laravel/laravel fence-warehouse
2. Configurar base de datos en .env
3. Instalar Filament 4: composer require filament/filament:"^3.0"
4. Ejecutar: php artisan filament:install --panels

## FASE 2: Sistema de Permisos
1. Instalar Spatie Permission: composer require spatie/laravel-permission
2. Publicar migración: php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
3. Instalar Filament Shield: composer require bezhansalleh/filament-shield
4. Configurar Shield: php artisan shield:install
5. Agregar trait HasRoles al modelo User
6. Agregar campos adicionales a migración users (is_active, avatar_url)

## FASE 3: Migraciones del Sistema
1. Crear migración create_units_table
2. Crear migración create_unit_conversions_table
3. Crear migración create_categories_table
4. Crear migración create_locations_table
5. Crear migración create_products_table
6. Crear migración create_inventory_entries_table
7. Ejecutar: php artisan migrate

## FASE 4: Modelos Eloquent
1. Crear modelo Unit con relaciones
2. Crear modelo UnitConversion con relaciones
3. Crear modelo Category con relaciones y cast JSON para specifications_schema
4. Crear modelo Location con relaciones
5. Crear modelo Product con relaciones y cast JSON para specifications
6. Crear modelo InventoryEntry con relaciones y entry_type enum
7. Actualizar modelo User con relaciones y traits

## FASE 5: Seeders
1. Crear UnitsSeeder: Pzas (pcs, base), Pies (ft), Cajas (box), Pallet (plt)
2. Crear CategoriesSeeder: 6 categorías con specifications_schema y default_unit_id
3. Crear UnitConversionsSeeder: Factores de conversión por categoría
4. Crear LocationsSeeder: Ubicaciones ejemplo (R-01, A-12, etc.)
5. Crear RolesSeeder: super_admin, admin, supervisor, operario
6. Crear UsersSeeder: Usuario admin inicial
7. Ejecutar: php artisan db:seed

## FASE 6: Recursos Filament
1. Crear UserResource con asignación de roles
2. Generar permisos Shield: php artisan shield:generate --all
3. Crear CategoryResource
4. Crear UnitResource
5. Crear UnitConversionResource
6. Crear LocationResource
7. Crear ProductResource con formulario dinámico
8. Crear InventoryEntryResource con conversión de unidades
9. Configurar políticas de acceso en cada recurso

## FASE 7: Funcionalidades Adicionales
1. Crear UnitConversionService en app/Services/
2. Crear widget SessionSummaryWidget
3. Crear widget RecentEntriesWidget
4. Crear widget InventoryByCategoryWidget
5. Implementar exportación CSV en InventoryEntryResource
6. Configurar navegación con grupos e iconos
7. Implementar acción 'Limpiar Formulario'
8. Registrar usuario inicial super_admin