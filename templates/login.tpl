<?php include 'header.tpl'; ?>

<div class="login-wrapper">
</div>
<div class="login">
	<div class="login-errors">
		<?php if(isset($_GET['error'])) {echo $_GET['error'];} ?>
	</div>
	<form method="POST" action="" >
		<div>
			<input name="email" placeholder="Email" />
		</div>
		<div>
			
			<input type="password" name="password" placeholder="Password" />
		</div>
		<div>
			<input type="submit" value="Log in" />
		</div>
	</form>
</div>

<?php include 'footer.tpl'; ?>
