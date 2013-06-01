<?php if(isset($msg)) {?><div class="alert alert-info"><?php echo $msg;?></div><?php }?>
<div class="alert alert-success message-connection" style="display: none;"></div>
<div class="form">
    <style type="text/css">
    #config-env-form .row label{
        float: left;
        width: 25%;
    }
    #config-env-form .row{
        margin-left: .3%;
    }
    #config-env-form .buttons{
        padding-left: 25%;
    }
    </style>
    <div class="alert alert-error alert-validation hide"></div>
    <?php $form=$this->beginWidget('CActiveForm',array('id'=>'config-env-form','enableAjaxValidation'=>true)); ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    
    <?php echo CHtml::errorSummary($model); ?>
    
    <div class="row">
        <?php echo $form->labelEx($model,'site_owner'); ?>
        <?php echo $form->textField($model,'site_owner',array('size'=>80,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'site_owner',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'site_id'); ?>
        <?php echo $form->textField($model,'site_id',array('size'=>80,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'site_id',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'server_host'); ?>
        <?php echo $form->textField($model,'server_host',array('size'=>80,'maxlength'=>128,)); ?>
        <?php echo $form->error($model,'server_host',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'dbport'); ?>
        <?php echo $form->textField($model,'dbport',array('size'=>80,'maxlength'=>128,)); ?>
        <?php echo $form->error($model,'dbport',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'dbname'); ?>
        <?php echo $form->textField($model,'dbname',array('size'=>80,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'dbname',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'dbusername'); ?>
        <?php echo $form->textField($model,'dbusername',array('size'=>80,'maxlength'=>128,)); ?>
        <?php echo $form->error($model,'dbusername',array('class'=>'alert alert-error')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'dbpassword'); ?>
        <?php echo $form->textField($model,'dbpassword',array('size'=>80,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'dbpassword',array('class'=>'alert alert-error')); ?>
    </div>
        
    <div class="row buttons">
        <?php echo CHtml::button('Save',array('class'=>'btn btn-primary','id'=>'save-config-env-btn')); ?>
        <?php //echo CHtml::button('Test Connection',array('class'=>'btn btn-primary','id'=>'test-connection-btn')); ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php Yii::app()->clientScript->registerCoreScript("jquery")?>
    <script type="text/javascript">
    /*<![CDATA[*/
    jQuery('#save-config-env-btn').on('click',function(){
        jQuery.ajax({
            'type':'post',
            'dataType':'json',
            'url':'/diagnostics.php?r=Diagnostic/environment/index',
            'cache':false,
            'data':jQuery("#config-env-form").serialize(),
            success: function(response) {
                if(response!=''){
                    if(response['msg']!=undefined && response['msg']!=''){
                        jQuery('.message').html(response['msg']);
                        jQuery('.message').fadeIn(1000).fadeOut(6000);
                        jQuery('#env-block-form').html('');
                        window.location = response['url'];
                    } else {
                        jQuery('.alert-validation').fadeIn(1000);
                        html = '<ul>';
                        for(res in response) {
                            html += '<li>'+response[res]+'</li>';
                        }
                        html += '</ul>';
                        jQuery('.alert-validation').html(html);
                        jQuery('.message').fadeOut(20000);
                    }
                }
            }
        });
        return false;
    });
    jQuery('#test-connection-btn').on('click',function(){
        jQuery.ajax({
            'type':'post',
            'dataType':'json',
            'url':'/diagnostics.php?r=Diagnostic/environment/testConnection',
            'cache':false,
            'data':jQuery("#config-env-form").serialize(),
            success: function(response) {
                if(response!=''){
                    if(response['status']!=''){
                        jQuery('.message-connection').removeClass('alert-success');
                        jQuery('.message-connection').addClass('alert-error');
                        jQuery('.message-connection').html(response['msg']);
                        jQuery('.message-connection').fadeIn(1000);
                    } else {
                        jQuery('.message-connection').removeClass('alert-error');
                        jQuery('.message-connection').addClass('alert-success');
                        jQuery('.message-connection').html(response['msg']);
                        jQuery('.message-connection').fadeIn(1000);
                    }
                }
            }
        });
        return false;
    });
    /*]]>*/
    </script>
</div>