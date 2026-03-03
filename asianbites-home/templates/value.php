<section class="ab-section">
    <div class="ab-container" data-ab-reveal>
        <h2>Tu antojo asiático, resuelto en minutos</h2>
        <div class="ab-grid ab-grid--4">
            <?php foreach ($items as $item) : ?>
                <article class="ab-card">
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <p><?php echo esc_html($item['body']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
