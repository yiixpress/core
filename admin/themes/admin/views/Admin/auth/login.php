<?php
$this->layout = '//layouts/master';
?>
<div id="login">

<!-- Box -->
	<div class="form-signin">
		<h3>Sign in to Your Account</h3>

		<!-- Row -->
		<div class="row-fluid row-merge">

			<!-- Column -->
			<div class="span12">
				<div class="inner">

					<?php $form = $this->beginWidget('CActiveForm', array(
						'id'                     => 'login-form',
						'enableClientValidation' => true,
						'clientOptions'          => array(
							'validateOnSubmit' => true,
						),
						'htmlOptions'            => array(
							'class' => 'form form-horizontal',
						),
						'focus'                  => array($user, 'email'),
					)); ?>


					<?php
					if (errorHandler()->hasErrors(NULL, $this))
						$this->widget('InfoBox', array('type' => 'error', 'message' => '{errors}', 'heading' => ''))
					?>

						<label class="strong" for="<?php echo CHtml::activeId($user, 'login'); ?>">Username or Email</label>

						<?php echo $form->textField($user, 'login', array('class' => 'input-block-level')); ?>
						<?php echo $form->error($user, 'login'); ?>

						<label class="strong"
						       for="<?php echo CHtml::activeId($user, 'password'); ?>">Password</label>

						<?php echo $form->passwordField($user, 'password', array('class' => 'input-block-level')); ?>
						<?php echo $form->error($user, 'password'); ?>

						<label class="strong" for="dummy"></label>

						<div class="uniformjs">
							<label class="checkbox">
								<input type="checkbox" name="remember" id="remember"/> Remember me on this computer
							</label>
						</div>

						<div class="row-fluid">
							<div class="span5 center">
								<?php echo CHtml::submitButton('Sign in', array('class' => 'btn btn-block btn-primary')); ?>
							</div>
						</div>

					<?php $this->endWidget(); ?>

				</div>
			</div>
			<!-- // Column END -->
		</div>
		<!-- // Row END -->
	</div>
	<!-- // Box END -->
</div>

<?php
cs()->registerScript('set-body-class','$("body").addClass("login")', CClientScript::POS_READY);
?>