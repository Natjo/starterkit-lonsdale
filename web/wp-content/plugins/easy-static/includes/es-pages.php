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
                <th class="table-col-sm">Up to date</th>
            </tr>
        </thead>
        <tbody id="plug-static-pages">
            <?= display(); ?>
        </tbody>
    </table>
</section>
<style>
    #pages-list th:nth-last-child(3),
    #pages-list td:nth-last-child(3) {
        text-align: center;
    }

    #pages-list th:nth-last-child(2),
    #pages-list td:nth-last-child(2) {
        width: 100px;
        text-align: center;
    }

    #pages-list th:nth-last-child(1),
    #pages-list td:nth-last-child(1) {
        width: 100px;
        text-align: center;
    }
    #pages.disabled{
        pointer-events: none;
        opacity: .6;
    }


    .info-update {
        color: green !important;
    }
    .info-update:before {
        content:'✓';
    }
    .info-update.error {
        color: red !important;
    }
    .info-update.error:before {
        content:'x';
    }
</style>