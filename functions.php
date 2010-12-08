<?php
function array_keys_exists($keys, $array) {
    foreach($keys as $k) {
        if (false == array_key_exists($k, $array)) {
            return false;
        }
    }
    return true;
}

function arrayToStr($array, $depth = 1) {
    $retStr = 'array(' . PHP_EOL;
    if (!is_array($array)) {
        return false;
    }
    foreach ($array as $key => $value) {
        for ($i = 0; $i < $depth; $i++) {
            $retStr .= '    ';
        }
        $retStr .= "'" . $key . "' => ";
        if (is_array($value)) {
            $retStr .= arrayToStr($value, $depth + 1);
        } else {
            $retStr .= "'" . $value . "'," . PHP_EOL;
        }
    }
    for ($i = 0; $i < $depth - 1; $i++) {
        $retStr .= '    ';
    }
    $retStr .= ')';
    if ($depth != 1) {
        $retStr .= ',';
    } else {
        $retStr .= ';';
    }
    $retStr .= PHP_EOL;
    return $retStr;
}

function __autoload($className) {
    $classNameArr = explode('_', $className);
    $classPath = '';
    for ($i = 0; $i < count($classNameArr) - 1; $i++) {
        $classPath .= $classNameArr[$i] . '/';
    }
    $classPath .= $classNameArr[$i] . '.php';
    if (file_exists($classPath)) {
        require_once($classPath);
        return true;
    } else {
        return false;
        //throw new Exception('Unknown class ' . $className . ' in '. getcwd());
    }
}

function singleValueToArray($value) {
    $tmpArr = array();
    if (!is_array($value)) {
        $tmpArr[] = $value;
        $value = $tmpArr;
    }
    return $value;
}