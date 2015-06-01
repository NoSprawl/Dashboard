<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body style="margin: 0; padding: 0; background: #eee;">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td style="background: #343434; padding-top: 4px; padding-bottom: 4px;" width="10%"></td>
				<td style="background: #343434; padding-top: 4px; padding-bottom: 4px;">
					<a href="http://nosprawl.com"><img style="display: block; float: left;" height="43" width="90" src="http://s3.amazonaws.com/us-east-1-resources-nosprawl/email_logo.png"></a>
					<div style="color: #fff; font-family: Helvetica, Arial; float: left; font-size: 1.4em; padding-top: 13px; padding-left: 13px; position: relative;">Welcome</div>
				</td>
				<td style="background: #343434; padding-top: 4px; padding-bottom: 4px;" width="10%"></td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td style="background: #eee; padding-top: 4px; padding-bottom: 4px;" width="10%"></td>
				<td style="background: #eee; padding-top: 4px; padding-bottom: 4px;">
					<div style="color: #fff; font-family: Helvetica, Arial; font-size: 1em; padding-top: 20px; padding-bottom: 20px; line-height: 1.7em; position: relative; color: #343434;">Hi {{ $data['name'] }},<br />You&rsquo;ve been added as a subuser on {{ User::find($data['parent_user_id'])->company_name }}&rsquo;s NoSprawl account.</div>
					
					<div style="display: block; font-family: Helvetica, Arial; font-size: 1em; color: #343434; line-height: 1.7em; margin-top: 0; padding-top: 0;">Please <a href="http://my.nosprawl.com/signup/<?= $data['user_confirmation_token']; ?>">activate your account now</a>.</div>
					<div style="display: block; font-family: Helvetica, Arial; font-size: 1em; color: #343434; line-height: 1.7em; margin-top: 0; padding-top: 0;"><br />Thank you,<br />The NoSprawl Team</div>
				</td>
				<td style="background: #eee; padding-top: 4px; padding-bottom: 4px;" width="10%"></td>
			</tr>
		</table></body>
</html>