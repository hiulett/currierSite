---
description: Ejecuta la suite de tests PHPUnit
---

Ejecuta la suite de tests del proyecto Laravel:

```
composer test
```

`composer test` equivale a `php artisan config:clear --ansi && php artisan test`.

**Loop rápido (sin build/restore, reutiliza el estado previo):**

```
php vendor/bin/phpunit --filter <NombreDeTest>
```

- Usa el loop rápido para verificar un test o grupo acotado.
- Corre la suite completa (`composer test`) solo cuando cambian firmas o para el cierre.
- No requiere `npm run build` para testear.
