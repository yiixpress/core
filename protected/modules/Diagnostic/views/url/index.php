<?php
$test = str_replace('Controller','',get_class($this));
?>
<div>
<h3>List of sites</h3>
<div class="alert alert-<?php echo isset($array['error'])?'error':'info hide';?>">
<?php echo isset($array['error'])?$array['error']:'';?>
</div>
<ul>
<?php
    if(isset($array['site']) && !empty($array['site'])) {
        foreach($array['site'] as $item) {
?>
        <li><?php echo $item['name']?></li>
<?php            
        }
    }
?>
</ul>
</div>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>
