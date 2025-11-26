# 6. ESPECIFICACIONES DINÁMICAS POR CATEGORÍA

Cada categoría tiene campos específicos según documentación v1.0 y v2.0. El campo specifications_schema en categories define la estructura JSON de campos dinámicos:

## 6.1 MADERA (Wood)
1. Tipo de Pieza: Post, Rail, Boards, Trim, Top Plate
2. Material/Especie: Cedro, Pino, Redwood
3. Tratamiento: Presión Tratada (PT), Natural/Sin Tratar, Pre-Teñida (Stained)
4. Tamaño Nominal: 1x4x6, 1x6x6, 2x4x8, 4x4x8, 4x4x10, 6x6x8, Custom
5. Largo Específico: Campo libre (Ej: 6ft)
6. Unidad Default: Pies

## 6.2 ALUMINIO (Aluminum)
1. Tipo de Componente: Panel, Line Post, End Post, Corner Post, Gate Post, Blank Post, Gate Standard, Gate Flush Bottom, Gate Arc
2. Acabado/Color: Black, Zinc, Satin Black, Satin Zinc, Negro Mate, Bronce, Blanco, Galvanizado
3. Calibre/Grosor: Campo texto (ej: 14ga, pared .065)
4. Altura: 48", 54", 60", 72"
5. Ancho (Gate/Panel): Campo libre
6. Unidad Default: Pies

## 6.3 VINYL (Vinyl Fencing)
1. Componente: Vinyl Post, Vinyl Rail, Vinyl Board
2. Color: Tan, Adobe, White
3. Estilo: Privacidad (Privacy)
4. Dimensiones: 4x4, 5x5, 1.5x5.5 Rail, T&G Board
5. Largo: Campo libre (Ej: 8ft)
6. Unidad Default: Pies

## 6.4 STEEL (Acero)
1. Componente: Post, Rail
2. Espesor/Tipo: SS20, SS40, Schedule 40
3. Acabado/Color: Negro Mate, Bronce, Blanco, Galvanizado (Solo Acero), Aluminio (Galv), Negro
4. Calibre/Grosor: Campo texto
5. Largo: 6', 7', 8', 9', 10', 12', 21', 24'
6. Unidad Default: Pzas (DIFERENCIA CRÍTICA)

## 6.5 CHAIN LINK (Malla Eslabonada)
1. Tipo Tejido: KK (Knuckle-Knuckle), KT (Knuckle-Twist)
2. Calibre (Gauge): 9ga, 11ga, 11.5ga, 12ga, 6ga
3. Altura: 42", 48", 60", 72", 84", 96", 144" (12')
4. Color: Galvanized, Black, Green
5. Unidad Default: Pies

## 6.6 ACCESORIOS (Accessories)
1. Categoría Padre: Madera, Aluminio, Chain Link, Vinyl
2. Nombre Accesorio: Tapa (Cap), Bisagra (Hinge), Latch, Bracket, Tornillos (Box), Drop Rod, Wheel
3. Descripción/SKU: Campo libre
4. Unidad Default: Pzas