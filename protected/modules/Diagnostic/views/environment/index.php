<?php 
$test = str_replace('Controller','',get_class($this));
echo $message;?>
<?php if(isset($model)) {?>
<div class="alert alert-success message" style="display: none;"></div>
<div id="env-block-form">
<?php echo $this->renderPartial('_form', array('model'=>$model),false, true);  ?>
</div>
<?php
}
?>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>