# Asian Bites Homepage (WordPress + Elementor)

## Uso rápido en Elementor

### Home completa
Usa un widget **Shortcode** con:

```txt
[ab_home]
```

### Secciones individuales
Si prefieres armar la landing por bloques dentro de Elementor:

```txt
[ab_home_hero]
[ab_home_value]
[ab_home_categories]
[ab_home_best_sellers]
[ab_home_how]
[ab_home_testimonials]
[ab_home_about]
[ab_home_faq]
[ab_home_final_cta]
```

> El plugin ahora también carga CSS/JS al editar en Elementor la página configurada como `page_on_front` y en `elementor-preview`, evitando la vista en blanco del editor para la home.

## Imágenes editables

### Hero (LCP)
En el hero usa `hero_image_id` (recomendado):

```txt
[ab_home_hero hero_image_id="123"]
```

También existe `hero_image_url` (manual), pero `hero_image_id` aprovecha `srcset` nativo de WordPress.

### Categorías (nuevas)
Cada tile de categorías acepta imagen opcional por ID:

- `cat_1_image_id`
- `cat_2_image_id`
- `cat_3_image_id`
- `cat_4_image_id`

Ejemplo:

```txt
[ab_home_categories
  cat_1_title="Soju Fresh"
  cat_1_url="/categoria-producto/soju-fresh"
  cat_1_image_id="301"
  cat_2_image_id="302"
  cat_3_image_id="303"
  cat_4_image_id="304"
]
```

Estas imágenes se renderizan con `wp_get_attachment_image` y `loading="lazy"`.

### Mascota “Mr. Abi” (nueva)
En About puedes mostrar sticker/mascota opcional:

```txt
[ab_home_about about_mascot_image_id="401"]
```

La imagen se renderiza sin deformarse (dimensiones naturales, `height:auto`, alt de Media Library).

## Global Colors / Fonts de Elementor (brandbook)

El CSS del plugin está scopeado a `.ab-home` y prioriza tokens globales de Elementor:

- Colores:
  - `--e-global-color-primary`
  - `--e-global-color-secondary`
  - `--e-global-color-accent`
  - `--e-global-color-text`
  - `--e-global-color-background`
- Tipografías:
  - `--e-global-typography-primary` (familia para títulos)
  - `--e-global-typography-secondary` (mensajes especiales)
  - `--e-global-typography-text` (cuerpo)
  - `--e-global-typography-accent` (si tu sistema lo usa para variantes)

Si un token no existe, el plugin cae a fallback de brandbook:

- Bubblegum `#FF4DA6`
- Carbon `#1B1B1E`
- Pearl `#FDF8F6`
- Fredoka (headings), Usagi (mensajes especiales), Work Sans (texto)

### Mapeo recomendado en Elementor (Site Settings)
- **Primary** → Carbon `#1B1B1E`
- **Secondary** → Pearl `#FDF8F6`
- **Accent** → Bubblegum `#FF4DA6`
- **Text** → Carbon `#1B1B1E`
- **Background** → Pearl `#FDF8F6`
- **Typography Primary** → Fredoka
- **Typography Secondary** → Usagi
- **Typography Text** → Work Sans

## Copy base alineado a marca (defaults)

Los defaults de Hero/Value/About/Categories ahora reflejan:

- foco actual en Soju (Fresh, Uva Verde, Durazno)
- tono “vida real” + planes
- mantra: “Abre, sirve, sigue. Sin protocolos. Sin complicaciones.”
- expansión futura a sopas/snacks/sake sin prometer fechas

## Modos opcionales
- Brand mode:
  ```txt
  [ab_home brand="1"]
  ```
- Dark mode:
  ```txt
  [ab_home dark="1"]
  ```

## Filtros útiles
- `ab_home_should_enqueue`
- `ab_home_value_items`
- `ab_home_how_steps`
- `ab_home_testimonials`
- `ab_home_faq_items`
