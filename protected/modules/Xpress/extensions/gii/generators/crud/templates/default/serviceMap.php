<?php echo "<?php\n"; ?>
/**
* Service definition for <?php echo $this->modelClass; ?>Api
*/

return array(
    '<?php echo $this->modelClass; ?>Api' => array(
        'class' => '<?php echo $this->Module->Id; ?>.services.<?php echo $this->modelClass; ?>Api',
        'methods'=> array(
            'get'=>array(
                'title'=>'Get <?php echo $this->modelClass; ?>',
                'output'=>'<?php echo $this->Module->Id; ?>.models.<?php echo $this->modelClass; ?>',
            ),
            'save'=>array(
                'title'=>'Save <?php echo $this->modelClass; ?>',
                'input'=>'<?php echo $this->Module->Id; ?>.models.<?php echo $this->modelClass; ?>',
                'output'=>'<?php echo $this->Module->Id; ?>.models.<?php echo $this->modelClass; ?>'
            ),
            'delete'=>array(
                'title'=>'Delete one or multiple <?php echo $this->modelClass; ?>',
            ),
        )
    ),
);
?>
