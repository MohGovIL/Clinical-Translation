<?php
/**
 * User: oshri
 * Date: 18/04/17
 * Time: 13:57
 */
session_start();
include_once ("function/nav.php");
include_once ("function/mysqliconf.php");
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Translate</title>
        <link rel="stylesheet" href="css/bootstrap-rtl.css" type="text/css" />
        <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="plugins/alertfy/css/alertify.rtl.min.css" type="text/css" />
        <link rel="stylesheet" href="plugins/jsgrid/jsgrid.min.css" type="text/css" />
        <link rel="stylesheet" href="plugins/jsgrid/jsgrid-theme.min.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <script src="js/jquery-3.2.1.min.js" language="javascript" type="text/javascript"></script>
        <script src="js/jquery.validate.min.js" language="javascript" type="text/javascript"></script>
        <script src="plugins/alertfy/alertify.min.js" language="javascript" type="text/javascript"></script>
        <script src="plugins/jsgrid/jsgrid.min.js" language="javascript" type="text/javascript"></script>
        <script src="plugins/jsgrid/jsgrid-he.js" language="javascript" type="text/javascript"></script>
        <script src="js/scripts.js" language="javascript" type="text/javascript"></script>

    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>מערכת תרגומים - Openemr</h1>
            </div>
            <div class="col-xs-12">
                <?=nav()?>
            </div>
        </div>
    </div>
<?php

switch ($_GET['p']){
    case "edit":
        include_once("pages/edit_lang.html");
        break;
    case "add":
        include_once ("pages/add_lang.php");
        break;
    case "upgradelang":
        include_once ("pages/updatelang.html");
        break;
    case "export":
        include_once ("pages/exportlang.php");
        break;
    default:
        include_once ("pages/edit_lang.html");
        break;
}

?>

    </body>
</html>

<?php
session_destroy();
$db->disconnect();
?>
