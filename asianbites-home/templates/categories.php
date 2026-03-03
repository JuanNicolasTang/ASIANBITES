<section class="ab-section ab-section--alt">
    <div class="ab-container" data-ab-reveal>
        <h2>Explora el universo Soju + lo que viene</h2>
        <div class="ab-grid ab-grid--2">
            <a class="ab-tile" href="<?php echo esc_url($cat_1_url); ?>">
                <?php if (!empty($cat_1_image_html)) : ?>
                    <span class="ab-tile__media"><?php echo wp_kses_post($cat_1_image_html); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html($cat_1_title); ?></h3>
            </a>
            <a class="ab-tile" href="<?php echo esc_url($cat_2_url); ?>">
                <?php if (!empty($cat_2_image_html)) : ?>
                    <span class="ab-tile__media"><?php echo wp_kses_post($cat_2_image_html); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html($cat_2_title); ?></h3>
            </a>
            <a class="ab-tile" href="<?php echo esc_url($cat_3_url); ?>">
                <?php if (!empty($cat_3_image_html)) : ?>
                    <span class="ab-tile__media"><?php echo wp_kses_post($cat_3_image_html); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html($cat_3_title); ?></h3>
            </a>
            <a class="ab-tile" href="<?php echo esc_url($cat_4_url); ?>">
                <?php if (!empty($cat_4_image_html)) : ?>
                    <span class="ab-tile__media"><?php echo wp_kses_post($cat_4_image_html); ?></span>
                <?php endif; ?>
                <h3><?php echo esc_html($cat_4_title); ?></h3>
            </a>
        </div>
    </div>
</section>
