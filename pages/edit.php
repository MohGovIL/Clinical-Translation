<?php
session_start();
include_once ("../function/mysqliconf.php");

$_POST = null;
$_GET = null;


foreach ($_REQUEST as $key => $value){
    $_REQUEST[$key] = $db->escape($value);
}

switch ($_REQUEST['p']){
    case "filter":


        $WHERE = "AND 1=1";
        if($_REQUEST['Constants']){
          $WHERE .= " AND lc.constant_name = '{$_REQUEST['Constants']}'";
        }

        if($_REQUEST['Lang']){
          $WHERE .= " AND ld.definition LIKE '%{$_REQUEST['Lang']}%'";
        }


        $sql = "SELECT lc.cons_id AS Constant_id ,ld.def_id as Lang_id ,lc.constant_name AS Constants,ld.definition AS Lang FROM lang_definitions AS ld
                LEFT JOIN lang_constants as lc on lc.cons_id = ld.cons_id
                WHERE ld.lang_id = 7 $WHERE";


        $lang = $db->rawQuery($sql);

        header("Content-Type: application/json");
        echo json_encode($lang);


        break;
    case "update":

        if($_REQUEST['Constants']){

            if(empty($_REQUEST['Constants']) OR empty($_REQUEST['Lang'])){
                exit();
            }

            if(!preg_match('/^[^א-ת]+$/', $_REQUEST['Constants'])){
                exit();
            }

            $db->where ('cons_id', $_REQUEST['Constant_id']);
            $db->update ('lang_constants', Array ('constant_name' => $_REQUEST['Constants']));
        }


        if($_REQUEST['Lang']){
            $db->where ('def_id', $_REQUEST['Lang_id']);
            $db->update ('lang_definitions', Array ('definition' => $_REQUEST['Lang']));
        }


        break;


}