<?php
/**
* @author Hung Nguyen
* @package Xpress
*/


class InfoBox extends CWidget
{
    /**
    * Info type. Set the color of the info box to indication its importance 
    * 
    * @var string Possible value info, error, success, warning
    */
    public $type = 'info';
    
    /**
    * Box's heading. It's auto set depend on info's type.
    * To hide the heading, set to null.
    * 
    * @var string
    */
    public $heading = null;
    
    public $errorOwner;
    
    /**
    * The message can use template {errors},{flash} or both. {flash} get messages in
    * user's flash while {errors} get logged messages from errorHandler component.
    * 
    * The default error owner object is current controller. You can set to null
    * in order to dump all logged errors.
    * 
    * You can include {heading} if you set the information heading via 'heading'
    * property. Content of {errors} or {flash} is wrapped by <ul>, each message 
    * is wrapped by <li>.
    * 
    * @var mixed
    */
    public $message = '{heading}{errors}{flash}';
    
    public function init()
    {
        parent::init();
        $this->errorOwner = Yii::app()->getController();
    }
    
    public function run() {
        $noFlash = true;
        $noError = true;
        
        // flash
        $flahses = user()->getFlashes();
        if (strpos($this->message,'{flash}') !== false && count($flahses))
        {
            $noFlash = false;
            $flashMsg = '<ul class="unstyled"><li>'.implode('</li><li>', $flahses).'</li></ul>';
            $this->message = str_replace('{flash}',$flashMsg,$this->message);
        }
        else
            $this->message = str_replace('{flash}','',$this->message);
            
        // errors
        if (strpos($this->message,'{errors}') !== false && errorHandler()->hasErrors())
        {
            $noError = false;
            $this->type = 'error';
            $errMsg = '';
            $errors = errorHandler()->getErrors();
            foreach($errors as $err)
            {
                if (!$err instanceof XManagedError) continue;
                $errMsg .= '<li>'.$err->getMessage().'</li>';
                
                errorHandler()->discardErrorByHash($err->getHash());
            }
            
            $errMsg = "<ul class=\"unstyled\">{$errMsg}</ul>";
            $this->message = str_replace('{errors}',$errMsg,$this->message);
        }
        else
            $this->message = str_replace('{errors}','',$this->message);
            
            
        if ($noError && $noFlash)
            return;

        if ($this->heading === null)
            switch($this->type)
            {
                case 'info':
                    $this->heading = 'Please Note :';    
                    break;
                case 'waring':
                    $this->heading = 'Warning :';    
                    break;
                case 'error':
                    $this->heading = 'Error :';    
                    break;
                case 'success':
                    $this->heading = 'Success :';    
                    break;
            }
            
        // heading
        if (strpos($this->message,'{heading}') !== false)
        {
            $this->message = str_replace('{heading}',$this->heading,$this->message);
            $this->heading = null;
        }
            

            
        $this->render('InfoBox');
    }
}
?>