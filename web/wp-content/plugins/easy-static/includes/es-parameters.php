<section id="parameters" class="tab-content">
    <?php if (ENV_PREPROD_LONSDALE) : ?>
        <section>
            <header>
                <h2>Htaccess preprod</h2>
            </header>

            <div class="es-auth">
                <div>
                    <label for="">User</label>
                    <input type="text" id="es-auth-user" value="<?= $authentification["user"] ?>">
                </div>

                <div>
                    <label for="">Password</label>
                    <input type="password" id="es-auth-password" value="<?= $authentification["password"] ?>">
                </div>
            </div>
        </section>

        <hr>
    <?php endif; ?>
    
    <section>
        <header>
            <h2>Options</h2>
        </header>

        <?php

        ?>
        <ul>
            <li><input id="es-option-minify" type="checkbox" <?= $isminify === true ? "checked" : "" ?>><label>Compresser les pages générées</label></li>
        </ul>
    </section>
</section>