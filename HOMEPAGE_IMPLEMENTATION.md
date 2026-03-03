# Asian Bites Homepage (WordPress + Elementor)

## Opción técnica elegida
Se implementó **Opción 1 (plugin `asianbites-home`)** porque:
1. Permite cargar CSS/JS solo en contexto de home usando `ab_home_should_enqueue` (por defecto `is_front_page()`).
2. Mantiene la home modular con shortcodes reutilizables dentro de Elementor (sin tocar archivos del theme).
3. Simplifica mantenimiento/despliegue y aísla el comportamiento del home en un plugin.
4. Mejora mantenibilidad con templates PHP (`templates/*.php`) en vez de strings HTML gigantes en el controlador.

## Estructura
- `asianbites-home/asianbites-home.php`
- `asianbites-home/assets/css/home.css`
- `asianbites-home/assets/js/home.js`
- `asianbites-home/templates/*.php`

## Instalación
1. Copia la carpeta `asianbites-home` dentro de `wp-content/plugins/`.
2. Activa el plugin en `WP Admin > Plugins`.
3. Ve a `Páginas > Inicio` y edita con Elementor.
4. Inserta un widget **Shortcode** con: `[ab_home]`.
5. Si prefieres control por bloques, usa shortcodes por sección:
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
  - `hero_image_id` (ID de Media Library, recomendado por srcset automático de WordPress).
  - o `hero_image_url` + `hero_srcset` + `hero_sizes` para control manual.
- Si no configuras imagen hero, el plugin renderiza un placeholder interno (sin requests externos).

## Rank Math (SEO-neutral por defecto)
Si Rank Math está activo (`defined('RANK_MATH_VERSION')` o clase detectada), el plugin **no imprime** por defecto:
- OG meta
- Twitter meta
- JSON-LD (`Organization` + `WebSite` + `SearchAction`)

Esto evita duplicados SEO y conflictos de schema cuando Rank Math ya gestiona head/meta/schema.

### Filtros disponibles
- `ab_home_output_meta` (bool, default `true`)
- `ab_home_output_schema` (bool, default `true`)
- `ab_home_force_output_meta` (bool, default `false`) → fuerza meta incluso con Rank Math.
- `ab_home_force_output_schema` (bool, default `false`) → fuerza schema incluso con Rank Math.
- `ab_home_should_enqueue` (bool, default `is_front_page()`) → permite incluir otros contextos (ej. `is_home()`).

Ejemplo para soportar blog-home:
```php
add_filter('ab_home_should_enqueue', static function ($should) {
    return $should || is_home();
});
```

## Debug para QA
- Shortcode: `[ab_home_debug]`
- Solo visible para admins (`manage_options`).
- Muestra: detección de Rank Math + estado final de meta/schema y force flags.

## SEO recomendado
- Meta title sugerido: `Asian Bites Bogotá | Boxes y suscripción de comida asiática`
- Meta description sugerida: `Abre, sirve, sigue. Pide boxes asiáticas con delivery rápido en Bogotá o suscríbete mensual. Sin protocolos. Sin complicaciones.`
- Si usas Rank Math, configura OG/Twitter/schema desde Rank Math como fuente única.

## QA / CWV
- LCP: hero image con `loading="eager"`, `fetchpriority="high"`, `decoding="async"` y dimensiones.
- CLS: layout del hero reservado con dimensiones/aspect-ratio y grilla estable.
- INP: JS mínimo con `IntersectionObserver`, sin jQuery.
- Accesibilidad: estructura semántica, un solo H1, FAQ con `<details><summary>`.
