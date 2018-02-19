<html>
<head>
	<title>FMI</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
    rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->configs['static_urls']; ?>/css/style.css">
</head>
<body>
	<div class="navigation">
		<div>
			<ul>
				<?php if(array_key_exists('User', $_SESSION)) { ?>
				<li><i class="material-icons">account_circle</i><?php echo $_SESSION['User']['email']; ?><?php if($_SESSION['User']['is_admin']) echo '(admin)'; ?></li>
				<li><i class="material-icons">exit_to_app</i><a href="<?php echo $this->configs['base_path']."/logout" ?>">Log out</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>