<?php
namespace Ultra\Html;

/**
 * Utility methods for all HTML helpers
 */
class BaseHelper 
{
    /**
    * @var array Configuration array
    */
    protected $config;

    /**
     * Creates a new Helper
     */
    public function __construct($config = array()) {
        $this->config = array_merge(array(
            // base uri, by default it's root (/)
            'base_uri' => '',
        ), $config);
    }

    /**
     * Translates an array into an html list of key-values
     *
     * @param array An associative array of parameters
     *
     * @return string A list of html key-values
     */
    protected function array_to_html($arr) {
        $attrs = array();
        foreach($arr as $key => $value) {
            $attrs[] = $key . '="' . $value . '"';
        }
        return implode(' ', $attrs);
    }

    /**
     * If the url is not absolute, prepend the base uri
     *
     * @param string $uri The uri to check
     *
     * @return string A uri with the according base uri prepended
     */
    protected function urlify($uri) {
        if(substr($uri, 0, 1) == '/') {
            return $uri;
        }

        return $this->config['base_uri'] . '/' . $uri;

    }
}
