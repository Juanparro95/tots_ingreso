# 📚 TOTS - Recursos y Referencias

## 🎯 Tabla Rápida de Consulta

### Colores Principales
| Uso | Color | Hex | Variable |
|-----|-------|-----|----------|
| Primario | Indigo | #6366f1 | `--primary-color` |
| Primario Claro | Indigo Light | #818cf8 | `--primary-light` |
| Primario Oscuro | Indigo Dark | #4f46e5 | `--primary-dark` |
| Secundario | Pink | #ec4899 | `--secondary-color` |
| Éxito | Green | #10b981 | `--success-color` |
| Error | Red | #ef4444 | `--danger-color` |
| Advertencia | Amber | #f59e0b | `--warning-color` |
| Información | Blue | #3b82f6 | `--info-color` |

### Tipografía
| Elemento | Fuente | Peso | Tamaño |
|----------|--------|------|--------|
| h1 | Outfit | 800 | 3rem |
| h2 | Outfit | 800 | 2rem |
| h3 | Outfit | 700 | 1.5rem |
| p | Sora | 400 | 1rem |
| small | Sora | 400 | 0.875rem |

### Espaciado
| Clase | Valor |
|-------|-------|
| p-xs / m-xs | 0.25rem |
| p-sm / m-sm | 0.5rem |
| p-md / m-md | 1rem |
| p-lg / m-lg | 1.5rem |
| p-xl / m-xl | 2rem |
| p-2xl / m-2xl | 2.5rem |

### Sombras
| Clase | Uso |
|-------|-----|
| shadow-xs | Subtil |
| shadow-sm | Ligero |
| shadow-md | Normal |
| shadow-lg | Destacado |
| shadow-xl | Elevado |
| shadow-2xl | Máximo |

### Border Radius
| Clase | Valor |
|-------|-------|
| rounded-xs | 0.25rem |
| rounded-sm | 0.375rem |
| rounded-md | 0.5rem |
| rounded-lg | 0.75rem |
| rounded-xl | 1rem |
| rounded-2xl | 1.5rem |
| rounded-full | 9999px |

---

## 🎨 Paleta de Colores Completa

### Indigo (Primary)
```
#6366f1  ← Main
#818cf8  ← Light
#c7d2fe  ← Lighter
#4f46e5  ← Dark
#4338ca  ← Darker
```

### Pink (Secondary)
```
#ec4899  ← Main
#f472b6  ← Light
#be185d  ← Dark
```

### Green (Success)
```
#10b981  ← Main
#6ee7b7  ← Light
#059669  ← Dark
```

### Red (Danger)
```
#ef4444  ← Main
#f87171  ← Light
#dc2626  ← Dark
```

### Amber (Warning)
```
#f59e0b  ← Main
#fbbf24  ← Light
#d97706  ← Dark
```

### Blue (Info)
```
#3b82f6  ← Main
#60a5fa  ← Light
#2563eb  ← Dark
```

### Grays (Text & Background)
```
#0f172a  ← Text Dark
#475569  ← Text Secondary
#64748b  ← Text Light
#94a3b8  ← Text Lighter
#f8fafc  ← Surface Alt
#f1f5f9  ← Surface Secondary
#e2e8f0  ← Border
#cbd5e1  ← Border Light
```

---

## 📐 Sistema de Grid

### Card Grid (Automático)
```css
.card-grid {
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}
/* Resultado:
   320px-640px:  1 columna
   641px-1024px: 2 columnas
   1025px+:      3 columnas
*/
```

### Feature Grid
```css
.feature-grid {
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}
```

### Dos Columnas Responsivo
```css
grid-template-columns: 1fr;
@media (min-width: 768px) {
  grid-template-columns: repeat(2, 1fr);
}
```

### Tres Columnas Responsivo
```css
grid-template-columns: 1fr;
@media (min-width: 768px) {
  grid-template-columns: repeat(2, 1fr);
}
@media (min-width: 1024px) {
  grid-template-columns: repeat(3, 1fr);
}
```

---

## 🎬 Animaciones Disponibles

### Entrada
```css
slideInRight  /* Entra desde derecha */
slideInLeft   /* Entra desde izquierda */
slideInUp     /* Entra desde abajo */
slideInDown   /* Entra desde arriba */
scaleIn       /* Crece desde pequeño */
fadeIn        /* Desvanece desde invisible */
```

### Movimiento
```css
float         /* Flota arriba/abajo */
bounce        /* Rebota */
pulse         /* Pulsación suave */
shimmer       /* Efecto brillo (loading) */
spin          /* Gira (loader) */
```

### Aplicar Animaciones
```html
<!-- Entra en la página -->
<div class="fade-enter">Contenido</div>
<div class="slide-enter">Contenido</div>
<div class="scale-enter">Contenido</div>

<!-- En CSS personalizado -->
<style>
  .my-element {
    animation: slideInUp 0.6s ease-out;
  }
</style>
```

---

## 🎯 Componentes PrimeNG Compatibles

