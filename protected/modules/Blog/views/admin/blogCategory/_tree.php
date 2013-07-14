<style type="text/css">
	.grid-view ul {
		padding: 0;
		display: block;
		margin: 0;
	}

	.grid-view ul li {
		list-style: none;
	}

	.grid-view .sort-handle {
		cursor: move;
	}

	.grid-view table.items {
		border-bottom: 1px solid #FFFFFF;
	}

	#list-container ul {
		padding-left: 20px;
	}

	.ordering-updated {
		background: url(<?php echo Yii::app()->theme->baseUrl;?>/images/notifications.gif) 10px center #EEEEEE no-repeat;
		padding: 5px 0 5px 30px;
		margin: 10px 0;
	}

	.placeholder {
		background: #DBDBDB;
		border-bottom: 1px solid #FFFFFF;
	}

	.filters span {
		padding: 5px 10px;
		background: url(<?php echo Yii::app()->theme->baseUrl;?>/images/clear-filter.png) no-repeat left center;
		cursor: pointer;
	}

	.grid-view table.items {
		width: 100%;
	}

		/*.grid-view table.items th, .grid-view table.items td { border: 1px solid white;}*/
	.grid-view table.items th {
		border-bottom: 1px solid #DDDDDD;
	}

	.view-mode select {
		display: none;
	}
</style>

<?php
$cs = Yii::app()->clientScript;

$cs->registerCoreScript('jquery.ui');
$cs->registerCoreScript('bbq');
$cs->registerCoreScript('cookie');
$cs->registerScriptFile(themeUrl() . '/scripts/jquery.ui.nestedSortable.js');

$this->breadcrumbs = array(
	'Product Categories' => array('admin'),
	'Manage',
);

$script = "
jQuery.initNestedSortable = function()
{
    var timer = null;
    jQuery('#list-container').nestedSortable({
        disableNesting: 'no-nest',
        forcePlaceholderSize: true,
        handle: '.sort-handle',
        items: '.sortable',
        opacity: .6,
        placeholder: 'placeholder',
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> table',
        'listType' : 'ul',
        update: function(event, ui){
            jQuery('#list-container').nestedSortable('disable');
            var serialized = jQuery('#list-container').nestedSortable('serialize');
            jQuery('.ordering-updated').remove();
            if (timer) {
                clearTimeout(timer);
                timer = null;
            }
            jQuery.get('" . $this->createUrl('/Xpress/service/ajax', array('SID' => 'Blog.BlogCategory.reorder')) . "', serialized, function(res){
                jQuery('#list-container').nestedSortable('enable');
                var message = jQuery('<div></div');
                message.addClass('ordering-updated')
                    .text('The display order of your categories has been updated successfully.');
                jQuery('.grid-view').before(message);
                timer = setTimeout(function(){jQuery('.ordering-updated').remove()}, 5000);
            });
        }
    });
};
";
$cs->registerScript(__CLASS__ . '#InitNestedSortable', $script);
$cs->registerScript(__CLASS__ . '#RunNestedSortable', "jQuery.initNestedSortable();");
$script = "
jQuery('.status-column a').click(function(){
    var _this = jQuery(this);
    var href = jQuery(this).attr('href');
    var value = _this.find('i').hasClass('icon-ok') ? 0 : 1;
    href = $.param.querystring(href,{value:value});
    jQuery.get(href, function(){
        if (_this.find('i').hasClass('icon-ok'))
            _this.find('i').attr('class','icon-ban-circle');
        else
            _this.find('i').attr('class','icon-ok');
    });
    return false;
});

jQuery('.crud-menu .change-status').click(function() {
    var data = [];
    jQuery.each(jQuery('input.select-on-check'), function(){
        if (jQuery(this).attr('checked'))
            data.push(jQuery(this).val());
    });
    if (data.length <= 0)
    {
        alert('No item selected.');
        return false;
    }

    var href = jQuery(this).attr('href');
    jQuery.get(href, {'ids' : data}, function(res){
        res = eval(res);
        if (res.errors != undefined && res.errors.length <= 0) {
            jQuery('.grid-view').load('" . $this->createUrl('Blog/admin/blogCategory/admin') . " .grid-view > *', function(){
                jQuery.initNestedSortable();
            });
        } else {
            var message = 'Error';
            if (jQuery.isArray(res.errors.ErrorCode))
                message = res.errors.ErrorCode.join('. ');
            alert(message);
        }
    });
    return false;
});

function get_link_delete(url, data)
{
    var valid = true;
    if (!confirm('Are you sure to delete the category?'))
        valid = false;

    return {
        url : jQuery.param.querystring(url, data),
        valid : valid
    };
}

