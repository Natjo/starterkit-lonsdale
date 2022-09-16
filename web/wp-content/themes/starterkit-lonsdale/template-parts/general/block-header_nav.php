<div id="quick_access">
    <div class="content">
        <a href="#main">Accès au contenu principal</a>
        <a href="#footer">Accès au pied de page</a>
    </div>
</div>

<header id="header" role="banner" data-module="blocks/header-nav">
    <div class="container">
        <a href="/" class="logo" aria-label="Aller à la page d'accueil">
            <svg xmlns="http://www.w3.org/2000/svg" width="122" height="14" viewBox="0 0 122 14" xml:space="preserve">
                <path d="M56,0c-2.6,0-4.1,1.5-4.1,3.5c0,4.2,6.4,3.8,6.4,6.7c0,1.4-1.1,2.2-2.7,2.2c-1.2,0-2.8-0.5-3.9-1.7v2.1c1.2,0.9,2.7,1.3,3.9,1.3c2.6,0,4.5-1.4,4.5-3.9c0-4.5-6.4-3.9-6.4-6.7c0-1,0.7-1.7,2.3-1.7c1.1,0,2.4,0.4,3.5,1.2V1C58.3,0.3,57.1,0,56,0 M1.8,0.2H0v13.7h7.9v-1.8H1.8V0.2z M86.1,9.6l2.5-6.2l2.6,6.2H86.1z M88.3,0.1l-5.8,13.7h1.9l1-2.6h6.4l1.1,2.6h1.9l-6-13.7H88.3z M102.7,0.2h-1.8v13.7h7.9v-1.8h-6.1V0.2z M116.1,12.1V7.5h5.2V5.9h-5.2V1.9h5.9V0.2h-7.7v13.7h7.7v-1.7H116.1z M71.4,12.1h-2.8V1.9h2.8c1.2,0,5,0.5,5,5.1C76.4,11.6,72.6,12.1,71.4,12.1 M71.1,0.2h-4.3v13.7h4.3c4.1,0,7.1-2.4,7.1-6.8C78.2,2.6,75.2,0.2,71.1,0.2 M43.2,9.6l-9-9.5h-0.8v13.7h1.8v-10l7.9,8.3v1.8H45V0.2h-1.8V9.6z M19.7,12.2c-2.8,0-5-2.3-5-5.2s2.3-5.2,5-5.2s5,2.3,5,5.2S22.4,12.2,19.7,12.2 M19.7,0c-3.8,0-6.8,3.1-6.8,7c0,3.9,3.1,7,6.8,7c3.8,0,6.8-3.1,6.8-7C26.5,3.1,23.4,0,19.7,0" />
            </svg>
        </a>

        <button id="btn-nav" aria-expanded="false" aria-controls="nav-panel">Menu</button>

        <div id="nav-panel"> <nav id="nav" role="navigation" aria-label="Access to navigation">
                <?php
                wp_nav_menu(array(
                    'container' => false,
                    'theme_location' => 'menu-header',
                    'menu_class'  => false,
                    'items_wrap' => '<ul class="nav-links level-0">%3$s</ul>',
                    'walker' => new menu_header_Walker()
                ));
                ?>
            </nav>
            <form id="search" method="post" action="/">
                <input id="search-input" type="text" name="s" placeholder="recherche"> 
                <label for="search-input">Search</label>
                <?= icon('search', 30, 21)?>
            </form>

           
        </div>
    </div>
</header>