<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo "<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>"; ?>

<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php"; ?> $grid = $this->widget('Xpress.extensions.web.widgets.FlexicaGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'selectableRows'=>2,
    'selectionChanged'=>"updateSelectors",
    'menu' => array(
        'items'=>array(
            array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
            array('label' => 'Delete selected items', 'url'=>$this->createUrl('delete'), 'linkOptions' => array('onclick'=>'return multipleDelete("<?php echo $this->class2id($this->modelClass); ?>-grid",this.href)')),
        ),
    ),
	'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'value'=>'$data-><?php echo $this->Model->TableSchema->primaryKey; ?>',
            'htmlOptions'=>array('width'=>'3%'),
        ),
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
    $criteria = new CDbCriteria();
    $criteria->compare('form_id', $this->form->id);
    $criteria->compare('column_name', $column->name);
    $element = FormElement::model()->find($criteria);
    if (is_object($element) && $element->lookup_type === 'lookup')
    {
        if ($element->type === 'checkbox')
        {
            echo "\t\tarray(
        'name'=>'{$column->name}',
        'value'=>'is_array(\$data->{$column->name}) ? implode(\",\", array_map(\"Lookup::item\", array_fill(0, count(\$data->{$column->name}),\"{$element->lookup_value}\"),\$data->{$column->name})) : \$data->{$column->name}',
    ),\n";
        }
        else
        {
            echo "\t\tarray(
        'name'=>'{$column->name}',
        'value'=>'is_array(\$data->{$column->name}) ? Lookup::item(\"{$element->lookup_value}\", \$data->{$column->name}) : \$data->{$column->name}',
    ),\n";
        }
    }
    else
	    echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
		),
	),
));