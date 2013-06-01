<?php
$test = str_replace('Controller','',get_class($this));
?>
<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Content's base.php</h3>
  </div>
  <div class="modal-body" id="<?php echo $test?>modal-body">
    <pre id="baseConfigContent">
    <?php if(isset($config)) {?>
    <?php var_export($config);?>
    <?php }?>
    </pre>
    
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Close</button>    
  </div>
</div>
<ul><li id="<?php echo $test.'-content'?>"><?php echo $message;?></li></ul>

<div class="text-center <?php echo isset($config)?'':'hide';?>" id="baseConfigLink">
    <a data-toggle="modal" class="btn btn-primary" href="#responsive">Show base.php</a>
</div>

<script type="text/javascript">
/*
jQuery('#baseConfigLink a.reloadBaseConfig').on('click',function(){
    jQuery.ajax({
        'type':'post',
        'dataType':'html',
        'url':'<?php echo $this->createUrl('reloadContent');?>',
        'cache':false,
        'data':null,
        success: function(response) {
            if(response!=''){
                alert(reponse['config']);
                jQuery('#baseConfigContent').html(response['config']);
            }
        }
    });
    return false;
});*/
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>
