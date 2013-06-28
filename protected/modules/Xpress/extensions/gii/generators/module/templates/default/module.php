<?php echo "<?php\n"; ?>

class <?php echo $this->moduleClass; ?> extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => '<?php echo str_replace('Module','',$this->moduleClass);?>',
            'description'   => '',
            'is_system'     => false,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }
}
