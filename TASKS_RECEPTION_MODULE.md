# Tareas Detalladas: Módulo de Recepción y Finanzas

## Fase 1: Base de Datos y Modelos 🛠️
- [x] Análisis de campos requeridos (Peso, Dims, Costo Proveedor).
- [x] Crear migración para añadir campos financieros a la tabla `packages`.
- [x] Crear migración para añadir campos financieros a la tabla `manifests` (Costo total de flete).
- [x] Actualizar el modelo `Package.php` con propiedades calculadas de margen y ROI.

## Fase 2: Integración OCR/IA (Costo Cero) 🧠
- [x] Instalar Tesseract OCR en el servidor de desarrollo/producción (Configurado en Railway).
- [x] Implementar `AIParserService` usando la librería PHP Tesseract.
- [x] Crear patrones Regex para extraer datos de la tabla de Global Express (Tracking, Peso, Precio).
- [x] Crear tabla `ocr_logs` para auditoría y corrección manual.

## Fase 3: Consolidación de UI (Livewire) 💻
- [x] Crear nuevo componente `SmartReceptionHub`.
- [x] Implementar "Split-View": Scanner (Izquierda) vs Detalle OCR (Derecha).
- [x] Añadir validación visual de discrepancias de peso (Proveedor vs Bodega).
- [x] Integrar carga de archivos (PDF/JPG) con feedback inmediato de la IA.

## Fase 4: Inteligencia de Negocio y Dashboard 📊
- [x] Crear widgets de "Ganancia Proyectada" en el Dashboard principal.
- [x] Implementar lógica de comparación: Costo Proveedor vs. Ingreso Cliente.
- [x] Crear alerta de "Fugas de Dinero" (Paquetes con margen negativo).
- [x] Implementar alertas automáticas cuando un proveedor sube sus tarifas.

## Fase 5: Facturación Inteligente 🧾
- [x] Modificar `CreateInvoice` para mostrar utilidad neta antes de emitir.
- [x] Añadir desglose de costos logísticos en la vista administrativa de la factura.
