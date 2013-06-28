<ul>
	<?php
	$count = count($modules);
	$i = 0;
	$rendered = array();
	foreach ($modules as $m) : $i++?>
		<?php
		// don't display menu of Admin as it is supposed to have no menu
		if ($m->name == 'Admin') continue;
		$module = app()->getModule($m->name);
		if (!$module) continue;
		?>

		<?php
		// extended module
		if (strrpos($m->name, 'Ext', -3) !== false && strlen($m->name) - strrpos($m->name, 'Ext') == 3) {
			$coreId = substr($m->name, 0, strlen($m->name) - 3);
			if (array_search($coreId, $rendered) !== false) continue;
			$rendered[] = $coreId;
			$items      = $module->BackendMenuItems;
		} else {
			if (array_search($m->name, $rendered) !== false) continue;
			$rendered[] = $m->name;
			if (($extModule = app()->getModule($m->name . 'Ext')) != NULL)
				$items = $extModule->BackendMenuItems;
			else
				$items = $module->BackendMenuItems;
		}

		// start HTML code for module's menu
		$menuHtml = '';
		try {
			foreach ($items as $item) {
				// item is a group, not menu item
				if (!is_array($item)) {
					// close tags for the previous menu
					if (!empty($menuHtml))
						$menuHtml .= '</ul></li>';
					// start a new menu
					$menuHtml .= '
<li class="hasSubmenu glyphicons '.$m->icon.'">
	<a data-toggle="collapse" href="#'.$item.'"><i></i><span>' . $item . '</span></a>
	<ul class="collapse" id="'.$item.'">
';
				} else
				// item is a menu
					if ($item['url'] != '') {
						if ($item['url'] != '#')
							$url = app()->controller->createUrl($item['url'], isset($item['params']) ? $item['params'] : array());
						else
							$url = '#';
						$menuHtml .= "<li><a href=\"{$url}\"><span>{$item['title']}</span></a></li>";
					} else
						$menuHtml .= '<li class="divider"></li>';
				$menuHtml .= "\n";
			}
		} catch (Exception $ex) {
		}

		if (strpos($menuHtml, '<li class="hasSubmenu') !== 0)
			$menuHtml = '
<li class="hasSubmenu glyphicons '.$m->icon.'">
	<a href="#'.$m->name.'" data-toggle="collapse">
		<i></i><span>' . $m->friendly_name . '</span>
	</a>
	<ul class="collapse" id="'.$m->name.'">
		' . $menuHtml . '
	</ul>
</li>';
		else
			$menuHtml .= '</ul></li>';
		echo $menuHtml;
		?>
	<?php endforeach; ?>

</ul>
