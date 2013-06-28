<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>

<?php echo "<?php"; ?> $config = array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'default/_view',//current controller ImageListController -> controller viewPath = views/widgets/imageList
);

//apply config
$pageStyle = (int) $this->PagerStyle;
$showResultCoutner = (boolean) $this->ShowResultCoutner;
if (!$showResultCoutner) $config['template'] = '{items} {pager}';
if ($pageStyle == 0) $config['pager'] = array('class'=>'CLinkPager','maxButtonCount'=>0);

$this->widget('zii.widgets.CListView', $config); 
?>
<?php echo "<?php echo YII_DEBUG ? '<!-- '.__FILE__.' -->' : '';?>";?>