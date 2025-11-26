# 1. INTRODUCCIÓN Y CONTEXTO DEL SISTEMA

## 1.1 ¿Qué es Fence Warehouse Manager?
Fence Warehouse Manager es un sistema de gestión de inventario especializado para empresas dedicadas a la venta, instalación y distribución de productos de cercado (fencing). El sistema está diseñado para manejar la complejidad inherente de este tipo de inventario, donde un mismo tipo de producto puede variar significativamente en material, dimensiones, color, tratamiento y unidad de medida.

## 1.2 Problema que Resuelve
Las empresas de cercado enfrentan desafíos únicos en la gestión de inventario:
1. **Diversidad de materiales**: Madera, aluminio, vinyl, acero y malla eslabonada requieren especificaciones completamente diferentes.
2. **Múltiples unidades de medida**: Algunos productos se venden por piezas, otros por pies lineales, cajas o pallets completos.
3. **Especificaciones técnicas variables**: Cada categoría tiene atributos únicos (calibre en metales, tratamiento en madera, tipo de tejido en malla).
4. **Control de ubicación física**: Los almacenes requieren organización por racks y bins para localización rápida.
5. **Trazabilidad de movimientos**: Necesidad de registrar entradas, salidas y ajustes con historial completo.

## 1.3 Solución Propuesta
El sistema implementa:
1. **Formularios dinámicos**: Campos que cambian automáticamente según la categoría de producto seleccionada.
2. **Smart defaults de unidades**: El sistema sugiere la unidad de medida más apropiada según el material (pies para madera, piezas para acero).
3. **Conversión automática de unidades**: Permite convertir entre piezas, pies, cajas y pallets con factores configurables.
4. **Control de acceso por roles**: Diferentes permisos para administradores, supervisores y operarios de almacén.
5. **Historial y auditoría**: Registro completo de todas las operaciones con usuario, fecha y detalles.

## 1.4 Usuarios del Sistema
1. **Super Admin**: Acceso total al sistema, gestión de usuarios, roles y configuración.
2. **Administrador**: Gestión de catálogos (categorías, productos, ubicaciones), reportes completos.
3. **Supervisor**: Visualización de inventario, aprobación de ajustes, reportes operativos.
4. **Operario**: Registro de entradas y salidas, consulta de inventario y ubicaciones.

## 1.5 Flujo Principal de Operación
El flujo típico de recepción de materiales es:
1. Operario selecciona la categoría del material (Madera, Aluminio, Vinyl, Steel, Chain Link, Accesorios)
2. Sistema carga el formulario dinámico con campos específicos de esa categoría
3. Sistema pre-selecciona la unidad de medida por defecto según el material
4. Operario completa especificaciones (SKU, dimensiones, color, cantidad, ubicación)
5. Operario puede cambiar la unidad si es necesario (ej: registrar en cajas en lugar de piezas)
6. Sistema calcula equivalencias automáticamente si hay conversión de unidades
7. Se registra la entrada y actualiza el historial con usuario y timestamp
8. Dashboard muestra resumen de sesión actualizado