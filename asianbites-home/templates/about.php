<section class="ab-section ab-section--compact">
    <div class="ab-container ab-about" data-ab-reveal>
        <div>
            <h2><?php echo esc_html($title); ?></h2>
            <p><?php echo esc_html($summary); ?></p>
        </div>
        <?php if (!empty($mascot_html)) : ?>
            <div class="ab-about__mascot-wrap"><?php echo wp_kses_post($mascot_html); ?></div>
        <?php endif; ?>
    </div>
</section>
