<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8" />

    <title>Project Euler - Traduceri in Romana</title>
   	<link rel="shortcut icon" href="favicon.ico" />
   	{css_file}
    
    {mathjax_script}
</head>

<body>
    {google_analytics_script}
    <div id="container">

        <div id="nav" class="noprint">
            <ul>
                <li {selected_menu_home}><a href="index.php" title="Despre">Despre</a></li>
                <li {selected_menu_problems}><a href="problems.php?page=1" title="Probleme">Probleme</a></li>
                <li {selected_menu_progress}><a href="progress.php" title="Progres">Progres</a></li>
                <li {selected_menu_gm}><a href="greasemonkey.php" title="Script GreaseMonkey">GreaseMonkey</a></li>
                <li {selected_menu_contact}><a href="contact.php" title="Contact">Contact</a></li>
            </ul>
        </div>

        <div id="info_panel">
            <a href="rss.xml"><img src="images/icon_rss.png" alt="RSS Feed" title="RSS Feed" /></a>
        </div>

        <div id="logo" class="noprint">
            <a href="problems.php?page=1"><img src="images/logo_3_r.png" alt="Project Euler .net" /></a>
        </div>

        <div id="content">
            {page_content}
        </div>

        <div id="footer" class="noprint">
            <a href="copyright.php">Copyright &copy; 2012 - 2014. Informatii despre copyright</a>
        </div>
    </div>
</body>
</html>