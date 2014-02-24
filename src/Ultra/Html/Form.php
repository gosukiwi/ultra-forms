<?php
namespace Ultra\Html;

require_once __DIR__ . '/BaseHelper.php';

/**
 * Forms helper, allows you to easily create forms.
 */
class Form extends BaseHelper
{
    private $token;

    public function __construct($config = array()) {
        // by default, token_expiration_minutes is 5
        if(!array_key_exists('token_expiration_minutes', $config)) {
            $config['token_expiration_minutes'] = 5;
        }

        parent::__construct($config);

        // if session is not started, start it as we're gonna use it for token validation
        if(session_id() == '') {
            session_start();
        }
    }

    public function open($method = 'post', $action = null, $multipart = false) {
        // generate csrf token
        $this->token = sha1(microtime());
        $_SESSION['uf-token'] = array(
            'value'     => $this->token,
            'expires'   => time() + (60 * $this->config['token_expiration_minutes']),
        );

        if(is_null($action)) {
            $action = $_SERVER['REQUEST_URI'];
        } else {
            $action = $this->urlify($action);
        }

        $enctype = $multipart ? 'enctype="multipart/form-data"' : '';

        return '<form method="' . $method . '" action="' . $action . '" ' . $enctype . '>';
    }

    public function input($name, $type = 'text', $attrs = array()) {
        $attrs['name'] = $name;
        return '<input type="' . $type . '" ' . $this->array_to_html($attrs) . ' />';
    }

    public function password($name, $attrs = array()) {
        return $this->input($name, 'password', $attrs);
    }

    public function label($text, $for, $attrs = array()) {
        return '<label for="' . $for . '" ' . $this->array_to_html($attrs) . '>' . $text . '</label>';
    }

    public function textarea($name, $value = null, $attrs = array()) {
        return '<textarea name="' . $name . '" ' . $this->array_to_html($attrs) . '>' . $value . '</textarea>';
    }

    public function select($name, $values, $attrs = array()) {
        $attrs['name'] = $name;
        $output = '<select ' . $this->array_to_html($attrs) . ' />';
        foreach($values as $key => $value) {
            $output .= '<option value="' . $key . '">' . $value . '</option>';
        }
        $output .= '</select>';

        return $output;
    }

    public function close() {
        return $this->input('__uf_token', 'hidden', array('value' => $this->token)) . '</form>';
    }

    public function submit($name = 'Submit', $attrs = array()) {
        $attrs['value'] = $name;
        return '<input type="submit" ' . $this->array_to_html($attrs) . '/>';
    }

    public function button($content, $attrs = array()) {
        return '<button ' . $this->array_to_html($attrs) . '>' . $content . '</button>';
    }

    public function get_csrf_token() {
        return $this->token;
    }

    public function is_safe() {
        // check for server match, even though this can be hijacked it's worth
        // a try
        $server = @$_SERVER['SERVER_NAME'];
        if($server) {
            $referer = @$_SERVER['HTTP_REFERER'];
            if(strpos($referer, $server) === false) {
                return false;
            }
        }

        // now check for token
        $token_data = @$_SESSION['uf-token'];
        if($token_data && $_REQUEST['__uf_token'] == $token_data['value'] && time() <= $token_data['expires']) {
            unset($_SESSION['uf-token']);
            return true;
        }

        return false;
    }
}