### Buttons
```html
<button pButton type="button" label="Text" icon="pi pi-check"></button>
<button pButton type="button" label="Primary" class="p-button-primary"></button>
<button pButton type="button" label="Outlined" class="p-button-outlined"></button>
<button pButton type="button" icon="pi pi-plus" [rounded]="true"></button>
<button pButton type="button" icon="pi pi-times" severity="danger"></button>
```

### Forms
```html
<input pInputText type="text" placeholder="Buscar...">
<textarea pInputTextarea rows="4" placeholder="Ingresa texto..."></textarea>
<p-inputNumber formControlName="quantity" [min]="0"></p-inputNumber>
<p-checkbox [(ngModel)]="checked" [binary]="true" label="Opción"></p-checkbox>
<p-radioButton name="option" value="1" [(ngModel)]="selectedOption"></p-radioButton>
```

### Dropdowns
```html
<p-dropdown [options]="items" optionLabel="label" placeholder="Seleccionar"></p-dropdown>
<p-autocomplete [suggestions]="results" (onSearch)="search($event)"></p-autocomplete>
<p-multiSelect [options]="items" optionLabel="label" placeholder="Seleccionar"></p-multiSelect>
```

### Tablas
```html
<p-dataTable [value]="data" [paginator]="true" [rows]="10">
  <p-column field="name" header="Nombre"></p-column>
  <p-column field="email" header="Email"></p-column>
</p-dataTable>
```

### Diálogos
```html
<p-dialog [(visible)]="show" header="Título" [modal]="true" [style]="{width: '50vw'}">
  Contenido del diálogo
</p-dialog>
```

### Notificaciones
```html
<p-toast position="top-right"></p-toast>

<!-- En TypeScript -->
this.messageService.add({
  severity: 'success',
  summary: 'Éxito',
  detail: 'Operación completada'
});
```

### Otras
```html
<p-card>Tarjeta</p-card>
<p-panel header="Encabezado">Contenido</p-panel>
<p-accordion>Acordeón</p-accordion>
<p-tabs>Pestañas</p-tabs>
<p-progressBar [value]="50"></p-progressBar>
<p-rating [(ngModel)]="value" [readonly]="false"></p-rating>
```

---

## 🔗 Enlaces Útiles