jQuery('.actions-column a.delete').click(function(){
    var _this = jQuery(this);

    var url = jQuery(this).attr('href');
    var data = get_link_delete(url, {});
    if (data.valid == false)
        return false;

    jQuery.post(data.url, function(res){
        jQuery('.grid-view').load('" . $this->createUrl('Blog/admin/blogCategory/admin') . " .grid-view > *', function(){
            jQuery.initNestedSortable();
        });
    });
    return false;
});
jQuery('.check-all').click(function(){
    jQuery('.checkbox-column input').attr('checked', jQuery(this).attr('checked'));
});
jQuery('.checkbox-column input').click(function(){
    if (!jQuery(this).attr('checked'))
        jQuery('.check-all').attr('checked', false);
});
jQuery('.delete-multi').click(function(){
    var data = [];
    jQuery.each(jQuery('.checkbox-column input:checked'), function(){
        data.push(jQuery(this).val());
    });
    if (data.length <= 0) {
        alert('Please select at least one category');
        return false;
    }

    var url = '" . $this->createUrl('delete') . "';
    data = get_link_delete(url, {'ids' : data});
    if (data.valid == false)
        return false;

    jQuery.post(data.url, function(res){
        jQuery('.grid-view').load('" . $this->createUrl('Blog/admin/blogCategory/admin') . " .grid-view > *', function(){
            jQuery.initNestedSortable();
        });
    });
    return false;
});
";
$cs->registerScript(__CLASS__ . '#Actions', $script);

$script = "
var toggle = jQuery.cookie('toggle') || {};
if (typeof toggle === 'string')
{
    var tmp = {};
    JSON.parse(toggle, function(key,value){
        tmp[key] = value;
        key = key.replace('_','-');
        if (value == 0)
        {
            $('#'+key+' > .items .collapse i').removeClass('icon-minus');
            $('#'+key+' > ul').hide();
        }
    });
    toggle = tmp;
}
jQuery('.sort-handle .collapse').click(function(){
    var id = jQuery(this).closest('.sortable').attr('id').replace('-','_');
    if (jQuery('i',this).hasClass('icon-minus'))
    {
        jQuery('i',this).removeClass('icon-minus')
            .closest('.sortable').find('> ul').hide();
        toggle[id] = 0;
    }
    else
    {
        jQuery('i',this).addClass('icon-minus')
            .closest('.sortable').find('> ul').show();
        toggle[id] = 1;
    }
    jQuery.cookie('toggle', JSON.stringify(toggle));
    return false;
});
";
$cs->registerScript(__CLASS__ . '#Toggle', $script);

$js = <<<EOP
$('body').delegate('.expand-all', 'click', function(){
    $('.collapse > .icon-plus').addClass('icon-minus')
            .closest('.sortable').find('> ul').show();
    jQuery.cookie('toggle', null);
});
$('body').delegate('.collapse-all', 'click', function(){
    var toggle = {},
    items = $('.collapse > .icon-minus');

    $.each(items,function(k,v){
        var id = $(this).closest('.sortable').attr('id').replace('-','_');
        toggle[id] = 0;
        console.log('id',id);
    });

    items.removeClass('icon-minus')
        .closest('.sortable').find('> ul').hide();

    jQuery.cookie('toggle', JSON.stringify(toggle));
});
EOP;

cs()->registerScript(__CLASS__ . '#ExpandCollapseAll', $js);
?>

<div class="grid-view table">
	<table class="items">
		<thead>
		<tr>
			<th id="checkbox-header" class="checkbox-header minimal-hide" align="center"
			    width="20"><?php echo CHtml::checkBox('check_all', false, array('class' => 'check-all')); ?></th>
			<th id="title-header">Title</th>
			<th id="status-header" width="40">Status</th>
			<th id="actions-header" width="80">&nbsp;</th>
		</tr>
		</thead>
	</table>
	<?php if (count($models)): ?>
		<ul id="list-container">
			<?php $this->renderNested(__DIR__ . '/_item.php', $models); ?>
		</ul>
	<?php else: ?>
		<table class="items">
			<tr class="odd">
				<td colspan="4"><?php echo Yii::t('zii', 'No results found.'); ?></td>
			</tr>
		</table>
	<?php endif; ?>
</div>

<?php
$script = "
	// fix columns width
	function fixTreeColumnWidth(){
		$('table.items').attr('width','100%');
		$('table.items th').each(function(){
			var className = this.id.replace('header','column');
			$('td.'+className).attr('width', $(this).attr('width'));
		});
	}
	fixTreeColumnWidth();
	";
cs()->registerScript('fix-column-width', $script, CClientScript::POS_END);
?>