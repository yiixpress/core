<?php

class BlogModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'Blog & Wiki tool',
            'description'   => '',
            'is_system'     => false,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }

	public function getBackendMenuItems()
	{
		return array(
			array('title' => 'Posts', 'url' => '/Blog/admin/blogPost'),
			array('title' => 'Comments', 'url' => '/Blog/admin/blogComment'),
			array('title' => '---', 'url' => ''),
			array('title' => 'Categories', 'url' => '#'),
		);
	}
}
