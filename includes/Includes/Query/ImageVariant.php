<?php

namespace Propeller\Includes\Query;

class ImageVariant {
    public static $query;

    static function setDefaultQueryData() {
        self::$query = [
            "name",
            "language",
            "url",
            "mimeType"
        ];
    }

    static function setTransformationOptions($args) {
        $props = [];
        
        $name = "";
        
        if (isset($args['name'])) $name = 'name: "' . $args['name'] . '", ';

        if (isset($args['auto'])) $props[] = 'auto: ' . $args['auto'];
        if (isset($args['bgColor'])) $props[] = 'bgColor: "' . $args['bgColor'] . '"';
        if (isset($args['blur'])) $props[] = 'blur: ' . $args['blur'];
        if (isset($args['brightness'])) $props[] = 'brightness: ' . $args['brightness'];
        if (isset($args['canvas'])) $props[] = 'canvas: { ' . $args['canvas'] . ' }';   // canvas input
        if (isset($args['contrast'])) $props[] = 'contrast: ' . $args['contrast'];
        if (isset($args['crop'])) $props[] = 'crop: { ' . $args['crop'] . ' }'; // crop input
        if (isset($args['disable'])) $props[] = 'disable: ' . $args['disable'];
        if (isset($args['dpr'])) $props[] = 'dpr: ' . $args['dpr'];
        if (isset($args['fit'])) $props[] = 'fit: ' . $args['fit'];
        if (isset($args['format'])) $props[] = 'format: ' . $args['format'];
        if (isset($args['frame'])) $props[] = 'frame: ' . $args['frame'];
        if (isset($args['height'])) $props[] = 'height: ' . $args['height'];
        if (isset($args['level'])) $props[] = 'level: ' . $args['level'];
        if (isset($args['optimize'])) $props[] = 'optimize: ' . $args['optimize'];
        if (isset($args['orient'])) $props[] = 'orient: ' . $args['orient'];
        if (isset($args['pad'])) $props[] = 'pad: { ' . $args['pad'] . ' }';
        if (isset($args['precrop'])) $props[] = 'precrop: { ' . $args['precrop'] . ' }';
        if (isset($args['profile'])) $props[] = 'profile: "' . $args['profile'] . '"';
        if (isset($args['quality'])) $props[] = 'quality: ' . $args['quality'];
        if (isset($args['resizeFilter'])) $props[] = 'resizeFilter: ' . $args['resizeFilter'];
        if (isset($args['saturation'])) $props[] = 'saturation: ' . $args['saturation'];
        if (isset($args['sharpen'])) $props[] = 'sharpen: { ' . $args['sharpen'] . ' }';
        if (isset($args['trim'])) $props[] = 'trim: { ' . $args['trim'] . ' }';
        if (isset($args['width'])) $props[] = 'width: ' . $args['width'];
        
        $params = '{
            ' . $name . '
            transformation: { ' . implode(', ', $props) . ' }
        }';

        return $params;
    }

    static function setTransformations($transformations) {
        $output = '';

        if (is_array($transformations) && count($transformations)) {
            $output = '{
                transformations: [' . implode('], [', $transformations) . ']
            }';
        }

        return $output;
    }
}