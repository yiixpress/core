<?php
if(isset($controllers)) {
    foreach($controllers as $controller)
    {
        try
        {
            list($c,$a) = Yii::app()->createController("Diagnostic/{$controller}/index");
        }
        catch(CException $ex)
        {     
            echo $ex->getMessage();
        }
        
        if (!$c)
            throw new CException('Test controller '.$controller.'Controller.php is not found.');
        $c->run($a);
        
        if ($c->successful !== true)
        {
            // Stop running the next diagnostic
            echo '
            <div class="alert alert-error">
                Please fix the detected error and run diagnostic again.
            </div>';
            break;
        }
    }
}  
?>
