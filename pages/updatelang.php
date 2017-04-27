<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Created by PhpStorm.
 * User: oshri
 * Date: 23/04/17
 * Time: 08:20
 */


include_once ("../function/mysqliconf.php");


$result = file_get_contents("https://raw.githubusercontent.com/openemr/translations_development_openemr/master/languageTranslations_utf8.sql");

$find = array("lang_languages","lang_constants","lang_definitions");
$replace = array("community_lang_languages","community_lang_constants","community_lang_definitions");
$result = str_replace($find,$replace,$result);


$lines = explode(chr(10),$result);

foreach ($lines as $line)
{
// Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

// Add this line to the current segment
    $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Perform the query
        $db->rawQuery($templine);
        // Reset temp variable to empty
        $templine = '';
    }
}



die(1);