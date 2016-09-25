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
        <header>
            <nav id="navigation-menu">
                <a href="index.php" {selected_menu_home} title="Despre">Despre</a>
                <a href="problems.php?page=1" {selected_menu_problems} title="Probleme">Probleme</a>
                <a href="progress.php" {selected_menu_progress} title="Progres">Progres</a>
                <a href="greasemonkey.php" {selected_menu_gm} title="Script GreaseMonkey">GreaseMonkey</a>
                <a href="contact.php" {selected_menu_contact} title="Contact">Contact</a>
            </nav>

            <div id="info_panel">
                <a href="rss.xml"><img src="images/icon_rss.png" alt="RSS Feed" title="RSS Feed" /></a>
            </div>

            <div id="logo">
                <a href="problems.php?page=1"><img src="images/logo_3_r.png" alt="Project Euler .net" /></a>
            </div>
        </header>

        <div id="content">
            {page_content}
        </div>

        <footer>
            Project Euler RO v{site_version}
            <br />
            <a href="copyright.php">Copyright &copy; 2012 - 2016. Informatii despre copyright</a>
        </footer>
    </div>
</body>
</html>