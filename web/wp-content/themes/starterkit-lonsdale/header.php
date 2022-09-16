<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="icon" type="image/png" sizes="32x32" href="<?= THEME_ASSETS ?>favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= THEME_ASSETS ?>favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= THEME_ASSETS ?>favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= THEME_ASSETS ?>favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon-precomposed" href="<?= THEME_ASSETS ?>favicon/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= THEME_ASSETS ?>favicon/favicon.ico">
    <meta name='HandheldFriendly' content='true' />
    <meta name='format-detection' content='telephone=no'/>
    <meta name="msapplication-tap-highlight" content="no">
    <link rel='stylesheet' href='<?= THEME_ASSETS ?>print.css' media='print'>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
