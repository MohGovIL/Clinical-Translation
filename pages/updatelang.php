<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Created by PhpStorm.
 * User: oshri
 * Date: 23/04/17
 * Time: 08:20
 */



$output = shell_exec(dirname(__FILE__).'/updatelang.sh');
echo $output;

//echo exec("sudo bash pages/updatelang.sh");



die(1);