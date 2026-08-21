---
description: Compila los assets de producción (Vite)
---

Ejecuta el build de assets del proyecto Laravel con Vite:

```
npm run build
```

Verifica que termine sin errores y que se actualice `public/build/manifest.json` (o el bundle correspondiente en `public/build/`).

- Si se requiere modo watch durante desarrollo, usa `npm run dev`.
- El build NO es necesario antes de correr tests.
