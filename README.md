# 🌿 Simulador de Poda

**Simulador didáctico e interactivo de poda.**  
Elige el tipo de planta, vista y modo de poda, marca las ramas a cortar con un clic y pulsa **Evaluar** para obtener una puntuación y sugerencias automáticas.

---

## ✨ Características

- **Totalmente interactivo:** marca o desmarca ramas en un lienzo SVG con solo un clic.  
- **Tres tipos de planta:** Arbusto joven, Rosal, Árbol joven.  
- **Dos vistas:** lateral o aérea.  
- **Modos de poda:** Formación y Mantenimiento, con reglas didácticas específicas.  
- **Evaluación automática:** calcula porcentaje de masa eliminada, equilibrio, orientación de yemas y puntuación final.  
- **Visualización clara:** yemas externas e internas, ramas marcadas en rojo, leyenda integrada.  
- **Todo en una sola página:** HTML + TailwindCSS + JavaScript puro, sin dependencias externas.

---

## 🧩 Cómo usar

1. Abre `index.html` en tu navegador.  
2. Elige un **tipo de planta** en el panel izquierdo.  
3. Cambia la **vista** (Lateral o Aérea) y el **tipo de poda** (Formación o Mantenimiento).  
4. Haz clic sobre las **ramas** para marcar los cortes deseados.  
5. Pulsa **Evaluar** para ver tu puntuación y sugerencias.  
6. Usa **Limpiar** para quitar marcas o **Reiniciar** para volver al inicio.

---

## 📊 Evaluación y reglas

Cada modo de poda tiene su propio conjunto de reglas:

### 🌱 Formación
- Quitar ≤ 30% de la masa vegetal.  
- Mantener el eje principal (en árboles jóvenes).  
- Favorecer cortes sobre **yemas externas** para abrir la copa.  
- Buscar equilibrio lateral o distribución radial (vista aérea).

### 🌿 Mantenimiento
- Quitar ≤ 20% de la masa vegetal.  
- Eliminar ramas secas, dañadas o que se crucen.  
- Mantener la forma y el tamaño del ejemplar.

La app muestra una **puntuación sobre 100**, mensajes explicativos y consejos visuales.

---

## 🧠 Tecnologías

- **HTML5 + TailwindCSS CDN**  
- **JavaScript puro (sin frameworks)**  
- **SVG dinámico para visualización de ramas y yemas**

---

## 🧩 Estructura del proyecto

```
/pruning-simulator
│
├── index.html      # App principal (HTML + JS + CSS integrado)
├── LICENSE         # MIT License
└── README.md       # Este archivo
```

---

## 📜 Licencia

Este proyecto se distribuye bajo licencia **MIT**, lo que permite su libre uso, modificación y distribución con atribución.

---

## 💡 Autor

Desarrollado por [wherePANDA](https://github.com/wherePANDA)  
Proyecto didáctico para visualización y evaluación de poda 🌳
