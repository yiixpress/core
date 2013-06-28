<?php

class AdminModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'Admin module',
            'description'   => 'Provide basic administrative user interface and host the other modules admin pages',
            'is_system'     => true,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }
}
