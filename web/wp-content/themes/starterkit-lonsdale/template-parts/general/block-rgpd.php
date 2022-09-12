<?php
$cookiesFields = get_fields('options');
?>

<div id="rgpd-manage" style="display: none" role="dialog" aria-modal="true" aria-labelledby="rgpd-title" aria-describedby="rgpd-desc" data-nonce="<?= wp_create_nonce("securite_nonce_rgpd"); ?>" data-action="rgpd">
    <div class="box">
        <button type="button" class="btn-close btn-picto" aria-label="<?= __('Fermer la fenêtre de paramètres des cookies', 'lsd_lang'); ?>">X</button>
        <?php if (!empty($cookiesFields['params-cookies-title'])) : ?>
            <h1 id="rgpd-title"><?= $cookiesFields['params-cookies-title'] ?></h1>
        <?php endif; ?>
        <?php if (!empty($cookiesFields['params-cookies-intro'])) : ?>
            <div id="rgpd-desc" class="rte">
                <p><?= $cookiesFields['params-cookies-intro'] ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($cookiesFields['params-cookies-groups'])) : ?>
            <?php foreach ($cookiesFields['params-cookies-groups'] as $cookieGroup) : ?>
                <?php
                $dataCookies = array();
                if (!empty($cookieGroup['cookies'])) {
                    foreach ($cookieGroup['cookies'] as $index => $cookie) {
                        array_push($dataCookies, $cookie['name']);
                    }
                }
                ?>
                <section>
                    <h2><?= $cookieGroup['title']; ?></h2>
                    <?php if ($cookieGroup['activation'] === true) : ?>
                        <input type="checkbox" id="pref-<?= $cookieGroup['id']; ?>" name="pref-<?= $cookieGroup['id']; ?>" value="<?= $cookieGroup['id']; ?>" aria-label="<?= __('Gestion des cookies', 'lsd_lang'); ?> <?= $cookieGroup['title']; ?>" data-cookies="<?= implode(",", $dataCookies); ?>" data-accept="<?= __('Activé', 'lsd_lang'); ?>" data-denied="<?= __('Désactivé', 'lsd_lang'); ?>">
                    <?php endif; ?>
                    <p><?= $cookieGroup['text']; ?></p>
                    <?php if (!empty($cookieGroup['cookies'])) : ?>
                        <details>
                            <summary>Afficher les details</summary>
                            <ul>
                                <?php foreach ($cookieGroup['cookies'] as $index => $cookie) : ?>
                                    <li>
                                        <strong><?= $cookie['name']; ?></strong>
                                        <?php if ($cookie['domain']) : ?>
                                            <small>(<?= $cookie['domain']; ?>)</small>
                                        <?php endif; ?>
                                        <?php if (!empty($cookie['description'])) : ?>
                                            <br>
                                            <?= $cookie['description']; ?>
                                        <?php endif; ?>
                                    <li>
                                    <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="button" class="btn-save btn1"><?= __('Sauvegarder', 'lsd_lang'); ?></button>
    </div>
</div>

<div id="rgpd-modal" aria-hidden="true" style="display: none">
    <button type="reset" class="btn-refuse"><?= __('Continuer sans accepter', 'lsd_lang'); ?> →</button>
    <?php if (!empty($cookiesFields['params-cookies-title'])) : ?>
        <h2><?= $cookiesFields['params-cookies-title'] ?></h2>
    <?php endif; ?>
    <?php if (!empty($cookiesFields['params-cookies-intro'])) : ?>
        <p><?= $cookiesFields['params-cookies-intro'] ?></p>
    <?php endif; ?>
    <button type="button" class="rgpd-manage-link link-1"><?= __('Paramétrer mes cookies', 'lsd_lang'); ?></button>
    <button type="button" class="btn-accept btn-picto"><?= __('Accepter', 'lsd_lang'); ?></button>
</div>