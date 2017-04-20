
<?php
$message ='';
if(isset($_POST['submit'])){

    $mysqlroot = $_POST['username'];
    $mysqlrootpass = $_POST['password'];
    $translationDB = $_POST['database'];

    $target_dir = "";
    $target_file = $target_dir . 'clinikal_translation.csv';
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Allow certain file formats
    if($imageFileType != "csv") {
        $message = "Sorry, only CSV files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {

        // Check if file already exists
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $message = "The file has been uploaded.";
            $output = array();
            /*$old_path = getcwd();
            chdir('/home/amiel/projects/translation');*/
            $output = shell_exec("bash updateTranslation.sh $mysqlroot $mysqlrootpass $translationDB");

           // print_r($output ? 'yyy' : 'nnn');
            $file = 'clinikal_translation.sql';
            if ($output && file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        } else {
            $message  = "Sorry, there was an error uploading your file.";
        }
    }

}

?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
    <title>Clinikal translation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
    <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
</head>
<body>
<div class="container">
    <header>
        <h1>Clinikal translaion <span>sql file generator</span></h1>
    </header>
    <section>
        <div id="container_demo" >
            <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
            <div id="wrapper">
                <div id="login" class="animate form">
                    <form method="post" autocomplete="on" enctype="multipart/form-data" >
                        <h1>CSV -> SQL</h1>
                        <p>
                            <label for="username" class="uname" data-icon="u" > Mysql admin name </label>
                            <input id="username" name="username" required="required" value="superroot" type="text" />
                        </p>
                        <p>
                            <label for="password" class="youpasswd" data-icon="p"> Mysql admin password </label>
                            <input id="password" name="password" required="required" value="superroot1" type="password"  />
                        </p>
                        <p>
                            <label for="password" class="youdb"> Translation database </label>
                            <input id="database" name="database" required="required" value="openemr_translation" type="text"  />
                        </p>
                        <p>
                            <label for="password" required="required" class="youdb"> Upload csv file <small>must be with ^ separator instead , </small> </label>
                            <input id="database" name="fileToUpload" type="file"  />
                        </p>
                        <p class="login button">
                            <input type="submit" name="submit" onclick="showLoader()" value="generate" />
                        </p>
                    </form>
                    <div id="loading" style="display: none; text-align: center">
                       <img src="images/gears.svg">
                    </div>
                    <p style="color: red;text-align: center">
                        <?php echo $message; ?>
                    </p>
                </div>

            </div>
        </div>
    </section>
</div>
<script>
    function showLoader() {
        var divLoading = document.getElementById('loading');
        divLoading.style.display = 'block';
    }
</script>
</body>
</html>
