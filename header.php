<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8" />

    <title>Project Euler - Traduceri in Romana</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>

<?php

$enable_analytics = false;

if ($enable_analytics)
{
    require_once 'analytics.php';
    
    echo getAnalyticsScript();
}
?>

<div id="container">

<div id="nav" class="noprint">
    <ul>

    <?php
        if (strpos ($_SERVER['PHP_SELF'], 'page') !== FALSE OR
            strpos ($_SERVER['PHP_SELF'], 'problem') !== FALSE OR
            strpos ($_SERVER['PHP_SELF'], 'showall') !== FALSE)
        {
            echo '<li><a href="index.php" title="Despre">Despre</a></li>
                <li id="current"><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li><a href="progress.php" title="Progres">Progres</a></li>
                <li><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>';
        }
        elseif (strpos ($_SERVER['PHP_SELF'], 'index') !== FALSE)
        {
            echo '<li id="current"><a href="index.php" title="Despre">Despre</a></li>
                <li><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li><a href="progress.php" title="Progres">Progres</a></li>
                <li><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>';
        }
        elseif (strpos ($_SERVER['PHP_SELF'], 'greasemonkey') !== FALSE)
        {
            echo '<li><a href="index.php" title="Despre">Despre</a></li>
                <li><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li><a href="progress.php" title="Progres">Progres</a></li>
                <li id="current"><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>';
        }
        elseif (strpos ($_SERVER['PHP_SELF'], 'contact') !== FALSE)
        {
            echo '<li><a href="index.php" title="Despre">Despre</a></li>
                <li><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li><a href="progress.php" title="Progres">Progres</a></li>
                <li><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li id="current"><a href="contact.php" title="Contact">Contact</a></li>';
        }
        
        elseif (strpos ($_SERVER['PHP_SELF'], 'progress') !== FALSE)
        {
            echo '<li><a href="index.php" title="Despre">Despre</a></li>
                <li><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li id="current"><a href="progress.php" title="Progres">Progres</a></li>
                <li><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>';
        }
        else
        {
            echo '<li><a href="index.php" title="Despre">Despre</a></li>
                <li><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li><a href="progress.php" title="Progres">Progres</a></li>
                <li><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>';
        }
    ?>
    </ul>
</div>

<div id="info_panel"><a href="rss.xml"><img src="images/icon_rss.png" alt="RSS Feed" title="RSS Feed" /></a></div>

<div id="logo" class="noprint">
   <a href="problems.php?page=1"><img src="images/logo_3_r.png" alt="Project Euler .net" /></a>
</div>
