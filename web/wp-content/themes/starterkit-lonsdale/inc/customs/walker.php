<?php

class menu_header_Walker extends Walker_Nav_Menu
{

    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        //$hasChildren = $args->walker->has_children;
        $title = $item->title;
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';
        $output .= '<li>';
        $output .= '<a href="' . $permalink . '" ' . $target . '>';
        $output .= $title;
        $output .= '</a>';
    }

        // @see Walker::start_lvl()
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
    
            // Default class.
            $classes = array( 'nav-links level-'.($depth + 1 ));
    
            /**
             * Filters the CSS class(es) applied to a menu list element.
             *
             * @since 4.8.0
             *
             * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
             * @param stdClass $args    An object of `wp_nav_menu()` arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
    
            $output .= "{$n}{$indent}<ul$class_names>{$n}";
        }
    
}
