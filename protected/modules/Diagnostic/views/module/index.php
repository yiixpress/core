<?php
$test = str_replace('Controller','',get_class($this));
echo $message;
?>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>