<form method="post" action="signup.php">
	<div class="line">
		<label for="fname">First Name</label>
		<input type="text" name="fname" id="fname" placeholder="First Name" required/>
        <label class="error"></label>
	</div>
	<div class="line">
    	<label for="lname">Last Name</label>
		<input type="text" name="lname" id="lname" placeholder="Last Name" required/>
        <label class="error"></label>
    </div>
    <div class="line">
		<label for="username">Email</label>
		<input type="text" name="username" id="username" placeholder="netID@cornell.edu" required/>
        <label class="error"></label>
	</div>
	<div class="line">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required/>
        <label class="error"></label>
	</div>
    <div class="line">
		<button type="submit">Sign Up</button>
    </div>
</form>