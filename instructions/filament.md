# 7. RECURSOS FILAMENT 4

## 7.1 Recursos a Crear
1. UserResource - CRUD de usuarios con asignación de roles (protegido por Shield)
2. RoleResource - Gestión de roles y permisos (generado por Shield)
3. CategoryResource - CRUD de categorías con schema JSON para especificaciones
4. UnitResource - CRUD de unidades de medida
5. UnitConversionResource - Gestión de factores de conversión
6. LocationResource - CRUD de ubicaciones (Rack/Bin)
7. ProductResource - CRUD de productos con formulario dinámico según categoría
8. InventoryEntryResource - Registro de entradas/salidas con historial y conversión

## 7.2 Widgets del Dashboard
1. SessionSummaryWidget: Contador de items agregados y unidades totales (de v1.0)
2. RecentEntriesWidget: Historial reciente de entradas con usuario (de v2.0)
3. InventoryByCategoryWidget: Resumen de stock por categoría
4. LowStockAlertWidget: Productos con bajo inventario (opcional)

## 7.3 Navegación por Pestañas
Implementar navegación similar a v1.0 con iconos por categoría:
1. Madera - heroicon-o-squares-2x2
2. Aluminio - heroicon-o-cog-6-tooth
3. Vinyl - heroicon-o-cube
4. Steel - heroicon-o-wrench
5. Chain Link - heroicon-o-link
6. Accesorios - heroicon-o-star

## 7.4 Grupos de Navegación
1. Inventario: InventoryEntryResource, ProductResource
2. Catálogos: CategoryResource, UnitResource, UnitConversionResource, LocationResource
3. Administración: UserResource, RoleResource (solo super_admin)