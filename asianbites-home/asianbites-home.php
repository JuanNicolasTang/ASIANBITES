<?php
/**
 * Plugin Name: Asian Bites Home
 * Description: Homepage modular para Asian Bites con shortcodes optimizados para Elementor + WooCommerce.
 * Version: 1.4.0
 * Author: Asian Bites
 * Requires at least: 6.5
 * Requires PHP: 8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

final class AsianBites_Home {
    private const HANDLE = 'asianbites-home';
    private const DEFAULT_HERO_SIZES = '(max-width: 767px) 100vw, 50vw';

    public static function init(): void {
        add_action('init', [__CLASS__, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets_for_elementor_editor']);

        if (self::should_register_head_hook()) {
            add_action('wp_head', [__CLASS__, 'output_meta_and_schema'], 20);
        }
    }

    public static function register_shortcodes(): void {
        add_shortcode('ab_home', [__CLASS__, 'render_home']);
        add_shortcode('ab_home_hero', [__CLASS__, 'render_hero']);
        add_shortcode('ab_home_value', [__CLASS__, 'render_value']);
        add_shortcode('ab_home_categories', [__CLASS__, 'render_categories']);
        add_shortcode('ab_home_best_sellers', [__CLASS__, 'render_best_sellers']);
        add_shortcode('ab_home_how', [__CLASS__, 'render_how']);
        add_shortcode('ab_home_testimonials', [__CLASS__, 'render_testimonials']);
        add_shortcode('ab_home_about', [__CLASS__, 'render_about']);
        add_shortcode('ab_home_faq', [__CLASS__, 'render_faq']);
        add_shortcode('ab_home_final_cta', [__CLASS__, 'render_final_cta']);
        add_shortcode('ab_home_debug', [__CLASS__, 'render_debug']);
    }

    private static function should_load_home_assets(): bool {
        $should_enqueue_front = (bool) apply_filters('ab_home_should_enqueue', is_front_page());

        return $should_enqueue_front || self::is_elementor_front_page_preview_request();
    }

    private static function is_elementor_front_page_preview_request(): bool {
        $front_page_id = (int) get_option('page_on_front');

        if (0 === $front_page_id) {
            return false;
        }

        $elementor_preview_id = isset($_GET['elementor-preview']) ? absint(wp_unslash((string) $_GET['elementor-preview'])) : 0;

        return $elementor_preview_id === $front_page_id;
    }

    private static function is_elementor_front_page_editor_request(): bool {
        if (!is_admin()) {
            return false;
        }

        $front_page_id = (int) get_option('page_on_front');
        if (0 === $front_page_id) {
            return false;
        }

        $action = isset($_GET['action']) ? sanitize_key(wp_unslash((string) $_GET['action'])) : '';
        $post_id = isset($_GET['post']) ? absint(wp_unslash((string) $_GET['post'])) : 0;

        return 'elementor' === $action && $post_id === $front_page_id;
    }

    public static function enqueue_assets(): void {
        if (!self::should_load_home_assets()) {
            return;
        }

        self::enqueue_home_assets();
    }

    public static function enqueue_assets_for_elementor_editor(): void {
        if (!self::is_elementor_front_page_editor_request()) {
            return;
        }

        self::enqueue_home_assets();
    }

    private static function enqueue_home_assets(): void {
        if (wp_style_is(self::HANDLE, 'enqueued')) {
            return;
        }

        $base_url  = plugin_dir_url(__FILE__);
        $base_path = plugin_dir_path(__FILE__);

        wp_enqueue_style(
            self::HANDLE,
            $base_url . 'assets/css/home.css',
            [],
            (string) filemtime($base_path . 'assets/css/home.css')
        );

        wp_add_inline_style(self::HANDLE, self::critical_hero_css());

        wp_enqueue_script(
            self::HANDLE,
            $base_url . 'assets/js/home.js',
            [],
            (string) filemtime($base_path . 'assets/js/home.js'),
            true
        );
    }

    public static function render_home(array $atts = []): string {
        $atts = shortcode_atts([
            'dark' => '0',
            'brand' => '0',
        ], $atts, 'ab_home');

        $classes = ['ab-home'];
        if ('1' === (string) $atts['dark'] || (bool) apply_filters('ab_home_enable_dark_mode_class', false)) {
            $classes[] = 'ab-home--dark';
        }

        if ('1' === (string) $atts['brand'] || (bool) apply_filters('ab_home_enable_brand_mode_class', false)) {
            $classes[] = 'ab-home--brand';
        }

        $sections = [
            self::render_hero(),
            self::render_value(),
            self::render_categories(),
            self::render_best_sellers(),
            self::render_how(),
            self::render_testimonials(),
            self::render_about(),
            self::render_faq(),
            self::render_final_cta(),
        ];

        return self::render_template('home-wrapper', [
            'classes'  => implode(' ', array_unique($classes)),
            'sections' => implode("\n", array_filter($sections)),
        ]);
    }

    public static function render_hero(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Abre, sirve, sigue.',
            'subtitle' => 'Soju Fresh, Uva Verde y Durazno para tus planes de vida real. Frío, fácil y listo para compartir.',
            'cta_primary_text' => 'Pedir Soju ahora',
            'cta_primary_url' => '/tienda',
            'cta_secondary_text' => 'Ver todos los sabores',
            'cta_secondary_url' => '/categoria-producto/soju',
            'chip_1' => 'Especialistas en Soju: Fresh, Uva Verde y Durazno',
            'chip_2' => 'Abre, sirve, sigue. Sin protocolos. Sin complicaciones.',
            'chip_3' => 'Próximamente: sopas, snacks y sake',
            'hero_image_id' => '',
            'hero_image_url' => '',
            'hero_alt' => 'Box Asian Bites abierto con productos asiáticos',
            'hero_srcset' => '',
            'hero_sizes' => self::DEFAULT_HERO_SIZES,
            'hero_width' => '960',
            'hero_height' => '1080',
        ], $atts, 'ab_home_hero');

        return self::render_template('hero', [
            'title' => sanitize_text_field($atts['title']),
            'subtitle' => sanitize_text_field($atts['subtitle']),
            'cta_primary_text' => sanitize_text_field($atts['cta_primary_text']),
            'cta_primary_url' => esc_url_raw($atts['cta_primary_url']),
            'cta_secondary_text' => sanitize_text_field($atts['cta_secondary_text']),
            'cta_secondary_url' => esc_url_raw($atts['cta_secondary_url']),
            'chip_1' => sanitize_text_field($atts['chip_1']),
            'chip_2' => sanitize_text_field($atts['chip_2']),
            'chip_3' => sanitize_text_field($atts['chip_3']),
            'image_html' => self::build_hero_media($atts),
        ]);
    }

    private static function build_hero_media(array $atts): string {
        $validated_sizes = self::validate_responsive_attr((string) $atts['hero_sizes'], self::DEFAULT_HERO_SIZES);

        if (!empty($atts['hero_image_id'])) {
            return (string) wp_get_attachment_image(
                (int) $atts['hero_image_id'],
                'large',
                false,
                [
                    'class' => 'ab-hero__image',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'decoding' => 'async',
                    'sizes' => $validated_sizes,
                    'alt' => esc_attr((string) $atts['hero_alt']),
                ]
            );
        }

        if (!empty($atts['hero_image_url'])) {
            return self::render_template('hero-image', [
                'hero_image_url' => esc_url_raw($atts['hero_image_url']),
                'hero_srcset' => self::validate_responsive_attr((string) $atts['hero_srcset'], ''),
                'hero_sizes' => $validated_sizes,
                'hero_alt' => sanitize_text_field((string) $atts['hero_alt']),
                'hero_width' => (int) $atts['hero_width'],
                'hero_height' => (int) $atts['hero_height'],
            ]);
        }

        return '<div class="ab-hero__placeholder" role="img" aria-label="Placeholder visual editable del hero de Asian Bites"></div>';
    }

    private static function validate_responsive_attr(string $value, string $fallback): string {
        $value = trim($value);
        if ('' === $value) {
            return $fallback;
        }

        if (1 !== preg_match('/^[A-Za-z0-9\s,\.\%wx\(\)\-\/:]+$/', $value)) {
            return $fallback;
        }

        return $value;
    }

    public static function render_value(array $atts = []): string {
        $default_items = [
            ['title' => 'Soju para planes reales', 'body' => 'Fresh, Uva Verde y Durazno listos para prender cualquier parche.'],
            ['title' => 'Rápido y sin vueltas', 'body' => 'Compra fácil, entrega ágil y cero protocolos para disfrutar ya.'],
            ['title' => 'Sabor confiable', 'body' => 'Curamos productos que sí cumplen: chill, accesibles y de calidad.'],
            ['title' => 'Lo que viene', 'body' => 'Estamos preparando nuevas líneas: sopas, snacks y sake.'],
        ];

        return self::render_template('value', [
            'items' => self::normalize_items((array) apply_filters('ab_home_value_items', $default_items), ['title', 'body']),
        ]);
    }

    public static function render_categories(array $atts = []): string {
        $atts = shortcode_atts([
            'cat_1_title' => 'Soju Fresh',
            'cat_1_url' => '/categoria-producto/soju-fresh',
            'cat_1_image_id' => '',
            'cat_2_title' => 'Soju Uva Verde',
            'cat_2_url' => '/categoria-producto/soju-uva-verde',
            'cat_2_image_id' => '',
            'cat_3_title' => 'Soju Durazno',
            'cat_3_url' => '/categoria-producto/soju-durazno',
            'cat_3_image_id' => '',
            'cat_4_title' => 'Próximamente: sopas, snacks y sake',
            'cat_4_url' => '/tienda',
            'cat_4_image_id' => '',
        ], $atts, 'ab_home_categories');

        return self::render_template('categories', [
            'cat_1_title' => sanitize_text_field($atts['cat_1_title']),
            'cat_1_url' => esc_url_raw($atts['cat_1_url']),
            'cat_1_image_html' => self::build_supporting_image_html($atts['cat_1_image_id'], 'ab-tile__image'),
            'cat_2_title' => sanitize_text_field($atts['cat_2_title']),
            'cat_2_url' => esc_url_raw($atts['cat_2_url']),
            'cat_2_image_html' => self::build_supporting_image_html($atts['cat_2_image_id'], 'ab-tile__image'),
            'cat_3_title' => sanitize_text_field($atts['cat_3_title']),
            'cat_3_url' => esc_url_raw($atts['cat_3_url']),
            'cat_3_image_html' => self::build_supporting_image_html($atts['cat_3_image_id'], 'ab-tile__image'),
            'cat_4_title' => sanitize_text_field($atts['cat_4_title']),
            'cat_4_url' => esc_url_raw($atts['cat_4_url']),
            'cat_4_image_html' => self::build_supporting_image_html($atts['cat_4_image_id'], 'ab-tile__image'),
        ]);
    }

    public static function render_best_sellers(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Más vendidos',
            'limit' => '8',
            'columns' => '4',
        ], $atts, 'ab_home_best_sellers');

        $woo_shortcode = sprintf('[products limit="%d" columns="%d" orderby="popularity"]', (int) $atts['limit'], (int) $atts['columns']);

        return self::render_template('best-sellers', [
            'title' => sanitize_text_field($atts['title']),
            'products_html' => do_shortcode($woo_shortcode),
        ]);
    }

    public static function render_how(array $atts = []): string {
        $default_steps = [
            ['number' => '01', 'title' => 'Elige tu vibe', 'body' => 'Compra por categorías o arma tu box desde cero.'],
            ['number' => '02', 'title' => 'Recibe en casa', 'body' => 'Coordinamos tu entrega sin vueltas ni protocolos.'],
            ['number' => '03', 'title' => 'Abre y disfruta', 'body' => 'Sirve, comparte y repite cuando quieras.'],
        ];

        return self::render_template('how', [
            'steps' => self::normalize_items((array) apply_filters('ab_home_how_steps', $default_steps), ['number', 'title', 'body']),
        ]);
    }

    public static function render_testimonials(array $atts = []): string {
        $default_testimonials = [
            ['quote' => '“Literal llegó el mismo día. El ramen picante 10/10.”', 'author' => 'Camila, Chapinero'],
            ['quote' => '“La suscripción me ahorra tiempo y siempre trae algo nuevo.”', 'author' => 'Mateo, Teusaquillo'],
            ['quote' => '“Pedí box para regalo y fue éxito total en la oficina.”', 'author' => 'Sara, Usaquén'],
        ];

        return self::render_template('testimonials', [
            'items' => self::normalize_items((array) apply_filters('ab_home_testimonials', $default_testimonials), ['quote', 'author']),
        ]);
    }

    public static function render_about(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Somos Asian Bites',
            'summary' => 'Hoy somos especialistas en Soju (Fresh, Uva Verde y Durazno) para acompañar la vida real: planes simples, momentos espontáneos y cero complicaciones. Nuestro mantra es claro: “Abre, sirve, sigue. Sin protocolos. Sin complicaciones.” Mientras seguimos creciendo, también exploramos nuevas categorías como sopas, snacks y sake.',
            'about_mascot_image_id' => '',
        ], $atts, 'ab_home_about');

        return self::render_template('about', [
            'title' => sanitize_text_field($atts['title']),
            'summary' => sanitize_textarea_field($atts['summary']),
            'mascot_html' => self::build_supporting_image_html($atts['about_mascot_image_id'], 'ab-about__mascot'),
        ]);
    }

    private static function build_supporting_image_html($image_id, string $class_name, bool $lazy = true): string {
        $attachment_id = absint((string) $image_id);

        if (0 === $attachment_id) {
            return '';
        }

        return (string) wp_get_attachment_image(
            $attachment_id,
            'medium_large',
            false,
            [
                'class' => $class_name,
                'loading' => $lazy ? 'lazy' : 'eager',
                'decoding' => 'async',
            ]
        );
    }

    public static function render_faq(array $atts = []): string {
        $default_faqs = [
            ['q' => '¿Hacen entregas el mismo día en Bogotá?', 'a' => 'Sí, según zona y hora de compra. Verás disponibilidad al finalizar tu pedido.'],
            ['q' => '¿Puedo escoger productos en mi box?', 'a' => 'Sí. Puedes armar tu box manualmente o elegir uno curado por nosotros.'],
            ['q' => '¿Cómo funciona la suscripción mensual?', 'a' => 'Te enviamos una selección mensual y puedes pausar o cancelar desde tu cuenta.'],
            ['q' => '¿Qué medios de pago aceptan?', 'a' => 'Tarjeta débito/crédito, PSE y billeteras digitales habilitadas por WooCommerce.'],
            ['q' => '¿Tienen opciones para regalo?', 'a' => 'Sí, contamos con kits listos para regalo y mensaje personalizado.'],
        ];

        return self::render_template('faq', [
            'faqs' => self::normalize_items((array) apply_filters('ab_home_faq_items', $default_faqs), ['q', 'a']),
        ]);
    }

    public static function render_final_cta(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Tu próxima remesa asiática está a un clic',
            'button_text' => 'Empezar pedido',
            'button_url' => '/tienda',
        ], $atts, 'ab_home_final_cta');

        return self::render_template('final-cta', [
            'title' => sanitize_text_field($atts['title']),
            'button_text' => sanitize_text_field($atts['button_text']),
            'button_url' => esc_url_raw($atts['button_url']),
        ]);
    }

    private static function normalize_items(array $items, array $keys): array {
        $normalized = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $entry = [];
            foreach ($keys as $key) {
                $entry[$key] = isset($item[$key]) ? sanitize_text_field((string) $item[$key]) : '';
            }

            $has_content = false;
            foreach ($entry as $value) {
                if ('' !== $value) {
                    $has_content = true;
                    break;
                }
            }

            if ($has_content) {
                $normalized[] = $entry;
            }
        }

        return $normalized;
    }

    private static function is_rankmath_active(): bool {
        return defined('RANK_MATH_VERSION') || class_exists('RankMath\Core\Manager') || class_exists('RankMath');
    }

    private static function should_output_meta(): bool {
        $enabled = (bool) apply_filters('ab_home_output_meta', true);
        $force = (bool) apply_filters('ab_home_force_output_meta', false);

        if (self::is_rankmath_active() && !$force) {
            return false;
        }

        return $enabled;
    }

    private static function should_output_schema(): bool {
        $enabled = (bool) apply_filters('ab_home_output_schema', true);
        $force = (bool) apply_filters('ab_home_force_output_schema', false);

        if (self::is_rankmath_active() && !$force) {
            return false;
        }

        return $enabled;
    }

    private static function should_register_head_hook(): bool {
        if (!self::is_rankmath_active()) {
            return true;
        }

        $force_meta = (bool) apply_filters('ab_home_force_output_meta', false);
        $force_schema = (bool) apply_filters('ab_home_force_output_schema', false);

        return $force_meta || $force_schema;
    }

    public static function output_meta_and_schema(): void {
        if (!self::should_load_home_assets()) {
            return;
        }

        if (self::is_rankmath_active() && !(bool) apply_filters('ab_home_force_output_meta', false) && !(bool) apply_filters('ab_home_force_output_schema', false)) {
            return;
        }

        $site_name = get_bloginfo('name');
        $home_url = home_url('/');

        if (self::should_output_meta()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="Asian Bites | Boxes asiáticas en Bogotá">' . "\n";
            echo '<meta property="og:description" content="Abre, sirve, sigue. Boxes y suscripciones de comida asiática con delivery rápido en Bogotá.">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($home_url) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:title" content="Asian Bites | Boxes asiáticas en Bogotá">' . "\n";
            echo '<meta name="twitter:description" content="Sin protocolos. Sin complicaciones. Delivery y suscripción mensual de productos asiáticos.">' . "\n";
        }

        if (self::should_output_schema()) {
            $schema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => trailingslashit($home_url) . '#organization',
                        'name' => $site_name,
                        'url' => $home_url,
                        'description' => 'Boxes y remesas de productos asiáticos por delivery y suscripción mensual en Bogotá.',
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => trailingslashit($home_url) . '#website',
                        'url' => $home_url,
                        'name' => $site_name,
                        'publisher' => [
                            '@id' => trailingslashit($home_url) . '#organization',
                        ],
                        'potentialAction' => [
                            '@type' => 'SearchAction',
                            'target' => home_url('/?s={search_term_string}'),
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                ],
            ];

            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }

    public static function render_debug(array $atts = []): string {
        if (!current_user_can('manage_options')) {
            return '';
        }

        return self::render_template('debug', [
            'rows' => [
                'home_context' => self::should_load_home_assets() ? 'true' : 'false',
                'rankmath_detected' => self::is_rankmath_active() ? 'true' : 'false',
                'head_hook_registered' => self::should_register_head_hook() ? 'true' : 'false',
                'meta_enabled' => self::should_output_meta() ? 'true' : 'false',
                'schema_enabled' => self::should_output_schema() ? 'true' : 'false',
                'force_meta_override' => (bool) apply_filters('ab_home_force_output_meta', false) ? 'true' : 'false',
                'force_schema_override' => (bool) apply_filters('ab_home_force_output_schema', false) ? 'true' : 'false',
            ],
        ]);
    }

    private static function render_template(string $template_name, array $data): string {
        $template_file = plugin_dir_path(__FILE__) . 'templates/' . $template_name . '.php';

        if (!file_exists($template_file)) {
            return '';
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $template_file;

        return (string) ob_get_clean();
    }

    private static function critical_hero_css(): string {
        return '.ab-home .ab-hero{padding-top:clamp(1rem,3vw,2rem)}.ab-home .ab-hero__grid{display:grid;gap:clamp(1rem,3vw,2rem);grid-template-columns:repeat(2,minmax(0,1fr));align-items:center}.ab-home .ab-hero__media{min-height:320px}.ab-home .ab-hero__image,.ab-home .ab-hero__placeholder{width:100%;height:auto;aspect-ratio:4/5;border-radius:24px}.ab-home .ab-hero__image{object-fit:cover}.ab-home .ab-hero__placeholder{background:linear-gradient(140deg,rgba(255,77,166,.25),rgba(127,219,218,.25),rgba(255,225,86,.25))}@media (max-width:960px){.ab-home .ab-hero__grid{grid-template-columns:1fr}}';
    }
}

AsianBites_Home::init();
