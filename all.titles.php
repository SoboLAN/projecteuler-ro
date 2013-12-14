<?php
	require ('include/db.class.php');
	
	$dbconn = new DBConn ();
	
	$r = $dbconn->executeQuery ('SELECT problem_id, title_english, title_romanian FROM translations ORDER BY problem_id ASC');
	
	if (! $r)
	{
		$dbconn->closeConnection ();
		
		die ('DB ERROR');
	}
	
	$htmlout = '<!DOCTYPE html>
			<html>
				<head>
					<meta charset="utf-8" />
					<style type="text/css">
					#titles-table
					{
						width: 70%;
						border: 1px solid;
						background-color: #F4D9D9;
					}

					#titles-table td
					{
						border: 1px solid #9aa;
						text-align: center;
					}
					</style>
				</head>
				<body>
				<table id="titles-table">
				<tr>
					<td style="text-decoration: underline"><strong>Problem</strong></td>
					<td style="text-decoration: underline"><strong>Title English</strong></td>
					<td style="text-decoration: underline"><strong>Title Romanian</strong></td>
				</tr>';
	
	$row = $dbconn->nextRowAssoc ();
	
	while ($row !== FALSE)
	{
		$htmlout .= '<tr>' . 
					'<td>' . $row['problem_id'] . '</td>' . 
					'<td>' . $row['title_english'] . '</td>' . 
					'<td>' . ($row['title_romanian']) . '</td>
					</tr>';
		
		$row = $dbconn->nextRowAssoc ();
	}
	
	$dbconn->closeConnection ();
	
	$htmlout .= '</table>
				</body></html>';
				
	echo $htmlout;
?>