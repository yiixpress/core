<?php
$test = str_replace('Controller','',get_class($this));

/**
* Format some keywords in the content
*/
$keywords = array(
    // bad result
    'NOT FOUND' => '<span class="label label-important">not found</span>',
    'NOT OK' => '<span class="label label-important">NOT OK</span>',
    'FAIL' => '<span class="label label-important">fail</span>',
    'INVALID' => '<span class="label label-important">invalid</span>',
    // good result
    'FOUND' => '<span class="label label-success">found</span>',
    'OK' => '<span class="label label-success">OK</span>',
    'SUCCESSFUL' => '<span class="label label-success">successful</span>',
    'VALID' => '<span class="label label-success">valid</span>',
);

$content = strtr($content, $keywords);
?>
<div class="alert alert-<?php echo ($this->successful ? 'info' : 'error') ?>">
    <?php echo $this->title;?>
</div>
<div id="<?php echo $test.'-result';?>">
    <div class="content <?php echo ($this->successful ? 'success' : 'error') ?>" id="<?php echo $test.'-content';?>">
        <?php echo $content;?>
    </div>
</div>

<p align="center" id="<?php echo $test;?>">
    <a id="<?php echo $test.'-check-again';?>" class="double-check btn btn-<?php echo ($this->successful ? 'primary' : 'danger') ?>" href="javascrip:;">Check again</a>
</p>

<script type="text/javascript">
    /*<![CDATA[*/
    if(checkAgain<?php echo $test;?> == 'undefined') {
        jQuery('#<?php echo $test;?>').html('');
    }
    jQuery('#<?php echo $test.'-check-again';?>').on('click',function(){
        jQuery.ajax({
            'type':'post',
            'dataType':'json',
            'url':'<?php echo $this->createUrl('checkAgain');?>',
            'cache':false,
            'data':null,
            success: function(response) {
                if(response!=''){
                    if(checkAgain<?php echo $test;?> == 'BaseConfig') {
                        jQuery('li#<?php echo $test.'-content';?>').html(response['msg']);
                        /*
                        if(response['config']) {
                            //jQuery('#baseConfigContent').html(response['config']);
                            jQuery('#baseConfigLink').removeClass('hide');
                            jQuery('#baseConfigLink a').addClass('reloadBaseConfig');
                            jQuery('#baseConfigLink a').attr('href','#');
                        }  */
                        
                    } else {
                        jQuery('#<?php echo $test.'-content';?>').html(response['msg']);
                    }
                    
                }
            }
        });
        return false;
    });
    
    /*]]>*/
</script>
