# Asian Bites Homepage (WordPress + Elementor)

## Opción técnica elegida
Se implementó **Opción 1 (plugin `asianbites-home`)** porque:
1. Permite cargar CSS/JS **solo en homepage** (`is_front_page`) para proteger Core Web Vitals.
2. Mantiene la home modular con shortcodes reutilizables dentro de Elementor (sin tocar theme files).
3. Simplifica mantenimiento y despliegue (activar/desactivar plugin sin riesgo para otras plantillas).

## Estructura
- `asianbites-home/asianbites-home.php`
- `asianbites-home/assets/css/home.css`
- `asianbites-home/assets/js/home.js`

## Instalación
1. Copia la carpeta `asianbites-home` dentro de `wp-content/plugins/`.
2. Activa el plugin en `WP Admin > Plugins`.
3. Ve a `Páginas > Inicio` y edita con Elementor.
4. Inserta un widget **Shortcode** con: `[ab_home]`.
5. Si prefieres control por bloques, usa los shortcodes por sección:
   - `[ab_home_hero]`
   - `[ab_home_value]`
   - `[ab_home_categories]`
   - `[ab_home_best_sellers]`
   - `[ab_home_how]`
   - `[ab_home_testimonials]`
   - `[ab_home_about]`
   - `[ab_home_faq]`
   - `[ab_home_final_cta]`

## Edición sin código
- Textos y links de Hero/CTA/Categorías se editan con atributos del shortcode.
- Ejemplo Hero:
  ```
  [ab_home_hero title="Abre, sirve, sigue." cta_primary_url="/tienda" hero_image_id="123"]
  ```
- Para imagen hero usa:
  - `hero_image_id` (ID de Media Library, recomendado por srcset automático).
  - o `hero_image_url` + `hero_srcset` + `hero_sizes` si necesitas control manual.

## SEO recomendado
- Meta title sugerido: `Asian Bites Bogotá | Boxes y suscripción de comida asiática`
- Meta description sugerida: `Abre, sirve, sigue. Pide boxes asiáticas con delivery rápido en Bogotá o suscríbete mensual. Sin protocolos. Sin complicaciones.`
- El plugin inyecta JSON-LD básico: `Organization` + `WebSite` con `SearchAction`.
- Si usas Yoast/RankMath, configura OG/Twitter desde su UI y desactiva salida duplicada si aplica.

## QA / CWV
- LCP: hero image eager + fetchpriority high + width/height + aspect-ratio.
- CLS: layout del hero reservado con dimensiones y grilla estable.
- INP: JS mínimo con `IntersectionObserver`, sin jQuery.
- Accesibilidad: estructura semántica, un solo H1, FAQ con `<details><summary>`.

## Compatibilidad de imagen hero moderna
- Usa WebP/AVIF desde Media Library y tamaños responsivos de WP.
- Si manejas CDN con conversión automática, conserva `hero_image_id` para beneficio total.
