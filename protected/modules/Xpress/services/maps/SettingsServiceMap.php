<?php
return array(
'SettingsApi'=>array(
            'class'=>'Xpress.services.SettingsApi',
            'methods'=>array(
                'rebuildCache'=>array(
                    'title'=>'Rebuild settings cache for all modules',
                ),
                'db2php'=>array(
                    'title'=>'Rebuild settings cache for special module',
                ),
                'reorder'=>array(
                    'title'=>'reorder position of setting',
                ),
                'delete'=>array(
                    'title'=>'Delete setting',
                ),
                'create'=>array(
                    'title'=>'create setting',
                    'input'=>'Xpress.models.Setting',
                    'output'=>'Xpress.models.Setting',
                ),
                'update'=>array(
                    'title'=>'update setting',
                    'input'=>'Xpress.models.Setting',
                ),
                'generateModuleConfig'=>array(
                    'title'=>'Generate the config file for list of modules used in applicatio config'
                ),
                'detectModules'=>array(
                    'title'=>'Detect base and custom modules',
                ),
            ),
        ),
);
?>
