<?php
/**
 * Service definition for BlogCategoryApi
 */

return array(
	'BlogCategoryApi' => array(
		'class'   => 'Blog.services.BlogCategoryApi',
		'methods' => array(
			'get'          => array(
				'title'  => 'Get BlogCategory',
				'output' => 'Blog.models.BlogCategory',
			),
			'save'         => array(
				'title'  => 'Save BlogCategory',
				'input'  => 'Blog.models.BlogCategory',
				'output' => 'Blog.models.BlogCategory'
			),
			'delete'       => array(
				'title' => 'Delete one or multiple BlogCategory',
			),
			'reorder'      => array(
				'title' => 'Reorder BlogCategories on the grid',
			),
			'changeStatus' => array(
				'title' => 'Change category status',
			),
		)
	),
);
?>
