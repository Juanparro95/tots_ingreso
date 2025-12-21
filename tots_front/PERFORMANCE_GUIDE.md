# ⚡ TOTS - Guía de Performance & Optimización

## 📊 Índice
1. [CSS Optimization](#css-optimization)
2. [Image Optimization](#image-optimization)
3. [Performance Tips](#performance-tips)
4. [Accessibility](#accessibility)
5. [SEO Optimization](#seo-optimization)

---

## CSS Optimization

### 1. Minimize CSS Bundle
```css
/* ✅ BIEN - Usar variables CSS para temas */
:root {
  --primary-color: #6366f1;
  --text-color: #0f172a;
}

button {
  background: var(--primary-color);
  color: var(--text-color);
}

/* ❌ MAL - Repetir colores */
button {
  background: #6366f1;
  color: #0f172a;
}

.card {
  background: #6366f1;
  color: #0f172a;
}
```

### 2. Utility Classes
```css
/* ✅ Usar clases de utilidad */
<div class="p-md shadow-lg rounded-lg">Content</div>

/* ❌ Escribir CSS adicional */
<div class="custom-box">Content</div>

/* CSS que se repite */
.custom-box {
  padding: 1rem;
  box-shadow: var(--shadow-lg);
  border-radius: 0.75rem;
}
```

### 3. Critical CSS
```html
<!-- Inline critical CSS in <head> -->
<style>
  /* Only essential styles for initial render */
  body { margin: 0; }
  .navbar { background: white; }
  .hero { min-height: 100vh; }
</style>

<!-- Defer non-critical CSS -->
<link rel="preload" href="styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
```

---

## Image Optimization

### 1. Responsive Images
```html
<!-- ✅ BIEN - Usar srcset -->
<img 
  src="image-lg.jpg"
  srcset="image-sm.jpg 640w, image-md.jpg 1024w, image-lg.jpg 1536w"
  sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
  alt="Descripción clara de la imagen"
  loading="lazy">

<!-- ❌ MAL - Una sola imagen grande -->
<img src="image-4k.jpg" alt="image">
```

### 2. WebP Format
```html
<picture>
  <source srcset="image.webp" type="image/webp">
  <source srcset="image.jpg" type="image/jpeg">
  <img src="image.jpg" alt="Descripción">
</picture>
```

### 3. Image Compression
```bash
# Optimizar imágenes con ImageMagick
convert image.jpg -quality 80 -strip image-optimized.jpg

# Convertir a WebP
cwebp -q 80 image.jpg -o image.webp
```

### 4. Background Images
```css
/* ✅ BIEN - Usar gradientes en lugar de imágenes */
background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);

/* ❌ MAL - Imagen grande no optimizada */
background-image: url('bg-unoptimized.jpg');
```

---

## Performance Tips

### 1. Font Loading
```css
/* ✅ BIEN - Especificar display property */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;700&display=swap');

/* ❌ MAL - Sin display property */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;700');
```

### 2. Minimize Animations
```css
/* ✅ BIEN - Cortas y suaves */
.button {
  transition: all 0.15s ease;
}

.button:hover {
  transform: translateY(-2px);
}

/* ❌ MAL - Animaciones largas y complejas */
.button {
  animation: spin 5s linear infinite;
}
```

### 3. Reduce Motion
```css
/* ✅ Respetar preferencias de usuario */
@media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }
}
```

### 4. CSS Containment
```css
/* ✅ Aislar componentes para mejor performance */
.card {
  contain: layout style paint;
}

.card-item {
  contain: content;
}
```

---

## Angular Performance

### 1. Change Detection Strategy
```typescript
import { ChangeDetectionStrategy } from '@angular/core';

@Component({
  selector: 'app-card',
  template: `...`,
  changeDetection: ChangeDetectionStrategy.OnPush  // ✅ Mejor performance
})
export class CardComponent {
  @Input() data: any;
}
```

### 2. Lazy Load Modules
```typescript
// ✅ BIEN - Lazy loading
const routes: Routes = [
  {
    path: 'spaces',
    loadComponent: () => import('./spaces/spaces.component')
      .then(m => m.SpacesComponent)
  }
];

// ❌ MAL - Todo cargado al inicio
import { SpacesComponent } from './spaces/spaces.component';
```

### 3. Virtual Scrolling
```html
<!-- ✅ BIEN - Para listas grandes -->
<cdk-virtual-scroll-viewport itemSize="100" class="h-96">
  <div *cdkVirtualFor="let item of items">
    {{ item }}
  </div>
</cdk-virtual-scroll-viewport>

<!-- ❌ MAL - Renderizar todos los items -->
<div *ngFor="let item of items">{{ item }}</div>
```

### 4. TrackBy Function
```typescript
// ✅ BIEN - Optimizar *ngFor
trackBySpaceId(index: number, space: Space): number {
  return space.id;
}

// En template
<div *ngFor="let space of spaces; trackBy: trackBySpaceId">
  {{ space.name }}
</div>

// ❌ MAL - Sin trackBy
<div *ngFor="let space of spaces">
  {{ space.name }}
</div>
```

---

## Accessibility (A11y)

### 1. Semantic HTML
```html
<!-- ✅ BIEN - Usar elementos semánticos -->
<nav>
  <a href="/spaces">Espacios</a>
</nav>

<main>
  <article>
    <h1>Título Principal</h1>
    <p>Contenido</p>
  </article>
</main>

<!-- ❌ MAL - Divs para todo -->
<div class="navbar">
  <div class="link">Espacios</div>
</div>
```

### 2. ARIA Labels
```html
<!-- ✅ BIEN -->
<button aria-label="Cerrar menú" (click)="closeMenu()">
  <i class="pi pi-times"></i>
</button>

<nav aria-label="Main navigation">
  ...
</nav>

<!-- ❌ MAL -->
<button (click)="closeMenu()">×</button>
```

### 3. Color Contrast
```css
/* ✅ BIEN - Suficiente contraste (4.5:1) */
color: #0f172a;      /* Dark */
background: #ffffff;  /* White */

/* ❌ MAL - Contraste insuficiente (3:1) */
color: #94a3b8;      /* Light gray */
background: #e2e8f0; /* Lighter gray */
```

### 4. Keyboard Navigation
```html
<!-- ✅ BIEN - Accesible por teclado -->
<a href="/login" tabindex="0">Iniciar Sesión</a>

<button 
  pButton 
  (click)="action()"
  (keydown.enter)="action()"
  (keydown.space)="action()">
  Acción
</button>

<!-- ❌ MAL - Solo con mouse -->
<div (click)="action()">Acción</div>
```

### 5. Form Labels
```html
<!-- ✅ BIEN - Label vinculado -->
<label for="email">Email</label>
<input id="email" type="email" required>

<!-- ❌ MAL - Sin label -->
<input type="email" placeholder="Email" required>
```

---

## SEO Optimization

### 1. Meta Tags
```html
<!-- En index.html -->
<head>
  <title>TOTS - Reserva Espacios para tus Eventos</title>
  <meta name="description" content="Plataforma de reserva de espacios para eventos, conferencias y reuniones. Más de 100 espacios disponibles.">
  <meta name="keywords" content="espacios, eventos, reservas, salas de conferencia">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Open Graph -->
  <meta property="og:title" content="TOTS - Reserva de Espacios">
  <meta property="og:description" content="Plataforma de reserva de espacios...">
  <meta property="og:image" content="https://tots.com/og-image.jpg">
  <meta property="og:url" content="https://tots.com">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="TOTS - Reserva de Espacios">
  <meta name="twitter:description" content="...">
  <meta name="twitter:image" content="https://tots.com/og-image.jpg">
</head>
```

### 2. Structured Data
```html
<!-- JSON-LD para Google -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "TOTS - Reserva de Espacios",
  "description": "Plataforma de reserva de espacios",
  "url": "https://tots.com",
  "image": "https://tots.com/logo.png",
  "offers": {
    "@type": "AggregateOffer",
    "priceCurrency": "USD",
    "lowPrice": "100",
    "highPrice": "500"
  }
}
</script>
```

### 3. Robots & Sitemap
```txt
<!-- robots.txt -->
User-agent: *
Allow: /
Disallow: /admin
Disallow: /api

Sitemap: https://tots.com/sitemap.xml
```

### 4. Canonical URLs
```html
<!-- Evitar contenido duplicado -->
<link rel="canonical" href="https://tots.com/spaces">
```

---

## Lighthouse Audit Checklist

- [ ] **Performance**: > 90
  - Optimize images
  - Minify CSS/JS
  - Use lazy loading
  
- [ ] **Accessibility**: > 90
  - Fix color contrast
  - Add ARIA labels
  - Test keyboard navigation

- [ ] **Best Practices**: > 90
  - Use HTTPS
  - Update dependencies
  - No console errors

- [ ] **SEO**: > 90
  - Meta descriptions
  - Structured data
  - Mobile friendly

---

## WebVitals Targets

```
Metric                Target
LCP (Largest Paint)   < 2.5s
FID (First Input)     < 100ms
CLS (Cumulative)      < 0.1
```

### Monitor with Google Analytics
```typescript
// In main.ts
import { getAnalytics, logEvent } from 'firebase/analytics';

declare var gtag: Function;

export function reportWebVitals(metric: any) {
  gtag('event', metric.name, {
    value: Math.round(metric.value),
    event_category: 'Web Vitals',
    event_label: metric.id,
    non_interaction: true
  });
}
```

---

## Development Tips

### 1. Production Build
```bash
# Optimized build
ng build --configuration production

# With stats
ng build --configuration production --stats-json
```

### 2. Analyze Bundle
```bash
npm install -g webpack-bundle-analyzer

# Analyze
webpack-bundle-analyzer dist/tots_front/stats.json
```

### 3. Performance Testing
```bash
# Lighthouse CLI
npm install -g @lhci/cli@latest

# Run audit
lhci autorun
```

---

## Checklist de Optimización Final

### CSS
- [ ] Minified en producción
- [ ] Critical CSS inlined
- [ ] Unused CSS removed
- [ ] Variables CSS implementadas

### Images
- [ ] WebP format usado
- [ ] srcset definidos
- [ ] Lazy loading activado
- [ ] Alt text presente

### Performance
- [ ] LCP < 2.5s
- [ ] FID < 100ms
- [ ] CLS < 0.1
- [ ] TTL < 3s

### Accessibility
- [ ] WCAG 2.1 AA passed
- [ ] Keyboard navigable
- [ ] Screen reader tested
- [ ] Color contrast OK

### SEO
- [ ] Meta tags implementados
- [ ] Structured data added
- [ ] Sitemap.xml created
- [ ] robots.txt configured

---

**Última actualización**: December 20, 2025  
**Versión**: 1.0.0
