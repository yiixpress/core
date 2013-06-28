<?php
/**
* Service definition for ModuleApi
*/

return array(
    'ModuleApi' => array(
        'class' => 'Admin.services.ModuleApi',
        'methods'=> array(
            'get'=>array(
                'title'=>'Get Module',
                'output'=>'Xpress.models.Module',
            ),
            'save'=>array(
                'title'=>'Save Module',
                'input'=>'Xpress.models.Module',
                'output'=>'Xpress.models.Module'
            ),
            'delete'=>array(
                'title'=>'Delete one or multiple Module',
            ),
        )
    ),
);
?>
