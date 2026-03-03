<?php
/**
 * Plugin Name: Asian Bites Home
 * Description: Homepage modular para Asian Bites con shortcodes optimizados para Elementor + WooCommerce.
 * Version: 1.0.0
 * Author: Asian Bites
 * Requires at least: 6.5
 * Requires PHP: 8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

final class AsianBites_Home {
    private const VERSION = '1.0.0';
    private const HANDLE = 'asianbites-home';

    public static function init(): void {
        add_action('init', [__CLASS__, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('wp_head', [__CLASS__, 'output_meta_and_schema'], 20);
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
    }

    public static function enqueue_assets(): void {
        if (!is_front_page()) {
            return;
        }

        $base_url = plugin_dir_url(__FILE__);
        $base_path = plugin_dir_path(__FILE__);

        wp_enqueue_style(
            self::HANDLE,
            $base_url . 'assets/css/home.css',
            [],
            filemtime($base_path . 'assets/css/home.css')
        );

        wp_add_inline_style(self::HANDLE, self::critical_hero_css());

        wp_enqueue_script(
            self::HANDLE,
            $base_url . 'assets/js/home.js',
            [],
            filemtime($base_path . 'assets/js/home.js'),
            true
        );
    }

    public static function render_home(array $atts = []): string {
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

        return sprintf('<div class="ab-home">%s</div>', implode("\n", $sections));
    }

    public static function render_hero(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Abre, sirve, sigue.',
            'subtitle' => 'Tu box asiático llega a Bogotá con snacks, ramen y favoritos listos para disfrutar sin protocolos.',
            'cta_primary_text' => 'Arma tu box',
            'cta_primary_url' => '/tienda',
            'cta_secondary_text' => 'Suscríbete mensual',
            'cta_secondary_url' => '/suscripcion',
            'chip_1' => 'Delivery rápido en Bogotá',
            'chip_2' => 'Sin protocolos. Sin complicaciones.',
            'chip_3' => 'Cancela cuando quieras',
            'hero_image_id' => '',
            'hero_image_url' => '',
            'hero_alt' => 'Box Asian Bites abierto con productos asiáticos',
            'hero_srcset' => '',
            'hero_sizes' => '(max-width: 767px) 100vw, 50vw',
            'hero_width' => '960',
            'hero_height' => '1080',
        ], $atts, 'ab_home_hero');

        $image_html = self::build_hero_image($atts);

        return sprintf(
            '<section class="ab-section ab-hero"><div class="ab-container ab-hero__grid"><div class="ab-hero__content" data-ab-reveal><p class="ab-eyebrow">Asian Bites · Bogotá</p><h1>%1$s</h1><p class="ab-subtitle">%2$s</p><div class="ab-cta-row"><a class="ab-btn ab-btn--primary" href="%3$s">%4$s</a><a class="ab-btn ab-btn--ghost" href="%5$s">%6$s</a></div><ul class="ab-chip-list"><li>%7$s</li><li>%8$s</li><li>%9$s</li></ul></div><div class="ab-hero__media" data-ab-reveal>%10$s</div></div></section>',
            esc_html($atts['title']),
            esc_html($atts['subtitle']),
            esc_url($atts['cta_primary_url']),
            esc_html($atts['cta_primary_text']),
            esc_url($atts['cta_secondary_url']),
            esc_html($atts['cta_secondary_text']),
            esc_html($atts['chip_1']),
            esc_html($atts['chip_2']),
            esc_html($atts['chip_3']),
            $image_html
        );
    }

    private static function build_hero_image(array $atts): string {
        if (!empty($atts['hero_image_id'])) {
            return wp_get_attachment_image(
                (int) $atts['hero_image_id'],
                'large',
                false,
                [
                    'class' => 'ab-hero__image',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'decoding' => 'async',
                    'sizes' => esc_attr($atts['hero_sizes']),
                    'alt' => esc_attr($atts['hero_alt']),
                ]
            );
        }

        $src = !empty($atts['hero_image_url']) ? esc_url($atts['hero_image_url']) : 'https://via.placeholder.com/960x1080.webp?text=Asian+Bites+Hero';
        $srcset = !empty($atts['hero_srcset']) ? sprintf(' srcset="%s"', esc_attr($atts['hero_srcset'])) : '';

        return sprintf(
            '<img class="ab-hero__image" src="%1$s"%2$s sizes="%3$s" alt="%4$s" width="%5$d" height="%6$d" loading="eager" fetchpriority="high" decoding="async">',
            $src,
            $srcset,
            esc_attr($atts['hero_sizes']),
            esc_attr($atts['hero_alt']),
            (int) $atts['hero_width'],
            (int) $atts['hero_height']
        );
    }

    public static function render_value(array $atts = []): string {
        return '<section class="ab-section"><div class="ab-container" data-ab-reveal><h2>Tu antojo asiático, resuelto en minutos</h2><div class="ab-grid ab-grid--4"><article class="ab-card"><h3>Curaduría real</h3><p>Seleccionamos marcas virales y clásicos de confianza.</p></article><article class="ab-card"><h3>Precios claros</h3><p>Boxes para compartir o maratonear solo, sin letras chiquitas.</p></article><article class="ab-card"><h3>Delivery express</h3><p>Despacho rápido en Bogotá con seguimiento simple.</p></article><article class="ab-card"><h3>Suscripción flexible</h3><p>Recibe cada mes y pausa cuando quieras.</p></article></div></div></section>';
    }

    public static function render_categories(array $atts = []): string {
        $atts = shortcode_atts([
            'cat_1_title' => 'Snacks y dulces',
            'cat_1_url' => '/categoria-producto/snacks',
            'cat_2_title' => 'Ramen instantáneo',
            'cat_2_url' => '/categoria-producto/ramen',
            'cat_3_title' => 'Bebidas asiáticas',
            'cat_3_url' => '/categoria-producto/bebidas',
            'cat_4_title' => 'Kits para regalo',
            'cat_4_url' => '/categoria-producto/regalos',
        ], $atts, 'ab_home_categories');

        return sprintf(
            '<section class="ab-section ab-section--alt"><div class="ab-container" data-ab-reveal><h2>Explora por categorías</h2><div class="ab-grid ab-grid--2"><a class="ab-tile" href="%1$s"><h3>%2$s</h3></a><a class="ab-tile" href="%3$s"><h3>%4$s</h3></a><a class="ab-tile" href="%5$s"><h3>%6$s</h3></a><a class="ab-tile" href="%7$s"><h3>%8$s</h3></a></div></div></section>',
            esc_url($atts['cat_1_url']),
            esc_html($atts['cat_1_title']),
            esc_url($atts['cat_2_url']),
            esc_html($atts['cat_2_title']),
            esc_url($atts['cat_3_url']),
            esc_html($atts['cat_3_title']),
            esc_url($atts['cat_4_url']),
            esc_html($atts['cat_4_title'])
        );
    }

    public static function render_best_sellers(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Best sellers del momento',
            'limit' => '8',
            'columns' => '4',
        ], $atts, 'ab_home_best_sellers');

        $woo_shortcode = sprintf('[products visibility="featured" limit="%d" columns="%d"]', (int) $atts['limit'], (int) $atts['columns']);

        return sprintf('<section class="ab-section"><div class="ab-container" data-ab-reveal><h2>%1$s</h2><div class="ab-products">%2$s</div></div></section>', esc_html($atts['title']), do_shortcode($woo_shortcode));
    }

    public static function render_how(array $atts = []): string {
        return '<section class="ab-section ab-section--alt"><div class="ab-container" data-ab-reveal><h2>Cómo funciona</h2><div class="ab-grid ab-grid--3"><article class="ab-step"><span>01</span><h3>Elige tu vibe</h3><p>Compra por categorías o arma tu box desde cero.</p></article><article class="ab-step"><span>02</span><h3>Recibe en casa</h3><p>Coordinamos tu entrega sin vueltas ni protocolos.</p></article><article class="ab-step"><span>03</span><h3>Abre y disfruta</h3><p>Sirve, comparte y repite cuando quieras.</p></article></div></div></section>';
    }

    public static function render_testimonials(array $atts = []): string {
        return '<section class="ab-section"><div class="ab-container" data-ab-reveal><h2>Lo que dice la comunidad</h2><div class="ab-grid ab-grid--3"><figure class="ab-quote"><blockquote>“Literal llegó el mismo día. El ramen picante 10/10.”</blockquote><figcaption>— Camila, Chapinero</figcaption></figure><figure class="ab-quote"><blockquote>“La suscripción me ahorra tiempo y siempre trae algo nuevo.”</blockquote><figcaption>— Mateo, Teusaquillo</figcaption></figure><figure class="ab-quote"><blockquote>“Pedí box para regalo y fue éxito total en la oficina.”</blockquote><figcaption>— Sara, Usaquén</figcaption></figure></div></div></section>';
    }

    public static function render_about(array $atts = []): string {
        return '<section class="ab-section ab-section--compact"><div class="ab-container" data-ab-reveal><h2>Somos Asian Bites</h2><p>Nacimos en Bogotá para acercar lo mejor de la despensa asiática a tu rutina real: rápida, social y sin complicaciones. Curamos productos que sí valen la pena para que solo te preocupes por abrir, servir y seguir.</p></div></section>';
    }

    public static function render_faq(array $atts = []): string {
        $faqs = [
            ['q' => '¿Hacen entregas el mismo día en Bogotá?', 'a' => 'Sí, según zona y hora de compra. Verás disponibilidad al finalizar tu pedido.'],
            ['q' => '¿Puedo escoger productos en mi box?', 'a' => 'Sí. Puedes armar tu box manualmente o elegir uno curado por nosotros.'],
            ['q' => '¿Cómo funciona la suscripción mensual?', 'a' => 'Te enviamos una selección mensual y puedes pausar o cancelar desde tu cuenta.'],
            ['q' => '¿Qué medios de pago aceptan?', 'a' => 'Tarjeta débito/crédito, PSE y billeteras digitales habilitadas por WooCommerce.'],
            ['q' => '¿Tienen opciones para regalo?', 'a' => 'Sí, contamos con kits listos para regalo y mensaje personalizado.'],
        ];

        $items = array_map(
            static fn($faq) => sprintf(
                '<details class="ab-faq-item"><summary>%1$s</summary><p>%2$s</p></details>',
                esc_html($faq['q']),
                esc_html($faq['a'])
            ),
            $faqs
        );

        return sprintf('<section class="ab-section"><div class="ab-container" data-ab-reveal><h2>Preguntas frecuentes</h2><div class="ab-faq">%s</div></div></section>', implode('', $items));
    }

    public static function render_final_cta(array $atts = []): string {
        $atts = shortcode_atts([
            'title' => 'Tu próxima remesa asiática está a un clic',
            'button_text' => 'Empezar pedido',
            'button_url' => '/tienda',
        ], $atts, 'ab_home_final_cta');

        return sprintf('<section class="ab-section ab-section--final"><div class="ab-container" data-ab-reveal><h2>%1$s</h2><a class="ab-btn ab-btn--primary" href="%2$s">%3$s</a></div></section>', esc_html($atts['title']), esc_url($atts['button_url']), esc_html($atts['button_text']));
    }

    public static function output_meta_and_schema(): void {
        if (!is_front_page()) {
            return;
        }

        $site_name = get_bloginfo('name');
        $home_url = home_url('/');
        $search_url = esc_url(home_url('/?s={search_term_string}'));

        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="Asian Bites | Boxes asiáticas en Bogotá">' . "\n";
        echo '<meta property="og:description" content="Abre, sirve, sigue. Boxes y suscripciones de comida asiática con delivery rápido en Bogotá.">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($home_url) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="Asian Bites | Boxes asiáticas en Bogotá">' . "\n";
        echo '<meta name="twitter:description" content="Sin protocolos. Sin complicaciones. Delivery y suscripción mensual de productos asiáticos.">' . "\n";

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
                        'target' => $search_url,
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }

    private static function critical_hero_css(): string {
        return '.ab-home .ab-hero{padding-top:clamp(1rem,3vw,2rem)}.ab-home .ab-hero__grid{display:grid;gap:clamp(1rem,3vw,2rem);grid-template-columns:repeat(2,minmax(0,1fr));align-items:center}.ab-home .ab-hero__media{min-height:320px}.ab-home .ab-hero__image{width:100%;height:auto;aspect-ratio:4/5;object-fit:cover;border-radius:24px}@media (max-width:960px){.ab-home .ab-hero__grid{grid-template-columns:1fr}}';
    }
}

AsianBites_Home::init();
