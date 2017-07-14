<?php

function render($path, $data){
    $html = file_get_contents($path);
    foreach($data as $key => $value){
        $html = str_replace('{{'.$key.'}}',$value, $html);
    }
    return $html;
}