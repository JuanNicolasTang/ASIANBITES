<div class="ab-home-debug">
    <h3>AB Home Debug</h3>
    <ul>
        <?php foreach ($rows as $key => $value) : ?>
            <li><strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html($value); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
