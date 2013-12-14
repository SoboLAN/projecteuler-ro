<?php
	define ('ADMIN_USER', 'eulerroadmin');
	define ('ADMIN_PASS', 'eulerropass9ijn');
	
	function display_form ()
	{
		$output = '<form name="login" action="body.content.php" method="post">
					<p>Username: <input type="text" name="username" size="30" maxlength="30" /></p>
					<p>Password: <input type="password" name="password" size="30" maxlength="30" /></p>
					<p>Problem ID: <input type="text" name="id" size="10" maxlength="10" /></p>
					<p><textarea name="content" rows="10" cols="80"></textarea></p>
					<input type="submit" name="submit" value="Login" />
					<input type="hidden" name="submitted" value="TRUE" />
					</form>';
		
		return $output;
	}
	
	require ('header.php');
	
	echo '<div id="content">
		<h2>Body Content Checker</h2>
		<br />';
	
	if (! isset ($_POST['submitted']))
	{
		$form = display_form ();
		
		echo $form . '<div style="clear:both;"></div>
					</div>';

		require ('footer.php');

		echo '</div>
				</body>
				</html>';
				
		exit (0);
	}
	
	if ($_POST['username'] !== ADMIN_USER OR $_POST['password'] !== ADMIN_PASS)
	{
		echo '<div style="text-align:center;" class="noprint">
				<p>Username sau parola gresite.</p>
				</div>
			</div>';

		require ('footer.php');

		echo '</div>
			</body>
			</html>';
		
		exit (0);
	}
	
	require_once ('include/db.class.php');
	
	$dbconn = new DBConn ();
	
	$inputContent = $_POST['content'];
	
	$id = (int) $_POST['id'];
	
	$r = $dbconn->executeQuery ('SELECT text_english FROM translations WHERE problem_id=' . $id);
	
	if (! $r)
	{
		die ('DB ERROR');
	}
	
	$row = $dbconn->nextRowAssoc ();
	
	$dbContent = $row['text_english'];

	$strippedInput = preg_replace ('/\s+/', '', $inputContent);
	
	//escaped because the input version is also escaped.. don't know why...
	$strippedDB = $dbconn->escape (preg_replace ('/\s+/', '', $dbContent));
	
	$dbconn->closeConnection ();
	
	echo '<b>Input Length</b>: ' . strlen ($strippedInput) . '<br /><b>DB Length</b>: ' . strlen ($strippedDB);
	
	echo '<br /><br />';
	
	echo '<b>Input:</b><br /><br />' . htmlspecialchars ($strippedInput) . '<br /><br /><b>DB</b>:<br /><br />' . htmlspecialchars ($strippedDB);

	echo '<div style="clear:both;"></div>
		</div>';
	
	require ('footer.php');
	
	echo '</div>
		</body>
		</html>';
	
?>