### Documentación Oficial
- [PrimeNG](https://primeng.org) - Componentes UI
- [Angular](https://angular.io) - Framework
- [Tailwind CSS](https://tailwindcss.com) - Utilidades CSS
- [Google Fonts](https://fonts.google.com) - Fuentes

### Herramientas de Diseño
- [Figma](https://figma.com) - Prototipado
- [Coolors](https://coolors.co) - Paleta de colores
- [Font Pair](https://www.fontpair.co) - Combinación de fuentes
- [Unsplash](https://unsplash.com) - Imágenes libres

### Performance & SEO
- [Google Lighthouse](https://developers.google.com/web/tools/lighthouse) - Auditoría
- [PageSpeed Insights](https://pagespeed.web.dev) - Performance
- [GTmetrix](https://gtmetrix.com) - Análisis
- [Web.dev](https://web.dev) - Guías

### Accesibilidad
- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref) - Estándares
- [Axe DevTools](https://www.deque.com/axe/devtools) - Testing
- [Lighthouse A11y](https://developers.google.com/web/tools/lighthouse) - Auditoría
- [Contrast Checker](https://webaim.org/resources/contrastchecker) - Colores

---

## 📱 Responsive Cheatsheet

### Mobile First
```css
/* Base (mobile) */
.element { width: 100%; }

/* Tablet */
@media (min-width: 641px) {
  .element { width: 50%; }
}

/* Desktop */
@media (min-width: 1025px) {
  .element { width: 33.333%; }
}
```

### Clases Útiles
```html
<!-- Hide/Show por dispositivo -->
<div class="hide-mobile">Solo desktop</div>
<div class="hide-tablet">No tablet</div>
<div class="hide-desktop">Solo mobile/tablet</div>

<!-- Flex responsive -->
<div class="flex-between flex-col-mobile">
  <div>Izquierda</div>
  <div>Derecha</div>
</div>

<!-- Grid responsive -->
<div class="card-grid">
  <!-- Auto-responsive: 1-2-3 columnas -->
</div>
```

---

## 🎓 Patrones Comunes

### Button con Icono
```html
<button class="p-button p-button-primary">
  <i class="pi pi-check mr-2"></i>Confirmar
</button>
```

### Card con Imagen
```html
<div class="p-card hover-lift">
  <div class="p-card-header">
    <img src="image.jpg" alt="Descripción" class="w-full h-48 object-cover">
  </div>
  <div class="p-card-content">Contenido</div>
</div>
```

### Form Group
```html
<div class="form-group">
  <label class="form-label">Campo</label>
  <input class="p-inputtext w-full" type="text">
  <small class="form-help">Texto de ayuda</small>
</div>
```

### Status Badge
```html
<span class="badge-status badge-active">
  <span class="badge-dot"></span>
  Activo
</span>
```

### Feature Item
```html
<div class="feature-item">
  <div class="feature-icon">🎯</div>
  <div class="feature-number">100+</div>
  <h3 class="card-title">Título</h3>
  <p class="card-description">Descripción</p>
</div>
```

### Testimonial
```html
<div class="testimonial-card">
  <p class="testimonial-text">"Cita aquí"</p>
  <div class="testimonial-author">
    <div class="testimonial-avatar">JD</div>
    <div class="testimonial-info">
      <h4>Nombre</h4>
      <p>Cargo</p>
    </div>
  </div>
</div>
```

---

## ⚙️ Variables CSS Personalizables

### Cambiar Color Primario
```css
:root {
  --primary-color: #7c3aed;    /* Purple */
  --primary-light: #a78bfa;
  --primary-dark: #6d28d9;
}
```

### Cambiar Tipografía
```css
:root {
  --font-display: 'Playfair Display', serif;
  --font-body: 'Lato', sans-serif;
}
```

### Cambiar Radios
```css
:root {
  --radius-lg: 1rem;      /* Aumenta */
  --radius-xl: 1.5rem;
  --radius-2xl: 2rem;
}
```

---

## 🔍 Debugging CSS

### Ver Variables
```javascript
// En la consola del navegador
const style = getComputedStyle(document.documentElement);
console.log(style.getPropertyValue('--primary-color'));
```

### Inspeccionar Elemento
```html
<!-- Click derecho → Inspeccionar elemento -->
<!-- Ver estilos aplicados -->
<!-- Probar cambios en tiempo real -->
```

### Media Queries
```javascript
// Verificar breakpoint actual
window.innerWidth  // Ancho actual
window.matchMedia('(min-width: 1024px)').matches  // ¿Es desktop?
```

---

## 📊 Métricas de Diseño

### Espaciado Base
```
4px = 0.25rem (xs)
8px = 0.5rem  (sm)
16px = 1rem   (md)
24px = 1.5rem (lg)
32px = 2rem   (xl)
```

### Transiciones
```
150ms = Rápido (hover)
300ms = Normal (cambios de estado)
500ms = Lento (entrada de página)
```

### Z-Index
```
10    = Navbar
100   = Dropdowns
1000  = Modales
9999  = Toast
```

---

## ✨ Tips & Tricks

### 1. Combinar Clases
```html
<div class="p-card shadow-lg hover-lift rounded-xl">
  Combina múltiples utilidades
</div>
```

### 2. Responsive Text
```html
<!-- Tipografía fluida -->
<h1 style="font-size: clamp(1.5rem, 5vw, 3rem)">
  Título que se ajusta automáticamente
</h1>
```

### 3. Gradientes
```html
<div class="gradient-primary">
  Fondo con gradiente automático
</div>
```

### 4. Truncar Texto
```html
<!-- Una línea -->
<p class="text-truncate">Texto largo que se corta...</p>

<!-- Dos líneas -->
<p class="text-clamp-2">Texto que se corta después de 2 líneas...</p>

<!-- Tres líneas -->
<p class="text-clamp-3">Texto que se corta después de 3 líneas...</p>
```

### 5. Efecto Hover
```html
<!-- Eleva el elemento -->
<div class="hover-lift">Hover me!</div>

<!-- Crece el elemento -->
<div class="hover-grow">Hover me!</div>

<!-- Agrega sombra -->
<div class="hover-shadow">Hover me!</div>
```

---

## 🚨 Errores Comunes

### ❌ Padding en lugar de Margin
```css
/* MAL */
.element { padding: 2rem; margin: 2rem; }

/* BIEN */
.element { padding: 1rem; margin-bottom: 1rem; }
```

### ❌ Espacios inconsistentes
```css
/* MAL */
.button { padding: 8px 12px; }
.card { padding: 16px 18px; }

/* BIEN - Usar variables */
.button { padding: var(--space-sm) var(--space-md); }
.card { padding: var(--space-md); }
```

### ❌ Colores hardcodeados
```css
/* MAL */
.button { background: #6366f1; color: #0f172a; }

/* BIEN - Usar variables */
.button { background: var(--primary-color); color: var(--text-color); }
```

### ❌ Animaciones siempre activas
```css
/* MAL */
* { transition: all 0.3s; }  /* Ralenta todo */

/* BIEN - Selectivo */
.button { transition: all var(--transition-base); }

/* Mejor - Respetar preferencias */
@media (prefers-reduced-motion: reduce) {
  * { animation: none !important; transition: none !important; }
}
```

---

## 📞 Soporte Rápido

| Pregunta | Respuesta |
|----------|-----------|
| ¿Cómo cambio el color primario? | Edita `--primary-color` en `:root` |
| ¿Cómo agrego más sombras? | Copia `--shadow-lg` y ajusta los valores |
| ¿Cómo hago responsive? | Usa `card-grid` o media queries |
| ¿Cómo animó elementos? | Usa clases `.fade-enter`, `.slide-enter`, etc |
| ¿Cómo cambio la tipografía? | Edita `--font-display` y `--font-body` |

---

**Versión**: 1.0.0  
**Última actualización**: December 20, 2025  
**Estado**: ✅ Completo

**¡Feliz diseño!** 🎨
