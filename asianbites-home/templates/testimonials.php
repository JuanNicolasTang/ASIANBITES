<section class="ab-section">
    <div class="ab-container" data-ab-reveal>
        <h2>Lo que dice la comunidad</h2>
        <div class="ab-grid ab-grid--3">
            <?php foreach ($items as $item) : ?>
                <figure class="ab-quote">
                    <blockquote><?php echo esc_html($item['quote']); ?></blockquote>
                    <figcaption>— <?php echo esc_html($item['author']); ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
