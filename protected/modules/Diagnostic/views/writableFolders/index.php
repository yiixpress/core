<ul>
<?php
foreach($dirs as $dir => $ok)
    echo "<li>{$dir} is {$ok}</li>";
?>
</ul>
<?php
$test = str_replace('Controller','',get_class($this));
?>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>