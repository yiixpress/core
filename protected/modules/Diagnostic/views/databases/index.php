<?php if (!is_array($dbs) || count($dbs) == 0) : ?>
    <div class="alert alert-info">There is no database connection defined in environment file.</div>
<?php else: ?>
    <ul>
    <?php 
        foreach($dbs as $name => $db)
        {
            if ($db['error'] == '')
                echo '<li>Connect to <strong>'.$name.'</strong> database is OK</li>';
            else
                echo '<li>Error when connect to '.$name.': database: '.$db['error'].'.</li>';
        }
    ?>
    </ul>
<?php endif; ?>
<?php
$test = str_replace('Controller','',get_class($this));
?>
<script type="text/javascript">
var checkAgain<?php echo $test?> = '<?php echo isset($checkAgain)?$checkAgain:'undefined'; ?>';
</script>
<script type="text/javascript">
/* <![CDATA[ */
$('a.double-check').live('click',function(){
    var id = $(this).attr('id').replace('check-again','result');
//    $('#'+id).load($(this).attr('href')+' div.content', function(){
//        if ($('#'+id+' div.content').hasClass('success'))
//        {
//           $('#'+id).prev().removeClass('alert-error').addClass('alert-info');
//           $('#'+id).next().removeClass('btn-danger').addClass('btn-primary');            
//        }
//        else
//        {
//           $('#'+id).prev().removeClass('alert-info').addClass('alert-error');
//           $('#'+id).next().removeClass('btn-primary').addClass('btn-danger');            
//        }
//    });
    return false;
});
/* ]]> */
</script>