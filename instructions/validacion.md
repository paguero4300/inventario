# 9. VALIDACIONES REQUERIDAS

1. User email: unique, required, email format
2. Product SKU: unique, required
3. InventoryEntry quantity: numeric, min:0
4. InventoryEntry unit_id: required, exists:units,id
5. Product category_id: required, exists:categories,id
6. Location code: unique, required
7. Category slug: unique, required
8. UnitConversion: unique [category_id, from_unit_id, to_unit_id]
9. UnitConversion conversion_factor: numeric, min:0.0001


# 10. NOTAS IMPORTANTES

1. El campo specifications en products es JSON y almacena datos dinámicos según categoría
2. El campo specifications_schema en categories define la estructura de campos para cada categoría
3. La unidad por defecto cambia automáticamente según la categoría seleccionada
4. El historial muestra cantidad + unidad + usuario (ej: 500 pcs por Juan)
5. quantity_base siempre almacena el valor convertido a Pzas (unidad base)
6. Las conversiones pueden ser globales (category_id = null) o específicas por categoría
7. Filament Shield genera automáticamente permisos CRUD para cada recurso
8. El rol super_admin tiene acceso total y no puede ser eliminado
9. Todos los datos de especificaciones provienen de documentación v1.0 y v2.0
10. Los factores de conversión son configurables por el administrador