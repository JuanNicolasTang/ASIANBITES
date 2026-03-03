# Asian Bites Homepage (WordPress + Elementor)

## Opción técnica elegida
Se implementó **Opción 1 (plugin `asianbites-home`)** porque:
1. Permite cargar CSS/JS solo en contexto de home usando `ab_home_should_enqueue` (por defecto `is_front_page()`).
2. Mantiene la home modular con shortcodes reutilizables dentro de Elementor (sin tocar archivos del theme).
3. Simplifica mantenimiento/despliegue y aísla el comportamiento del home en un plugin.
4. Usa templates PHP (`templates/*.php`) en vez de strings HTML gigantes para mejorar mantenibilidad.

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

### Modos de estilo opcionales
- Brand mode (paleta/estilo marca opt-in):
  ```
  [ab_home brand="1"]
  ```
- Dark mode (opt-in):
  ```
  [ab_home dark="1"]
  ```
- Puedes combinar ambos:
  ```
  [ab_home brand="1" dark="1"]
  ```

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

Además, el plugin evita registrar `wp_head` para esta salida cuando Rank Math está activo y no hay force flags, reduciendo riesgo de duplicados.

> Recomendación: dejar OG/Twitter/schema en **Rank Math como fuente única**.

### Filtros de SEO
- `ab_home_output_meta` (bool, default `true`)
- `ab_home_output_schema` (bool, default `true`)
- `ab_home_force_output_meta` (bool, default `false`) → fuerza meta incluso con Rank Math.
- `ab_home_force_output_schema` (bool, default `false`) → fuerza schema incluso con Rank Math.

## Filtros de contenido overrideable
- `ab_home_value_items` → cards de propuesta de valor (`title`, `body`)
- `ab_home_how_steps` → pasos (`number`, `title`, `body`)
- `ab_home_testimonials` → testimonios (`quote`, `author`)
- `ab_home_faq_items` → FAQ (`q`, `a`)

Ejemplo:
```php
add_filter('ab_home_faq_items', static function ($items) {
    return [
        ['q' => '¿Entregan domingos?', 'a' => 'Sí, según cobertura y franja disponible.'],
        ['q' => '¿Puedo pausar suscripción?', 'a' => 'Sí, desde tu cuenta sin penalidad.'],
    ];
});
```

## Filtros de comportamiento
- `ab_home_should_enqueue` (bool, default `is_front_page()`) → controla en qué páginas encolar assets.
- `ab_home_enable_dark_mode_class` (bool) → fuerza `.ab-home--dark`.
- `ab_home_enable_brand_mode_class` (bool) → fuerza `.ab-home--brand`.

Ejemplo para soportar blog-home:
```php
add_filter('ab_home_should_enqueue', static function ($should) {
    return $should || is_home();
});
```

## Debug para QA
- Shortcode: `[ab_home_debug]`
- Solo visible para admins (`manage_options`).
- Muestra: detección de Rank Math, si el hook de head está registrado y estado final de meta/schema con force flags.

## QA / CWV
- LCP: hero con `loading="eager"`, `fetchpriority="high"`, `decoding="async"` y dimensiones.
- CLS: layout del hero reservado con dimensiones/aspect-ratio y grilla estable.
- INP: JS mínimo con `IntersectionObserver`, sin jQuery.
- Accesibilidad: estructura semántica, un solo H1, FAQ con `<details><summary>`.
