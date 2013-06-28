<?php
/**
* Service definition for BlogPostApi
*/

return array(
    'BlogPostApi' => array(
        'class' => 'Blog.services.BlogPostApi',
        'methods'=> array(
            'get'=>array(
                'title'=>'Get BlogPost',
                'output'=>'Blog.models.BlogPost',
            ),
            'save'=>array(
                'title'=>'Save BlogPost',
                'input'=>'Blog.models.BlogPost',
                'output'=>'Blog.models.BlogPost'
            ),
            'delete'=>array(
                'title'=>'Delete one or multiple BlogPost',
            ),
        )
    ),
);
?>
