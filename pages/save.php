<?php
/**
 * Created by PhpStorm.
 * User: oshri
 * Date: 19/04/17
 * Time: 07:40
 */
session_start();
include_once ("../function/mysqliconf.php");
$_POST = null;
$_GET = null;

// remove query url
$_SERVER['HTTP_REFERER'] = explode('&',$_SERVER['HTTP_REFERER']);
$_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'][0];

if(empty($_REQUEST['constants']) OR empty($_REQUEST['definitions'])){
    if(strpos($_SERVER['HTTP_REFERER'], '&') == 0){
        $err = '&err=2';
    }
    header('Location: ' . $_SERVER['HTTP_REFERER'].$err);
    exit();
}

if(!preg_match('/^[^א-ת]+$/', $_REQUEST['constants'])){
    if(strpos($_SERVER['HTTP_REFERER'], '&') == 0){
        $err = '&err=3';
    }
    header('Location: ' . $_SERVER['HTTP_REFERER'].$err);
    exit();
}

foreach ($_REQUEST as $key => $value){
    $value = trim($value);
    $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $_REQUEST[$key] = $db->escape($value);
}

        $db->where ("constant_name", $_REQUEST['constants']);
        $user = $db->getOne("lang_constants");

        if (!empty($user)) {
            $_SESSION['constants'] = $_REQUEST['constants'];
            $_SESSION['definitions'] = $_REQUEST['definitions'];
            if(strpos($_SERVER['HTTP_REFERER'], '&') == 0){
                $err = '&err=1';
            }

            header('Location: ' . $_SERVER['HTTP_REFERER'].$err);
            exit();
        }


            $constant_id = $db->insert ("lang_constants", Array ("constant_name" => $_REQUEST['constants']));
            $db->insert ("lang_definitions", Array (
                "cons_id" => $constant_id,
                "lang_id" => 7,
                "definition" => $_REQUEST['definitions'],
            ));

            header('Location: ' . $_SERVER['HTTP_REFERER'].'&success');
