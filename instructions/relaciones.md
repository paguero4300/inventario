# 3. DIAGRAMA DE RELACIONES

## 3.1 Relaciones entre Modelos
1. User hasMany InventoryEntries
2. User belongsToMany Roles (via Spatie)
3. User belongsToMany Permissions (via Spatie)
4. Category hasMany Products
5. Category belongsTo Unit (default_unit)
6. Category hasMany UnitConversions
7. Product belongsTo Category
8. Product hasMany InventoryEntries
9. InventoryEntry belongsTo Product
10. InventoryEntry belongsTo Location
11. InventoryEntry belongsTo Unit
12. InventoryEntry belongsTo User
13. Location hasMany InventoryEntries
14. Unit hasMany InventoryEntries
15. Unit hasMany UnitConversions (as from_unit)
16. Unit hasMany UnitConversions (as to_unit)
17. UnitConversion belongsTo Unit (from_unit)
18. UnitConversion belongsTo Unit (to_unit)
19. UnitConversion belongsTo Category (nullable)