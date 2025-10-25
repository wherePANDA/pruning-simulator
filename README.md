# 🌿 Simulador de Poda (HTML/Tailwind/JS + PHP)

**Simulador didáctico e interactivo de poda** con diseño moderno y responsive.
Permite elegir el **tipo de planta**, la **vista** (lateral/aérea) y el **modo de poda** (formación/mantenimiento), marcar ramas con un clic y obtener **evaluación automática** con puntuación y sugerencias.

---

## ✨ Características

- **Interacción directa**: marca o desmarca ramas haciendo clic en el lienzo SVG.
- **Tres tipos de planta**: Arbusto joven, Rosal, Árbol joven.
- **Dos vistas**: *Lateral* y *Aérea (top‑down)* para analizar mejor la distribución radial.
- **Dos modos de poda**:
  - **Formación** → límite recomendado ≤ **30%** de masa, conservar ápice/eje en árbol joven, yemas externas, equilibrio de copa.
  - **Mantenimiento** → límite recomendado ≤ **20%** de masa, priorizar ramas secas/dañadas/enfermas, eliminar cruces, respetar forma.
- **Evaluación automática**: calcula % de masa eliminada, equilibrio, orientación de yemas y **puntuación /100** con mensajes didácticos.
- **UI moderna con Tailwind**: tarjetas, *segmented buttons*, leyenda integrada, **barras de progreso** para % y puntuación.
- **Accesible y ligera**: HTML + JS + Tailwind por CDN. Sin *build* ni dependencias externas.

---

## 🚀 Demo local (PHP)

Este repositorio incluye un archivo `poda.php` listo para usar en un servidor PHP.

### Opción A — Servidor embebido de PHP
```bash
php -S localhost:8080
# Abre en el navegador: http://localhost:8080/poda.php
```

### Opción B — Apache/Nginx
Copia el archivo `poda.php` a tu carpeta pública del servidor (por ejemplo, `public_html/` o `www/`) y accede vía navegador.

> **Nota:** GitHub Pages no ejecuta PHP. Si deseas usar GitHub Pages, renombra `poda.php` a `index.html` y ajusta el encabezado PHP (es solo comentario) — funcionará como archivo estático, ya que toda la lógica es JS.

---

## 🧩 Cómo usar

1. Abre `poda.php` en tu servidor local.
2. Elige **Tipo de planta** (panel izquierdo).
3. Cambia **Vista** (Lateral / Aérea) y **Tipo de poda** (Formación / Mantenimiento).
4. Haz clic en las **ramas** del lienzo para marcar cortes.
5. Pulsa **Evaluar** para ver la **puntuación** y **sugerencias**.
6. Usa **Limpiar** para quitar marcas o **Reiniciar** para empezar de nuevo.

---

## 📊 Reglas y evaluación

### 🌱 Formación
- Quitar ≤ **30%** de la masa vegetal.
- Conservar el **eje principal** en árbol joven.
- Favorecer **yemas externas** para abrir la copa.
- Buscar **equilibrio lateral** o **distribución radial** (vista aérea).

### 🌿 Mantenimiento
- Quitar ≤ **20%** de la masa vegetal.
- Priorizar ramas **secas, dañadas o enfermas**.
- Eliminar **cruces** y ramas **hacia dentro**.
- Mantener **tamaño y forma** del ejemplar.

La app genera **mensajes explicativos** y una **puntuación sobre 100** para apoyo pedagógico.

---

## 🛠️ Tecnología

- **PHP** (para servir `poda.php`, aunque el contenido es estático).
- **HTML5 + TailwindCSS (CDN)** para el diseño.
- **JavaScript (vanilla)** para la lógica de simulación.
- **SVG** para representar ramas, yemas y cortes.

---

## 📂 Estructura del proyecto

```text
/pruning-simulator
├── poda.php       # App completa: HTML + Tailwind + JavaScript (sin build)
├── README.md      # Este archivo
└── LICENSE        # MIT License (opcional)
```

---

## 🧪 Pruebas rápidas

- Marca varias ramas y comprueba el **% de masa** (barra verde) y la **puntuación** (barra azul).
- Cambia a **Vista Aérea** en árbol joven y verifica la **distribución radial**.
- Alterna entre **Formación** y **Mantenimiento** para comparar reglas y mensajes.

---

## 🐞 Solución de problemas

- **No se ve nada / Pantalla en blanco**: abre la consola del navegador (F12) y revisa si hay errores. Vuelve a cargar la página sin caché (Ctrl/Cmd + Shift + R).
- **GitHub Pages**: recuerda que Pages **no ejecuta PHP**. Usa un servidor PHP o renombra a `index.html` para usarlo como estático.
- **Lento en móviles antiguos**: reduce el tamaño del lienzo (propiedad `viewBox` o altura CSS) para mejorar rendimiento.

---

## 🗺️ Roadmap (ideas futuras)

- Medidor de **ángulo** entre ramas y objetivos con **separación mínima** (p. ej., ≥ 75°).
- **Modo retos** con objetivos guiados por tipo de planta.
- Exportar resultados a **CSV/PDF** para seguimiento del alumnado.
- Ajustes de **accesibilidad** extra (teclado completo y textos escalables).

---

## 📜 Licencia

Distribuido bajo licencia **MIT**. Eres libre de usar, modificar y distribuir con atribución.

---

## 👤 Autoría

Creado para fines didácticos de **Jardinería e integración laboral**.
Si este proyecto te resulta útil, ¡estrellas y PRs son bienvenidos! ✨
