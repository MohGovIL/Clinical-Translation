<?php
function nav(){
    echo <<< html
      <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <ul class="nav  navbar-nav">
                <li role="presentation" id="inheritance"><a href="index.php?p=edit">ערוך תרגום</a></li>
                <li role="presentation" id="list"><a href="index.php?p=add">הוסף תרגום</a></li>
            </ul>
        </div>
       </nav>
html;

}