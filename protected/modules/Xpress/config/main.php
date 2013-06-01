<?php
return array(
    'class'=>'Xpress.components.Xpress',
    'include' => array(
        'Xpress.extensions.web.XWebModule',
        'Xpress.controllers.*',
        'Xpress.components.*',
        'Xpress.models.*',
        // helper functions
        'Xpress.extensions.web.helpers.shortcuts',
//        'Xpress.extensions.web.helpers.XInputFilter',
//        'Xpress.extensions.web.helpers.mics',
//        'Xpress.extensions.web.helpers.string',
    ),
);
?>