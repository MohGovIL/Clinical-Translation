<?php
include_once ("../function/mysqliconf.php");

$statement = $db->rawQuery("SELECT languages.lang_description, 
                            languages.lang_code, 
                            constants.constant_name, 
                            definitions.definition 
                            FROM lang_definitions AS definitions 
                            JOIN lang_constants AS constants 
                            ON definitions.cons_id = constants.cons_id 
                            JOIN lang_languages AS languages 
                            ON definitions.lang_id = languages.lang_id");

$MAX_PAKET = 10000;

$columns = null;
$value = '';
$i = 0;
$c = 1;

$sql = "DROP TABLE IF EXISTS lang_custom;CREATE TABLE lang_custom ( lang_description varchar(100) NOT NULL DEFAULT '', lang_code char(2) NOT NULL DEFAULT '', constant_name mediumtext, definition mediumtext ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($statement) {

    foreach ($statement as $row) {

        $content = null;

        foreach ($row as $values) {
            $content .= "'" . addslashes($values) . "',";
        }

        $value .= "(" . trim($content, ',') . "),";

        if ($i == 0) {
            $columns = "(" . implode(", ", array_keys($row)) . ")" . "\n";
        }

        if ($i == $MAX_PAKET OR $c == $db->count) {
            $value = trim($value, ',');
            $value = $value . ";";
            $sql .= "INSERT INTO lang_custom {$columns} VALUES {$value}";
            $value = null;
            $i = 0;
        }

        $c++;
        $i++;
    }
}
$convert = "INSERT INTO lang_constants (constant_name) SELECT custom.constant_name as constant_name FROM lang_custom AS custom WHERE NOT EXISTS (SELECT cons_id FROM lang_constants AS constants WHERE constants.constant_name = custom.constant_name) GROUP BY custom.constant_name; INSERT INTO lang_definitions(cons_id, lang_id, definition) SELECT temp.cons_id,temp.lang_id,temp.definition FROM (SELECT constants.cons_id,languages.lang_id,custom.definition FROM lang_custom AS custom JOIN lang_constants AS constants ON constants.constant_name = custom.constant_name JOIN lang_languages AS languages ON languages.lang_code = custom.lang_code AND languages.lang_description = custom.lang_description) AS temp WHERE NOT EXISTS (SELECT def.cons_id FROM lang_definitions AS def WHERE def.cons_id = temp.cons_id AND def.lang_id = temp.lang_id); UPDATE lang_definitions as def JOIN lang_constants AS constants ON constants.cons_id = def.cons_id JOIN lang_languages AS languages ON languages.lang_id = def.lang_id JOIN lang_custom AS custom ON custom.lang_code = languages.lang_code AND custom.lang_description = languages.lang_description AND custom.constant_name = constants.constant_name SET def.cons_id = constants.cons_id, def.lang_id = languages.lang_id, def.definition = custom.definition WHERE custom.definition <> def.definition;";
$sql = $sql.$convert;
$sql .= "DROP TABLE IF EXISTS lang_custom;CREATE TABLE lang_custom ( lang_description varchar(100) NOT NULL DEFAULT '', lang_code char(2) NOT NULL DEFAULT '', constant_name mediumtext, definition mediumtext ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$Transaction = "START TRANSACTION;";
$Transaction .= $sql;
$Transaction .= "COMMIT;";
date_default_timezone_set('Asia,Jerusalem');
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header('Content-Disposition: attachment; filename="export_'.date('Y-m-d_H-i-s').'.sql"');


echo $Transaction;
exit();
