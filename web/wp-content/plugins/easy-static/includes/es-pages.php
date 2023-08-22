<section id="pages" class="tab-content">
    <header>
        
        <button class="es-btn plug-static-btn-generate"><span>Generate  pages</span></button>
    </header>

    <br>
    <table id="pages-list" class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Type</th>
                <th>Static</th>
                <!-- <th class="table-col-sm">Up to date</th> -->
            </tr>
        </thead>
        <tbody id="plug-static-pages">
            <?= display(); ?>
        </tbody>
    </table>
</section>