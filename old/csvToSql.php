<?php

$exists = array();

if (($handle = fopen("clinikal_translation.csv", "r")) !== FALSE) {
  //  file_put_contents('mezigaTranslation2.sql', "");
    while (($data = fgetcsv($handle, 1000, "^")) !== FALSE) {

        if(strpos($data[0], '"')){
            $data[0] = str_replace('"', '\"', $data[0]);
        }
        if(strpos($data[1], '"')){
            $data[1] = str_replace('"', '\"', $data[1]);
        }

        if(!in_array($data[0], $exists)){
            $exists[] = $data[0];
        } else {
            continue;
        }

        $sql = "INSERT INTO `lang_custom` (`lang_description`,`lang_code`,`constant_name`,`definition`) VALUES (\"\",\"\",\"{$data[0]}\",\"\");\n";
        $sql .= "INSERT INTO `lang_custom` (`lang_description`,`lang_code`,`constant_name`,`definition`) VALUES (\"Hebrew\",\"he\",\"{$data[0]}\",\"{$data[1]}\");";

        $old =  file_get_contents('customTranslation.sql');
        $string = $old . $sql . "\n";
        file_put_contents('customTranslation.sql', $string);
    }
    fclose($handle);
}
