<?php

class BlogModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'Blog',
            'description'   => '',
            'is_system'     => false,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }
}
