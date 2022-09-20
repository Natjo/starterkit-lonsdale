<?php

class Strate_Helper
{
    public static function strate_wysiwyg($aStrate)
    {
        $args = [];
        $args['text'] = $aStrate['text'];

        return $args;
    }

    public static function strate_image($aStrate)
    {

        $args = [
            'image' => [
                'desktop' => lsd_get_thumb($aStrate['strate_image-desktop'], 'full'),
                'mobile' => lsd_get_thumb($aStrate['strate_image-mobile'], '1024_auto'),
                'width' => '1000',
                'height' => '700'
            ]
        ];

        return $args;
    }

    public static function strate_news($aStrate)
    {
        $args = [];
        $args['title'] = $aStrate['title'];

        return $args;
    }
}
