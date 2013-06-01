<?php echo $message;?>

<?php if ($this->successful === 0) :?>
    <p>The following configuration should be added to base.php file:</p>
<?php else: ?>
    <?php $this->successful = false; ?>
<?php endif; ?>

    <pre>
        'mail' => array(
            'class' => 'Xpress.extensions.vendors.mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => true
        ),
    </pre>
<?php
    $test = str_replace('Controller','',get_class($this));
?>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>