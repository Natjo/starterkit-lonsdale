<section id="pages" class="tab-content">
    <header>
        <h2>Générer les pages du sites</h2>
    </header>

    <section>
        <button class="es-btn plug-static-btn-generate"><span>Generate</span></button>

        <?php if (!empty($last_generate)) : ?>
            <span class="es-last_generated"> Last generated : <?= date("j F Y h:i:s", strtotime($last_generate)) ?></span>
        <?php endif; ?>
    </section>
</section>