<?php
$test = str_replace('Controller','',get_class($this));
  $this->successful = true;
?>
<style type="text/css">
.testSendEmail {
    margin-top: -10px;
}
</style>
<div class="alert alert-warning">
    Please note that the diagnostic is done on base.php configuration file. Your application (i.e. main-frontend) might override it.
</div>
<div class="alert hide" id="testResult"></div>
<div class="row" style="margin-left: 0%;">
    <form id="test-send-mail-form">
        <?php echo 'Enter your email : '; ?>
        <input type="text" name="email" value="" placeholder="Your email"/>
        <button class="btn btn-primary testSendEmail" type="button">Send Email Test</button>
    </form>
</div>

<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery('.testSendEmail').on('click',function(){
        jQuery.ajax({
            'type':'post',
            'dataType':'json',
            'url':'<?php echo $this->createUrl('testEmail');?>',
            'cache':false,
            'data':jQuery("#test-send-mail-form").serialize(),
            success: function(response) {
                if(response!=''){
                    if(response['status'] != '') {
                        jQuery('#testResult').removeClass('alert-info');
                        jQuery('#testResult').addClass('alert-error');
                    } else {
                        jQuery('#testResult').removeClass('alert-error');
                        jQuery('#testResult').addClass('alert-info');
                    }
                    jQuery('#testResult').fadeIn(1000);
                    
                    jQuery('#testResult').html(response['msg']);
                }
            }
        });
        return false;
    });
    
    /*]]>*/
    </script>