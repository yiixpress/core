<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
echo '$this->pageTitle = ($model->isNewRecord ? \'Create \' : \'Edit \') . \'' . $this->pluralize($this->class2name($this->modelClass)) . '\';';
echo "\n";

$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
$model = new $this->modelClass;

echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
);
if (\$model->".($model->hasAttribute('name') ? 'name' : ($model->hasAttribute('title') ? 'title' : 'id')).")
    \$this->breadcrumbs = CMap::mergeArray(\$this->breadcrumbs, array(\$model->{$nameColumn}=>array('update','id'=>\$model->{$this->tableSchema->primaryKey}),'Update'));
else
    \$this->breadcrumbs[] = 'Create';
";
?>

$this->menu=array(
);
?>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>