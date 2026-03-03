<section class="ab-section">
    <div class="ab-container" data-ab-reveal>
        <h2>Preguntas frecuentes</h2>
        <div class="ab-faq">
            <?php foreach ($faqs as $faq_item) : ?>
                <details class="ab-faq-item">
                    <summary><?php echo esc_html($faq_item['q']); ?></summary>
                    <p><?php echo esc_html($faq_item['a']); ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
