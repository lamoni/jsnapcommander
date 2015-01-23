<?php namespace Lamoni\JSnapCommander\JSnapHelpers;

class JSnapHelpers {
    static public function JSONOutput($text, $code = 1, $die = true)
    {
        echo str_replace('\\/', '/', json_encode(array('html' => $text, 'error' => $code)));

        if ($die) die();

    }

    static public function br2nl($input)
    {

        return preg_replace('#<br\s*?/?>#i', "\n", $input);

    }
}
