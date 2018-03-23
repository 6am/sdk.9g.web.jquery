<?php


    function add_javascript($script) {
        global $globals;
        if(!isset($globals['helpers']['includes']['javascript'])) $globals['helpers']['includes']['javascript'] = array();
        array_push($globals['helpers']['includes']['javascript'], $script);
        return $globals;
    }

    function get_javascript($globals = null) {
        if($globals === null) global $globals;

        if(!isset( $globals['helpers']['includes']['javascript'] )) return false;
        $output = '';
        foreach( (array) $globals['helpers']['includes']['javascript'][0] as $key => $value){
            $output .= '<script type="text/javascript" src="'.$value.'"></script>' . "\n";
        }
        return $output;
    }

    function add_css($css) {
        global $globals;
        if(!isset($globals['helpers']['includes']['css'])) $globals['helpers']['includes']['css'] = array();
        array_push($globals['helpers']['includes']['css'], $css);
        return $globals;
    }

    function get_css($globals = null) {
        if($globals === null) global $globals;

        if(!isset( $globals['helpers']['includes']['css'] )) return false;
        $output = '';
        foreach( (array) $globals['helpers']['includes']['css'][0] as $key => $value){
            $output .= '<link rel="stylesheet" href="'.$value.'">' . "\n";
        }

        return $output;
    }

?>