<?php
/*
Template Name: Sliders
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$mail = formulaire_contact();
$mail_send = !empty($mail) && empty($mail['error']) ? true : false;
$mail_error = !empty($mail['error']) ? true : false;
?>

<main id="main" role="main" tabindex="-1" class="">
    <?php
    $args['title'] = "Sliders";
    get_template_part('template-parts/heros/hero', 'page', $args);
    ?>



    <section class="strate strate-sliders" data-module="strates/sliders">https://codepen.io/natjo/pen/rNQeaOd
        <template id="time">
            <li>
                <div class="time">
                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                </div>
                <ul></ul>
            </li>
        </template>

        <section class="strate trains container">
            <header>
                <h2 class="title-2">Trains</h2>
                <ul>
                    <li>
                        <div class="color large"></div> entre 1h20min et 40min avant
                    </li>
                    <li>
                        <div class="color valid"></div> entre 40min et 20min avant
                    </li>
                    <li>
                        <div class="color risque"></div> orange: entre 20min et 15min avant<br>
                    </li>
                    <li>
                        <div class="color not"></div> orange: entre 15min et 5min avant<br>
                    </li>
                </ul>
            </header>

            <div class="strate-content">
            <form data-type="go">
                    <fieldset>
                        <h2 class="title-3">De la gare d'Avignon</h2>

                        <ol>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                        </ol>

                        <button type="button" class="btn-add">+ Add</button>
                    </fieldset>

                    <div class="action">
                        <button type="button" class="btn-reset">Reset</button>
                    </div>
                </form>

                <form data-type="back">
                    <fieldset>
                        <h2 class="title-3">Retour Avignon - Paris</h2>

                        <ol>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                            <li>
                                <div class="time">
                                    <input minlength="1" maxlength="2" type="text" class="hours">:<input minlength="1" maxlength="2" type="text" class="minutes">
                                </div>
                                <ul></ul>
                            </li>
                        </ol>

                        <button type="button" class="btn-add">+ Add</button>
                    </fieldset>

                    <div class="action">
                        <button type="button" class="btn-reset">Reset</button>
                    </div>
                </form>
            </div>

        </section>




        <section class="container">
            <header>
                <h2 class="title-2">Slider auto width</h2>
            </header>

            <div class="slider slider0" role="region" aria-roledescription="carousel" aria-label="">
                <div class="slider-pagination"></div>

                <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                <ul class="slider-content">
                    <li class="item" aria-label="1 of 6">
                        <img src="<?= THEME_ASSETS ?>img/sliders/slider0/151-400x354.jpg" alt="">
                    </li>
                    <li class="item" aria-label="2 of 6">
                        <img src="<?= THEME_ASSETS ?>img/sliders/slider0/181-660x354.jpg" alt="">
                    </li>
                    <li class="item" aria-label="3 of 6">

                        <img src="<?= THEME_ASSETS ?>img/sliders/slider0/251-560x354.jpg" alt="">
                    </li>
                    <li class="item" aria-label="4 of 6">
                        <img src="<?= THEME_ASSETS ?>img/sliders/slider0/281-260x354.jpg" alt="">
                    </li>
                    <li class="item" aria-label="5 of 6">
                        <img src="<?= THEME_ASSETS ?>img/sliders/slider0/378-536x354.jpg" alt="">
                    </li>

                </ul>
            </div>
        </section>
        <hr>
        <section class="container">
            <header>
                <h2 class="title-2">Slider width grid</h2>
            </header>

            <div class="slider slider1" role="region" aria-roledescription="carousel" aria-label="Last news">
                <div class="slider-pagination"></div>

                <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                <ul class="slider-content">
                    <li class="item" aria-label="1 of 6"><a href="">1</a></li>
                    <li class="item" aria-label="2 of 6"><a href="">2</a></li>
                    <li class="item" aria-label="3 of 6"><a href="">3</a></li>
                    <li class="item" aria-label="4 of 6"><a href="">4</a></li>
                    <li class="item" aria-label="5 of 6"><a href="">5</a></li>
                    <li class="item" aria-label="6 of 6"><a href="">6</a></li>
                </ul>
            </div>
        </section>
        <hr>
        <section class="container">
            <header>
                <h2 class="title-2">Slider height auto and fixed right offset</h2>
            </header>

            <div class="slider slider2" role="region" aria-roledescription="carousel" aria-label="Last news">
                <div class="slider-pagination"></div>

                <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                <ul class="slider-content">
                    <li class="item" aria-label="1 of 6"><a href="">1</a></li>
                    <li class="item" aria-label="2 of 6"><a href="">2</a></li>
                    <li class="item" aria-label="3 of 6"><a href="">3</a></li>
                    <li class="item" aria-label="4 of 6"><a href="">4</a></li>
                    <li class="item" aria-label="5 of 6"><a href="">5</a></li>
                    <li class="item" aria-label="6 of 6"><a href="">6</a></li>
                </ul>
            </div>
        </section>
        <hr>
        <section>
            <header class="container">
                <h2 class="title-2">Full</h2>
            </header>

            <div class="slider slider3" role="region" aria-roledescription="carousel" aria-label="Last news">
                <div class="slider-pagination"></div>

                <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                <ul class="slider-content">
                    <li class="item" aria-label="1 of 6"><a href="">1</a></li>
                    <li class="item" aria-label="2 of 6"><a href="">2</a></li>
                    <li class="item" aria-label="3 of 6"><a href="">3</a></li>
                    <li class="item" aria-label="4 of 6"><a href="">4</a></li>
                    <li class="item" aria-label="5 of 6"><a href="">5</a></li>
                    <li class="item" aria-label="6 of 6"><a href="">6</a></li>
                </ul>
            </div>
        </section>
        <hr>
        <section>
            <header class="container">
                <h2 class="title-2">Sidebar</h2>
            </header>

            <div class="layout-sidebar">
                <div class="sidebar">
                    Sidebar
                </div>

                <div class="slider slider4" role="region" aria-roledescription="carousel" aria-label="Last news">
                    <div class="slider-pagination"></div>

                    <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                    <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                    <ul class="slider-content">
                        <li class="item" aria-label="1 of 6"><a href="">1</a></li>
                        <li class="item" aria-label="2 of 6"><a href="">2</a></li>
                        <li class="item" aria-label="3 of 6"><a href="">3</a></li>
                        <li class="item" aria-label="4 of 6"><a href="">4</a></li>
                        <li class="item" aria-label="5 of 6"><a href="">5</a></li>
                        <li class="item" aria-label="6 of 6"><a href="">6</a></li>
                    </ul>
                </div>
            </div>
        </section>
        <hr>
        <section class="container">
            <header>
                <h2 class="title-2">Slider mobile / grid desktop</h2>
                <p>Enabled on mobile and disabled on desktop</p>
            </header>

            <div class="slider slider5" role="region" aria-roledescription="carousel" aria-label="Last news">
                <div class="slider-pagination"></div>

                <button class="slider-btn prev" aria-hidden="true" tabindex="-1">prev</button>
                <button class="slider-btn next" aria-hidden="true" tabindex="-1">next</button>

                <ul class="slider-content">
                    <li class="item" aria-label="1 of 6"><a href="">1</a></li>
                    <li class="item" aria-label="2 of 6"><a href="">2</a></li>
                    <li class="item" aria-label="3 of 6"><a href="">3</a></li>
                    <li class="item" aria-label="4 of 6"><a href="">4</a></li>
                    <li class="item" aria-label="5 of 6"><a href="">5</a></li>
                    <li class="item" aria-label="6 of 6"><a href="">6</a></li>
                </ul>
            </div>
        </section>
    </section>
</main>

<?php
get_footer();
