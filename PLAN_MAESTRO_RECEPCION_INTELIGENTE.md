# Plan Maestro: Smart Reception & Financial Hub (Edición Costo Cero)

## 1. Visión General
Transformar el proceso de recepción mediante herramientas open-source, eliminando costos operativos por procesamiento de documentos.

## 2. Estrategia de Extracción OCR (Local & Gratis)
Para procesar facturas de **Global Express Logistics**:

*   **Motor:** Tesseract OCR (Instalación local en servidor).
*   **Lógica de Extracción:**
    *   **Paso 1:** Conversión de PDF a Imagen (usando `Spatie/PdfToImage` - Open Source).
    *   **Paso 2:** Extracción de texto plano con Tesseract.
    *   **Paso 3:** Parsing con Regex: Identificación de patrones como `1ZV...` para tracking y valores decimales para costos.

## 3. Arquitectura de Datos
Se optimizará la base de datos actual para guardar la "foto financiera" del paquete al momento de recibir:
*   `provider_cost`: Lo que pagas a Global Express.
*   `provider_weight`: El peso que ellos facturan.
*   `margin_amount`: (Cobro Cliente - Costo Proveedor).

## 4. Visualización de Datos
*   Uso de **Chart.js** para el Dashboard de Ganancias.
*   Alertas visuales en Blade/Livewire para paquetes con rentabilidad negativa.
