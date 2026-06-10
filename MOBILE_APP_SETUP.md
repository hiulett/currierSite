# 📱 Configuración de la App Móvil (Operador)

Esta aplicación está desarrollada en **Flutter** y permite a los operadores de bodega realizar recepciones rápidas usando la cámara del celular.

## 🚀 Requisitos Previos
1. Tener Flutter instalado (`flutter doctor` debe estar OK).
2. El servidor Laravel debe estar corriendo (`php artisan serve --host=0.0.0.0`).
3. **Importante:** Tu PC y el celular deben estar en la misma red Wi-Fi.

## 🛠️ Configuración de la API
Edita el archivo `mobile/lib/services/api_service.dart`:
*   Si usas el **Emulador Android**: Usa `http://10.0.2.2/api/v1`.
*   Si usas un **Celular Real**: Usa `http://TU_IP_LOCAL/api/v1` (ej. `http://192.168.1.15/api/v1`).

## 📦 Funcionalidades Implementadas
1.  **Autenticación**: Login con correo corporativo (Staff/Admin/Operador).
2.  **Recepción Unitaria**: Escanea un paquete y muestra si tiene pre-alerta.
3.  **Recepción Masiva (Beta)**: 
    *   Escaneo continuo sin interrupciones.
    *   Lista de paquetes escaneados editable (peso).
    *   Sincronización por lotes al servidor en un solo clic.

## 🏗️ Comandos Útiles
```bash
cd mobile
flutter pub get
flutter run
```

## 🔐 Seguridad (Sanctum)
La app usa **Laravel Sanctum** para la autenticación vía tokens. Al loguearse, el token se guarda de forma segura en el dispositivo (`flutter_secure_storage`).
