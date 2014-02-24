<?php
namespace Ultra\Html;

require_once __DIR__ . '/BaseHelper.php';

/**
 * Html helper, several utilities for generating HTML tags
 */
class Html extends BaseHelper
{
    
    public function link($uri, $name = null, $arguments = array()) {
        $uri = $this->urlify($uri);

        if(is_null($name)) $name = $uri;

        return '<a href="' . $uri . '" ' . $this->array_to_html($arguments) . '>' . $name . '</a>';
    }

    public function ul($arr) {
        return $this->build_list($arr, 'ul');
    }

    public function ol($arr) {
        return $this->build_list($arr, 'ol');
    }

    private function build_list($arr, $type) {
        $output = "<$type>";
        foreach($arr as $item) {
            if(is_array($item)) {
                $output .= '<li>' . $this->build_list($item, $type) . '</li>';
            } else {
                $output .= '<li>' . $item . '</li>';
            }
        }
        $output .= "</$type>";
        return $output;
    }

    public function img($uri, $attrs) {
        return '<img src="' . $this->urlify($uri) . '" ' . $this->array_to_html($attrs) . '/>';
    }

    public function css($uri) {
        return '<link href="' . $this->urlify($uri) . '" rel="stylesheet" type="text/css" />';
    }

    public function script($uri) {
        return '<script src="' . $this->urlify($uri) . '" type="text/javascript"></script>';
    }
}

