# 5. SISTEMA DE CONVERSIÓN AUTOMÁTICA DE UNIDADES

## 5.1 Concepto y Funcionamiento
El sistema permite registrar inventario en cualquiera de las 4 unidades disponibles (Pzas, Pies, Cajas, Pallet) y automáticamente calcula la equivalencia a la unidad base para mantener consistencia en los totales.

## 5.2 Unidad Base
La unidad base es 'Pzas' (piezas). Todas las conversiones se calculan hacia esta unidad para facilitar totalizaciones. El campo 'quantity_base' en inventory_entries almacena siempre el valor convertido a piezas.

## 5.3 Factores de Conversión por Defecto
Los factores pueden ser globales o específicos por categoría:

| De | A | Factor | Ejemplo |
|----|---|--------|---------|
| Pies | Pzas | 1.0000 | 1 pie = 1 pieza (tabla) |
| Cajas | Pzas | Configurable | 1 caja = X piezas |
| Pallet | Pzas | Configurable | 1 pallet = X piezas |

## 5.4 Conversiones Específicas por Categoría (Ejemplos)
Los administradores pueden configurar factores específicos por categoría:

| Categoría | De | A | Factor | Notas |
|-----------|----|---|--------|-------|
| Madera | Cajas | Pzas | 50.0000 | 1 caja = 50 tablillas |
| Madera | Pallet | Pzas | 500.0000 | 1 pallet = 500 piezas |
| Accesorios | Cajas | Pzas | 100.0000 | 1 caja tornillos = 100 |
| Chain Link | Pies | Pzas | 1.0000 | 1 pie lineal de malla |

## 5.5 Lógica de Conversión
Al registrar una entrada de inventario:
1. Usuario ingresa cantidad y selecciona unidad
2. Sistema busca factor de conversión: primero específico de categoría, luego global
3. Si encuentra factor: quantity_base = quantity × conversion_factor
4. Si no encuentra factor y unidad es base (Pzas): quantity_base = quantity
5. Si no encuentra factor y unidad no es base: quantity_base = null (pendiente configuración)

## 5.6 Servicio de Conversión (UnitConversionService)
Crear un servicio en app/Services/UnitConversionService.php con los métodos:
1. convert(float $quantity, int $fromUnitId, int $toUnitId, ?int $categoryId): ?float
2. convertToBase(float $quantity, int $unitId, ?int $categoryId): ?float
3. getConversionFactor(int $fromUnitId, int $toUnitId, ?int $categoryId): ?float