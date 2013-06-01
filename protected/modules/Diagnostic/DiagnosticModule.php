<?php
class DiagnosticModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'Diagnostic tool',
            'description'   => 'Dianostic tool help to setup the system and detect common issues during deployment.',
            'is_system'     => false,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }
}
