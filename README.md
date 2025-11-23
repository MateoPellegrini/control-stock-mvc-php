# Sistema de Control de Stock (Almacén / Supermercado) – PHP MVC

Sistema de gestión de stock orientado a **almacenes / supermercados**, desarrollado con **PHP puro** usando el patrón **MVC**, con control de roles, historial de stock y módulos de compras y ventas.

Este proyecto está pensado como **práctica real** y también como **proyecto de portfolio**.

---

## ✨ Funcionalidades principales

- **Autenticación con roles**:
  - `admin`
  - `compras`
  - `ventas`
  - `deposito`

- **Productos**:
  - ABM de productos.
  - Categorías anidadas (padre / subcategoría) para organizar rubros.
  - SKU autogenerado a partir del ID (`P000001`, `P000002`, etc.).
  - Código de barras opcional.
  - Precios de compra y venta.
  - Stock actual + stock mínimo.

- **Historial de stock**:
  - Tabla de movimientos de stock por producto.
  - Guarda: usuario, tipo (compra, venta, ajuste_manual, correccion), cantidad, stock antes / después, motivo.
  - Vista de historial por producto.

- **Compras**:
  - Registro de compras simples por producto.
  - Autocompletado del precio de compra según el producto.
  - Cálculo automático de total: cantidad × precio unitario.
  - Aumenta stock y genera movimiento `tipo = 'compra'`.

- **Ventas**:
  - Registro de ventas simples por producto.
  - Autocompletado del precio de venta según el producto.
  - Cálculo automático de total: cantidad × precio unitario.
  - Disminuye stock y genera movimiento `tipo = 'venta'`.

- **Seguridad básica**:
  - Login con hash + salt (SHA-256).
  - Restricción de acciones según rol (ej: solo admin/compras pueden modificar stock desde ABM).

---

## 🛠 Tecnologías

- **Backend**: PHP (MVC manual, sin framework)
- **Base de datos**: MySQL / MariaDB
- **Frontend**:
  - Bootstrap 5
  - Tailwind CSS (via CDN)
- **Control de versiones**: Git + GitHub

---

## 📦 Requisitos

- PHP 7.4+ / 8.x
- MySQL o MariaDB
- Servidor local (XAMPP, Laragon, WAMP, etc.)

---

## ⚙️ Instalación y configuración

1. **Clonar el repositorio**

```bash
git clone https://github.com/TU_USUARIO/control-stock-mvc-php.git
cd control-stock-mvc-php
