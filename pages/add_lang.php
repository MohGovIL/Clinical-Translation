<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <form action="pages/save.php" method="post" id="langaddform">
                <section>
                    <p class="title">הכנס קבוע:</p>
                    <input required name="constants" value="<?=$_SESSION['constants']?>" class="filedtext constants" type="text">
                </section>
                <section>
                    <p class="title">הכנס תרגום:</p>
                    <input required name="definitions" value="<?=$_SESSION['definitions']?>" class="filedtext definitions" type="text">
                </section>
                <input type="submit" value="שמור">
            </form>
        </div>
    </div>
</div>
<?php
if(isset($_GET['err']))
{
    $error = array(
        1 => "הקבוע קיים במערכת",
        2 => "אחד מהפרמטרים מכיל ערך ריק",
        3 => "הקבוע אינו יכול להכיל תו בעברית",
    );

    if($error[$_GET['err']]){
        $error_alert = 'alertify.alert("שגיאה", "'.$error[$_GET['err']].'");';
    }

    echo <<<html
<script>

alertify.defaults.glossary.ok = "אישור";
{$error_alert}

</script>
html;
}

if(isset($_GET['success']))
{
    echo <<<html
<script>


alertify.success('התרגום הוכנס בהצלחה');

</script>
html;
}
?>

<script>
    history.pushState(null, "", location.href.split("&")[0]);
</script>

