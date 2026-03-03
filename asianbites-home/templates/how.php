<section class="ab-section ab-section--alt">
    <div class="ab-container" data-ab-reveal>
        <h2>Cómo funciona</h2>
        <div class="ab-grid ab-grid--3">
            <?php foreach ($steps as $step) : ?>
                <article class="ab-step">
                    <span><?php echo esc_html($step['number']); ?></span>
                    <h3><?php echo esc_html($step['title']); ?></h3>
                    <p><?php echo esc_html($step['body']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
