<form method="post" action="login.php">
	<div class="line">
		<label for="username">Email</label>
		<input type="text" name="username" id="username" placeholder="netID@cornell.edu"/>
        <label class="error">test error message</label>
	</div>
	<div class="line">
        <label for="password">Password</label>
		<input type="password" name="password" id="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"/>
        <label class="error"></label>
	</div>
	<div class="line">
		<button type="submit">Login</button>
	</div>
    <div class="line">
        <label id="forgot"><a href="forgot.php">Forgot password?</a></label>
	</div>
    <div class="line">
    	<label id="new"><a href="signup.php">New user? Sign up!</a></label>
	</div>
</form>