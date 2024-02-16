<section id="parameters" class="tab-content">

    <section>
        <header>
            <h2>Host</h2>

            <p class="desc">
                En local, vhost de la machine virtuelle: <b><?= $_SERVER['SERVER_ADDR']; ?></b><br>
                Preprod/prod: <b><?= $_SERVER['SERVER_NAME']; ?></b><br>
            </p>

        </header>

        <input type="text" id="es-host" value="<?= $host ?>" style="width: 300px"><br>
    </section>

    <hr>

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

    <section>
        <header>
            <h2>Options</h2>
        </header>

        <?php

        ?>
        <ul>
            <li><input id="es-option-minify" type="checkbox" <?= $isminify === true ? "checked" : "" ?>><label>Compresser les pages générées</label></li>
            <li><input id="es-option-localisfolder" type="checkbox" <?= $localisfolder[0]->value === "true" ? "checked" : "" ?>><label>Multilangue local (default) est un dossier</label></li>
        </ul>
    </section>

</section>