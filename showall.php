<?php
	if (! isset ($_GET['translated']))
	{
		header ("Location: showall.php?translated=yes");
	}

	$translated = $_GET['translated'];

	if ($translated !== 'yes' && $translated !== 'no')
	{
		header ("Location: problems.php?page=1");
	}

	require ('header.php');

	require_once ('include/db.class.php');

	$dbconn = new DBConn ();

	$r = $dbconn->executeQuery ('SELECT problem_id, publish_date, ' . ($translated == 'yes' ? 'text_romanian' : 'text_english') .
								' FROM translations' . 
								' WHERE is_translated=' . ($translated == 'yes' ? '1' : '0') .
								' ORDER BY problem_id ASC');

	if (! $r)
	{
		die ('SITE ERROR');
	}

	$htmlout = '<div id="content">
				';

	$row = $dbconn->nextRowAssoc ();

	while ($row !== FALSE)
	{
		if ($translated == 'yes')
		{
			$htmlout .= '<h2><a href="problem.php?id=' . $row['problem_id'] . '">Problema ' . $row['problem_id'] . '</a></h2>
						<div style="color:#666;font-size:80%;">' . $row['publish_date'] . '</div>
						<div style="border-bottom:1px solid #999;padding-bottom:20px;margin-bottom:20px;" class="problem_content">
						' . $row['text_romanian'] . '
						</div>
						';
		}
		else
		{
			$htmlout .= '<h2>Problema ' . $row['problem_id'] . '</h2>
						<div style="color:#666;font-size:80%;">' . $row['publish_date'] . '</div>
						<div style="border-bottom:1px solid #999;padding-bottom:20px;margin-bottom:20px;" class="problem_content">
						' . $row['text_english'] . '
						</div>
						';
		}

		$row = $dbconn->nextRowAssoc ();
	}

	$dbconn->closeConnection ();

	echo $htmlout;

	echo '</div>';
	require ('footer.php');
	echo '</div>
			</body>
			</html>';
?>