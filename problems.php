<?php
	const MAX_PROBLEMS_PER_PAGE = 50;
	const MAX_TAGS_PER_COLUMN = 4;

	require_once ('include/db.class.php');

	$db = new DBConn ();

	$tagsFromDB = array ();

	$nrPages = 0;

	function getNrPages ()
	{
		global $db;

		$r = $db->executeQuery ('SELECT COUNT(*) AS \'count\' FROM translations');

		if (! $r)
		{
			$db->closeConnection ();
			
			die ('ERROR1');
		}

		$row = $db->nextRowAssoc ();

		$count = (int) $row['count'];

		$count = ($count % MAX_PROBLEMS_PER_PAGE == 0) ? ($count / MAX_PROBLEMS_PER_PAGE) : (floor ($count / MAX_PROBLEMS_PER_PAGE) + 1);

		return $count;
	}
	
	function isValidPage ()
	{
		global $nrPages;

		if (! isset ($_GET['page']))
		{
			return false;
		}

		if (strlen ($_GET['page']) > 2)
		{
			return false;
		}

		if (! is_numeric ($_GET['page']))
		{
			return false;
		}

		$page = (int) $_GET['page'];

		if ($page < 1 OR $page > $nrPages)
		{
			return false;
		}

		return true;
	}

	function getCleanTags ()
	{
		global $tagsFromDB;

		$tags = array ();

		foreach ($tagsFromDB as $tagDB)
		{
			$tag_id = 'tag' . $tagDB;

			if (isset ($_GET[$tag_id]))
			{
				if ($_GET[$tag_id] == '1')
				{
					$tags[] = (int) $tagDB;
				}
			}
		}

		return $tags;
	}

	//will return a string containing the GET which the user typed in his address bar.
	// it will have the following structure: "arg1=val1&arg2=val2&arg3=val3"
	function getRawGET ()
	{		
		//WARNING: 'REQUEST_URI' is NOT supported (by default) in IIS
		$rawGET = explode ('?', $_SERVER['REQUEST_URI']);

		return $rawGET[1];
	}

	function fillTagsFromDB ()
	{
		global $db;
		global $tagsFromDB;

		$r = $db->executeQuery ('SELECT tag_id FROM tags ORDER BY tag_id ASC');

		if (! $r)
		{
			$db->closeConnection ();

			die ('ERROR2');
		}

		$row = $db->nextRowAssoc ();

		while ($row !== FALSE)
		{
			$tagsFromDB[] = (int) $row['tag_id'];

			$row = $db->nextRowAssoc ();
		}
	}

	function getRebuiltGET ()
	{
		global $nrPages;

		$cleanTags = getCleanTags ();

		if (count ($cleanTags) == 0)
		{
			$rebuiltGET = 'page=' . (isValidPage ($nrPages) ? $_GET['page'] : '1');
		}
		else
		{
			$rebuiltGET = 'tag' . implode ('=1&tag', $cleanTags) . '=1' . '&page=' . (isValidPage ($nrPages) ? $_GET['page'] : '1');
		}

		return $rebuiltGET;
	}

	//returns TRUE if GET is valid, FALSE otherwise
	function isValidGET ()
	{
		//check for arrays in _GET
		foreach ($_GET as $element)
		{
			if (is_array ($element))
			{
				return false;
			}
		}

		global $nrPages;

		//check the "page" argument
		$nrPages = getNrPages ();
		if (! isValidPage ())
		{
			return false;
		}

		//check for very obvious foreign arguments (if it's not "page" or it doesn't start with "tag"... well, too bad)
		foreach ($_GET as $key => $value)
		{
			if ($key != 'page' AND strncmp ($key, 'tag', 3) != 0)
			{
				return false;
			}
		}

		fillTagsFromDB ();

		$rebuiltGET = getRebuiltGET ();

		$rawGET = getRawGET ();

		return (strlen ($rawGET) == strlen ($rebuiltGET));
	}

	function getPagination ($nrPages, $for_page)
	{
		$cleanTags = getCleanTags ();

		$output = '<div class="pagination">';

		for ($i = 1; $i <= $nrPages; $i++)
		{
			if ($for_page == $i)
			{
				$output .= '<a href="#" class="current">' . $for_page . '</a>';
			}
			else
			{
				if (count ($cleanTags) > 0)
				{
					$output .= '<a href="problems.php?tag' . implode ('=1&amp;tag', $cleanTags) . '=1&amp;page=' . $i . '" title="Mergi la pagina ' . $i . '">' . $i . '</a>';
				}
				else
				{
					$output .= '<a href="problems.php?page=' . $i . '" title="Mergi la pagina ' . $i . '">' . $i . '</a>';
				}
			}
		}

		if (count ($cleanTags) == 0)
		{
			$output .= '<span>&nbsp;&nbsp;&nbsp;Mergi la problema: <input type="text" style="width:30px;" onkeypress="if (event.keyCode==13) location.href=\'problem.php?id=\'+this.value;" /></span>
			';
		}

		$output .= '</div>';

		return $output;
	}

	if (! isValidGET ())
	{
		$rebuiltGET = getRebuiltGET ();

		header ("Location: problems.php?$rebuiltGET");
	}

	require ('header.php');

	echo '<div id="content">
		<h2>Probleme</h2>
		<p>Aici găsiți traducerile problemelor. Problemele care sunt marcate ca fiind netraduse vor primi traducerea în cel mai scurt timp posibil. Mai multe probleme vor fi adaugate pe măsură ce apar pe <a href="http://projecteuler.net/">ProjectEuler.net</a>.</p>
		<p>Dacă aveți browser-ul Mozilla Firefox și extensia GreaseMonkey instalată, atunci vă recomand să instalați scriptul de traducere (link-ul e sus dreapta). În felul acesta, veți avea traducerile disponibile pe site-ul mamă, fără să fiți nevoiți să vizitați acest site. Un tutorial și un filmuleț demonstrativ sunt disponibile pe acea pagina, pentru o instalare cât mai ușoară.</p>
		<p>Puteți vizualiza pe o singură pagină atât <a href="showall.php?translated=yes">toate problemele traduse</a> cât și <a href="showall.php?translated=no">cele netraduse</a>.</p>';

	$r = $db->executeQuery ('SELECT DISTINCT ' .
							't.tag_id, t.tag_name, (SELECT COUNT(m.tag_id) FROM tag_mappings m WHERE t.tag_id = m.tag_id) AS \'occurrence\' ' .
							'FROM tags t ' .
							'LEFT JOIN tag_mappings m ON t.tag_id = m.tag_id');

	if (! $r)
	{
		die ('ERROR3');
	}

	echo '<form action="problems.php" method="get">
			<div id="tags-table">
				<table class="grid">
					<tbody>';

	$tagCount = $db->getRowCount ();

	$tableRowCount = ($tagCount % MAX_TAGS_PER_COLUMN == 0) ? ($tagCount / MAX_TAGS_PER_COLUMN) : (floor ($tagCount / MAX_TAGS_PER_COLUMN) + 1);

	$rowLimit = ($tagCount % MAX_TAGS_PER_COLUMN == 0) ? $tableRowCount : $tableRowCount - 1;

	for ($i = 1; $i <= $rowLimit; $i++)
	{
		echo '<tr>';

		for ($j = 1; $j <= MAX_TAGS_PER_COLUMN; $j++)
		{
			$tag = $db->nextRowAssoc ();

			echo '<td><input name=tag' . $tag['tag_id'] . ' type="checkbox" value="1" ' . (isset ($_GET['tag' . $tag['tag_id']]) ? 'checked' : '') . ' /><a href="problems.php?tag' . $tag['tag_id'] . '=1&amp;page=1">' . $tag['tag_name'] . ' (' . $tag['occurrence'] . ')</a></td>
			';
		}

		echo '</tr>';
	}

	if ($tagCount % MAX_TAGS_PER_COLUMN != 0)
	{
		$remainingTags = $tagCount - (($tableRowCount - 1) * MAX_TAGS_PER_COLUMN);

		echo '<tr>';

		for ($i = 1; $i <= $remainingTags; $i++)
		{
			$tag = $db->nextRowAssoc ();

			echo '<td><input name=tag' . $tag['tag_id'] . ' type="checkbox" value="1" ' . (isset ($_GET['tag' . $tag['tag_id']]) ? 'checked' : '') . ' /><a href="problems.php?tag' . $tag['tag_id'] . '=1&amp;page=1">' . $tag['tag_name'] . ' (' . $tag['occurrence'] . ')</a></td>';
		}

		for ($i = $remainingTags + 1; $i <= MAX_TAGS_PER_COLUMN; $i++)
		{
			echo '<td>&nbsp;&nbsp;</td>';
		}

		echo '</tr>';
	}

	echo '</tbody>
	</table>
	</div>
	<br />
	<input type="hidden" name="page" value="1" />
	<input id="submit-btn" type="submit" value="Filtrează" />
	</form>
	<br />';

	$page = (int) $_GET['page'];

	$limit = ($page - 1) * MAX_PROBLEMS_PER_PAGE;

	$cleanTags = getCleanTags ();

	$filtered = false;

	if (count ($cleanTags) == 0)
	{
		$query = 'SELECT ' .
				'problem_id, is_translated, title_romanian, title_english, publish_date, hits ' .
				'FROM translations ' .
				'ORDER BY problem_id ASC ' .
				'LIMIT ' . $limit . ', ' . MAX_PROBLEMS_PER_PAGE;
	}
	else
	{
		$query = 'SELECT DISTINCT ' .
				't.problem_id, t.is_translated, t.title_romanian, t.title_english, t.publish_date, t.hits ' .
				'FROM translations t ' .
				'JOIN tag_mappings m ON t.problem_id=m.problem_id ' .
				'WHERE m.tag_id IN (' . implode (', ', $cleanTags) . ') ' .
				'ORDER BY problem_id ASC';

		$filtered = true;
	}

	$r = $db->executeQuery ($query);

	if (! $r)
	{
		die ('ERROR4');
	}

	if ($db->getRowCount () == 0)
	{
		echo 'Această pagină nu există.';

		echo '<div class="clear"></div>
		</div>';

		require ('footer.php');

		echo '</div>
			</body>
			</html>';

		exit (0);
	}

	if ($filtered == TRUE)
	{
		$nrPages = ($db->getRowCount () % MAX_PROBLEMS_PER_PAGE == 0) ? ($db->getRowCount () / MAX_PROBLEMS_PER_PAGE) : ceil ($db->getRowCount () / MAX_PROBLEMS_PER_PAGE);
	}

	echo getPagination ($nrPages, $page);

	echo '<div class="clear"></div>
		<br />
		<table class="grid" style="width:1000px">
		<tr><th style="width:40px;"><strong><span style="color:#000;text-decoration:underline;">Nr</span></strong></th><th style="width:360px;"><b>Descriere / Titlu</b></th><th style="width:40px;"><b>Vizualizări</b></th></tr>';

	$db->seek ($limit);
	
	$printedProblems = 0;

	$row = $db->nextRowAssoc ();

	while ($row !== FALSE AND $printedProblems < MAX_PROBLEMS_PER_PAGE)
	{
		if ($row['is_translated'] == '0')
		{
			if (strlen ($row['title_romanian']) > 5)
			{
				echo '
				<tr><td style="height:30px;"><div style="text-align:center;"><b>' . $row['problem_id'] . '</b></div></td><td>(NETRADUS) ' . $row['title_romanian'] . '</td><td><div style="text-align:center;"><b>' . $row['hits'] . '</b></div></td></tr>';
			}
			else
			{
				echo '
				<tr><td style="height:30px;"><div style="text-align:center;"><b>' . $row['problem_id'] . '</b></div></td><td>(NETRADUS) ' . $row['title_english'] . '</td><td><div style="text-align:center;"><b>' . $row['hits'] . '</b></div></td></tr>';
			}
		}
		else
		{
			echo '
			<tr><td style="height:30px;"><div style="text-align:center;"><b>' . $row['problem_id'] . '</b></div></td><td><a href="problem.php?id=' . $row['problem_id'] . '" style="text-decoration:none;" title="Publicată în ' . $row['publish_date'] . '">' . $row['title_romanian'] . '</a></td><td><div style="text-align:center;"><b>' . $row['hits'] . '</b></div></td></tr>';
		}
		
		$printedProblems++;

		$row = $db->nextRowAssoc ();
	}

	$db->closeConnection ();
	
	echo '</table>
			<br />';

	echo getPagination ($nrPages, $page);

	echo '<div class="clear"></div>
		</div>';

	require ('footer.php');

	echo '</div>
		</body>
		</html>';
?>