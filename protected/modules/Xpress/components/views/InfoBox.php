<?php
    if ($this->type == 'warning')
        $this->type="block";
?>
<div class="alert alert-<?php echo $this->type ?>">
    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
    
    <?php if ($this->heading): ?>
        <h4><?php echo $this->heading; ?></h4>
    <?php endif; ?>
    
    <?php echo $this->message; ?>
</div>