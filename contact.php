<?php
	require ('header.php');
	
	$htmlout = '<div id="content">
		<h2>Contact</h2>';
		
	function getCaptcha ()
	{
		$out = '<script type="text/javascript">
			var RecaptchaOptions = {
			theme : \'white\'
			};
			</script>';
	
		require_once ('recaptcha/recaptchalib.php');

		$publicKey = '6Ld5wdYSAAAAAFVlLDb-g0utIKaJGn6vbyXIrnGJ';
		
		$out .= recaptcha_get_html ($publicKey);
		
		return $out;
	}

	function getForm ()
	{
		$output = '<p>Poți folosi acest formular pentru a raporta probleme tehnice sau pentru a sugera o imbunătățire a unei traduceri.
		<br />
		<strong>Mesaje cu orice alt subiect vor fi ignorate.</strong>
		</p>
		<form name="form" method="post" action="contact.php">
		<table class="grid" style="width:700px;">
			<tr><th><div style="text-align:right;">
				<input type="checkbox" name="confirm" onclick="javascript:if(document.form.confirm.checked==true) document.form.submit.disabled=false; else document.form.submit.disabled=true;" /></div></th><td><span style="font-size:80%;color:#d00;">Confirm că acest mesaj e legat de o problemă tehnică sau o sugestie de corectare a unei traduceri.</span></td></tr>
			<tr><th><div style="text-align:right;">Subiect:</div></th><td><input type="text" name="subject"'; if (isset ($_POST['subject'])) { $output .= "value=\"{$_POST['subject']}\""; } $output .= ' size="40" maxlength="40" /></td></tr>
			<tr><th><div style="text-align:right;">Email-ul tau:</div></th><td><input type="text" name="email"'; if (isset ($_POST['email'])) { $output .= "value=\"{$_POST['email']}\""; } $output .= ' size="40" maxlength="40" /> (opțional, daca dorești un răspuns)</td></tr>
			<tr><th style="vertical-align:top;"><div style="text-align:right;">Mesaj:</div></th><td><textarea name="message" cols="60" rows="15"></textarea></td></tr>
		</table>
		<input type="hidden" name="is_submitted" value="TRUE" />
		<br /><br />';
		
		$output .= getCaptcha ();
	
		$output .= '<br /><br />
				<div style="text-align:center;"><input type="submit" name="submit" value="Trimite Mesajul" disabled="true"/></div>
				<br />
				</form>';
				
		return $output;
	}

	if (! isset ($_POST['is_submitted']))
	{
		$htmlout .= getForm ();
		
		$htmlout .= '</div>
				</div>
				</body>
				</html>';
		
		echo $htmlout;
		
		exit (0);
	}
	
	require_once ('recaptcha/recaptchalib.php');
	$privatekey = '6Ld5wdYSAAAAAPfUNLGCSqV51qncTnCUaYl5zJa8';
	$resp = recaptcha_check_answer ($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_POST["recaptcha_challenge_field"],
									$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid)
	{
		$htmlout .= '<span style="font-size:130%;color:#d00;text-decoration:bold">Codul CAPTCHA introdus nu e corect.</span>';
		
		$htmlout .= getForm ();
		
		$htmlout .= '</div>
				</div>
				</body>
				</html>';
		
		echo $htmlout;
		
		exit (0);
	}
	
	function spamcheck($field)
	{
		//filter_var() sanitizes the e-mail
		//address using FILTER_SANITIZE_EMAIL
		$field=filter_var($field, FILTER_SANITIZE_EMAIL);

		//filter_var() validates the e-mail
		//address using FILTER_VALIDATE_EMAIL
		if(filter_var($field, FILTER_VALIDATE_EMAIL))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	$from = 'nobody@example.com';
	
	if (strlen ($_POST['email']) > 0)
	{
		$from_check = spamcheck ($_POST['email']);
		if ($from_check == FALSE)
		{
			$htmlout .= '<span style="font-size:130%;color:#d00;text-decoration:bold">Nice try.</span>';
			
			$htmlout .= getForm ();
			
			$htmlout .= '</div>
					</div>
					</body>
					</html>';
			
			echo $htmlout;
			
			exit (0);
		}
		
		$from = $_POST['email'];
	}
	
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$to = 'support@projecteuler.javafling.org';
	
	if (strlen ($subject) < 4 OR strlen ($message) < 10)
	{
		$htmlout .= '<span style="font-size:130%;color:#d00;text-decoration:bold">Totuși scrie ceva.</span>';
		
		$htmlout .= getForm ();
		
		$htmlout .= '</div>
				</div>
				</body>
				</html>';
		
		echo $htmlout;
		
		exit (0);
	}
	
	$success = mail ($to, $subject, $message, "From:" . $from);
	
	if ($success == TRUE)
	{
		$htmlout .= '<strong>Mesajul a fost trimis cu succes.</strong></div>
					</div>
					</body>
					</html>';
	}
	else
	{
		$htmlout .= '<strong>Mesajul nu a fost trimis.</strong></div>
					</div>
					</body>
					</html>';
	}

	echo $htmlout;	
?>