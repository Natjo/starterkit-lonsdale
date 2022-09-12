<?php if (!is_front_page() && !is_404() && !is_search()) : ?>
    <nav id="breadcrumb" class="container" aria-label="Fil d'Ariane" itemscope itemtype="https://schema.org/BreadcrumbList">
        <ol>
            <?php custom_breadcrumb(); ?>
        </ol>
    </nav>
<?php endif; ?>