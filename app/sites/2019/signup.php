<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::signup_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>signup" method="post">
			<label><?php echo L::signup_email;?></label>
			<input class="w3-input" type="email" name="email" placeholder="<?php echo L::signup_emailP;?>" required autofocus>
			<label><?php echo L::signup_username;?></label> <i class="far fa-info-circle" title="<?php echo L::signup_usernameI;?>"></i>
			<input class="w3-input" type="text" name="username" placeholder="<?php echo L::signup_usernameP;?>" required>
			<label><?php echo L::signup_pw;?></label>
			<input class="w3-input" id="pwd" type="password" name="password" placeholder="<?php echo L::signup_pwP;?>" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$" title="<?php echo L::signup_pwT;?>" required onkeyup="verifyPassword()">
			<label><?php echo L::signup_pw;?></label> <i class="w3-opacity w3-small"><?php echo L::signup_confirm;?></i> <i id="correct" class="far fa-times"></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="<?php echo L::signup_confirmP;?>" required onkeyup="verifyPassword()">
			<input class="w3-check" type="checkbox" required>
			<label><?php echo L::signup_privacy1;?> <a href="<?php echo URL.L::signup_privacy2;?>" target="_blank"><?php echo L::signup_privacy3;?> <i class="far fa-external-link"></i></a>.</label><br><br>
			<div class="w3-center">
				<button type="submit" id="btn" name="sign_up_acc" class="w3-button w3-round w3-border w3-border-blue" disabled="true"><?php echo L::signup_register;?></button><p>
			</div>
			<div>
				<?php echo L::signup_login;?> <a href="<?php echo URL;?>login"><?php echo L::signup_loginButton;?></a><br>
				Didn't receive the account confirmation email? <a href="<?php echo URL;?>signup/resend">Request a resend</a>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::signup_welcome;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::signup_desc1;?>
		</div>
		<div class="w3-container">
			<b class="w3-left-align"><?php echo L::signup_desc2;?>
				<ul>
					<li><?php echo L::signup_pwCond1;?></li>
					<li><?php echo L::signup_pwCond2;?></li>
					<li><?php echo L::signup_pwCond3;?></li>
				</ul>
			</b>
			<p><?php echo L::signup_desc3;?></p>
			<h3 class="w3-text-red"><?php echo L::signup_desc4;?></h3>
		</div>
	</div>
</div>
<script>
function verifyPassword(){
	regex=/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$/;
	if($("#pwd").val()==$("#pwdC").val()&&$("#pwd").val().length>=8&&$("#pwd").val().match(regex)){
		$("#btn").prop("disabled", false);
		if($("#correct").hasClass("fa-times")){
			$("#correct").removeClass("fa-times").addClass("fa-check");
		}
	}
	else{
		$("#btn").prop("disabled", true);
		if($("#correct").hasClass("fa-check")){
			$("#correct").removeClass("fa-check").addClass("fa-times");
		}
	}
}
</script>
