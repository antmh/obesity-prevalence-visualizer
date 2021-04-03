<?php

spl_autoload_register(function ($className) {
	//die(var_dump($className));
    include_once dirname(__DIR__) . '/' . str_replace('\\', "/", $className) . '.php';
});