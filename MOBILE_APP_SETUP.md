# 📱 Configuración de la App Móvil (Operador)

Esta aplicación está desarrollada en **Flutter** y permite a los operadores de bodega realizar recepciones rápidas usando la cámara del celular.

## 🚀 Requisitos Previos
1. Tener Flutter instalado (`flutter doctor` debe estar OK).
2. El servidor Laravel debe estar corriendo (`php artisan serve --host=0.0.0.0`).
3. **Importante:** Tu PC y el celular deben estar en la misma red Wi-Fi.

## 🛠️ Configuración de la API
Edita el archivo `mobile/lib/services/api_service.dart`:
*   Tu IP local detectada es: `192.168.50.195`
*   Usa: `http://192.168.50.195:8001/api/v1`

**IMPORTANTE:** Para que la app (o el navegador) pueda conectarse, debes iniciar el servidor de Laravel permitiendo conexiones externas:
```bash
php artisan serve --host=0.0.0.0 --port=8001
```

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
