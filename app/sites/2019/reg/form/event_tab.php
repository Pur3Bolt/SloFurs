<div class="tab-pane container-fluid active mt-3" id="Event">
	<div class="row">
		<div class="col-md-9">
			<h5><?php echo L::register_form_description;?></h5>
			<div class="text-dark"><?php echo nl2br($event->description); ?></div>
		</div>
		<div class="col">
			<!-- date and time of event -->
			<h5><?php echo L::register_form_date;?></h5>
			<p class="text-dark"><?php echo $reg_model->convertViewable($event->event_start, 2); ?> -<br>
				<?php echo $reg_model->convertViewable($event->event_end, 2); ?>
			</p>
			<!-- registration times -->
			<h5><?php echo L::register_form_registration;?></h5>
			<?php
			$now=new DateTime();
				if(new DateTime($event->reg_end)<=$now){
					//registrations ended
					$color='btn-light';
					$text=L::register_form_ended.'<br>'.$reg_model->convertViewable($event->reg_start, 2).' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif(new DateTime($event->reg_start)<=$now){
					//regular registrations
					$color='btn-success';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)<=$now && $account!=null && $account->status>=PRE_REG){
					//pre-registrations
					$color='btn-success';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				else{
					//upcoming registrations
					$color='btn-light';
					$textDate=($account!=null&&$account->status>=PRE_REG)?
						$reg_model->convertViewable($event->pre_reg_start, 2):
						$reg_model->convertViewable($event->reg_start, 2);
					$text=L::register_form_upcoming.'<br>'.$textDate.' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
			?>
			<p class="text-dark"><?php echo $text; ?></p>
			<?php if(isset($event->location)&&strlen($event->location)>0): ?>
				<!-- event location -->
				<h5><?php echo L::register_form_location;?></h5>
				<p class="text-dark"> <a href="https://maps.google.com/?q=<?php echo $event->location;?>" target="_blank"><?php echo $event->location;?> <i class="far fa-external-link"></i></a></p>
			<?php endif; ?>
			<!-- gallery -->
			<h5><?php echo L::register_form_gallery;?></h5>
			<p class="text-dark"><?php echo L::register_form_galleryD;?><br>
			<?php if($event->gallery!=null): ?>
				<a href="<?php echo $event->gallery;?>" target="_blank"><?php echo L::register_form_galleryL;?> <i class="far fa-external-link"></i></a>
			<?php else: ?>
				<?php echo L::register_form_galleryNone;?>
			<?php endif; ?>
			</p>
			<!-- age restrictions -->
			<h5><?php echo L::register_form_age_h;?></h5>
			<?php
			$age=null;
				if($account!=null){
					$age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y');
				}?>
			<?php if($event->age==0): ?>
				<p class="text-dark"><?php echo L::register_form_age_none;?></p>

			<?php elseif($account==null): ?>
				<p class="text-dark"><?php echo L::register_form_age_noAcc.$event->restricted_age.L::register_form_age_okYears;?></p>

			<?php elseif($age>=$event->age): ?>
				<p class="text-dark"><?php echo L::register_form_age_ok.$event->age.L::register_form_age_okYears;?></p>

			<?php elseif($age<$event->age && $age>=$event->restricted_age): ?>
				<p class="text-warning"><?php echo L::register_form_age_restricted;?><br> <?php echo $event->restricted_text; ?></p>

			<?php else: ?>
				<?php $color='btn-light'; ?>
				<p class="text-danger"><?php echo L::register_form_age_notOk1.$event->restricted_age.L::register_form_age_notOk2.$age.L::register_form_age_notOk3;?></p>
			<?php endif; ?>
			<?php if($account!=null&&!$reg_model->checkProfile()){
				$color='btn-light';
			} ?>
			<!-- questions -->
			<h5><?php echo L::register_form_questions;?></h5>
			<p class="text-dark"><a href="mailto:slofurs@gmail.com" target="_blank"><?php echo L::register_form_email;?></a>, <a href="https://discord.gg/0eaoyLCJ7eiTMBaj" target="_blank">Discord <i class="far fa-external-link"></i></a> </p>
			<!-- FORM BUTTON -->
			<?php if($new_reg): ?>
				<button class="btn-block btn <?php echo $color; ?>" <?php if($color!='btn-success'||$age<$event->restricted_age){echo 'disabled';} else{echo 'data-toggle="modal" data-target="#register"';} ?>><?php echo L::register_form_buttonRegister;?></button>
				<?php if($account==null&&$color=='btn-success'): ?>
					<!-- log in before reg -->
					<p><a href="#" data-toggle="modal" data-target="#loginModal"><?php echo L::register_form_login;?></p>
				<?php elseif($account!=null&&!$reg_model->checkProfile()): ?>
					<!-- incomplete profile -->
					<p><a href="<?php echo URL;?>account/contact"><?php echo L::register_form_completeProfile;?></p>
				<?php endif; ?>
			<?php elseif($color=='btn-light'): ?>
				<button class="btn-block btn btn-light" data-toggle="modal" data-target="#register"><?php echo L::register_form_buttonView;?></button>
			<?php else: ?>
				<button class="btn-block btn btn-outline-primary" data-toggle="modal" data-target="#register"><?php echo L::register_form_buttonEdit;?></button>
			<?php endif; ?>

			<?php
				// registration form
				if($age>=$event->restricted_age && ($color=='btn-success' || new DateTime($event->reg_end)<=$now)){
					require 'app/sites/2019/reg/form/form_types/reg_form.php';
				}
				//payment form
				if(!$new_reg && $color=='btn-success'){
					if(isset($session)){
						require 'app/sites/2019/reg/form/form_types/pay_form.php';
					}
					elseif($event->pay_button==1 && $event->confirmed==1){
						echo '<button class="btn-block btn btn-success mt-2 disabled"><i class="far fa-check-circle"></i> Vstopnina plačana v celoti ('.$paid.'€)</button>';
					}
				}
			?>
		</div>
	</div>
</div>
<?php require 'app/sites/global/validate_form.php'; ?>
<?php if(isset($view_only)&&$view_only):?>
<script>
	$("#regForm :input").attr("disabled", true);
</script>
<?php endif;?>
<?php if(isset($_SESSION['paynow'])&&$_SESSION['paynow']==true): ?>
	<script>
		$(document).ready(function(){
			//$('#payButton').click();
		});
	</script>
	<?php //$_SESSION['paynow']=false; ?>
<?php endif; ?>
