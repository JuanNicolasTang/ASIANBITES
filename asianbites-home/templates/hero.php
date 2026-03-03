<section class="ab-section ab-hero">
    <div class="ab-container ab-hero__grid">
        <div class="ab-hero__content" data-ab-reveal>
            <p class="ab-eyebrow">Especialistas en Soju · Bogotá</p>
            <h1><?php echo esc_html($title); ?></h1>
            <p class="ab-subtitle"><?php echo esc_html($subtitle); ?></p>
            <div class="ab-cta-row">
                <a class="ab-btn ab-btn--primary" href="<?php echo esc_url($cta_primary_url); ?>"><?php echo esc_html($cta_primary_text); ?></a>
                <a class="ab-btn ab-btn--ghost" href="<?php echo esc_url($cta_secondary_url); ?>"><?php echo esc_html($cta_secondary_text); ?></a>
            </div>
            <ul class="ab-chip-list">
                <li><?php echo esc_html($chip_1); ?></li>
                <li><?php echo esc_html($chip_2); ?></li>
                <li><?php echo esc_html($chip_3); ?></li>
            </ul>
        </div>
        <div class="ab-hero__media" data-ab-reveal>
            <?php echo wp_kses_post($image_html); ?>
        </div>
    </div>
</section>
