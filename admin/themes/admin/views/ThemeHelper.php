<?php
/**
 * @package Admin
 */

/**
 * Class ThemeHelper
 *
 * Helper functions to use in layouts and view of this theme.
 * DO NOT use these function in core view files or the application will not work
 * when switch to other theme
 */
class ThemeHelper {
	/**
	 * Create a Bootstrap button
	 *
	 * Button params must be an array with 'label', 'url', and optional 'class' and 'options'
	 * As a bootstrap button, the use 'class' => 'btn-...', i.e. btn-primary to define button color
	 *
	 * @param $link
	 */
	public static function linkButton($link)
	{
		if (!isset($link['options']))
			$link['options'] = array();

		$link['class']            = (isset($link['class']) ? 'btn btn-icon glyphicons ' . $link['class'] : 'btn btn-icon glyphicons');
		$link['options']['class'] = $link['class'];
		if (isset($link['icon']))
		{
			$link['icon'] = self::translateGenericIcon($link['icon']);
			$link['label'] = '<i></i> '.$link['label'];
			$link['options']['class'] .= ' '.$link['icon'];
		}
		echo CHtml::link($link['label'], $link['url'], $link['options']);
	}

	public static function dropDownButtons($buttons)
	{
		if (!isset($buttons['items']))
			return self::linkButton($buttons);

		if (count($buttons['items']) == 1)
			return self::linkButton($buttons['items'][0]);

		app()->controller->widget('bootstrap.widgets.TbButtonGroup', array(
			//'size'    => 'large',
			'type'    => 'danger',
			'buttons' => array($buttons),
		));
	}

	/**
	 * Translate generic icon name into theme specific icon
	 *
	 * Usually a Bootstrap theme will have glyphicons set come with it
	 * but each theme could use a different name for the same meaning of the icon
	 *
	 * @param $generic
	 * @return string
	 */
	protected static function translateGenericIcon($generic)
	{
		switch ($generic)
		{
			case 'new-row':
				return 'circle_plus';
			default:
				return $generic;
		}
	}

}