<?php
/**
 * @author Minh Le <vanminh0312@gmail.com>
 * @version $Id$
 * @since 1.0
 */

class NestedCategoryApi extends ApiController
{
	public function actionFindParents($className, $id)
	{
		$parents   = array();
		$cachePath = Yii::getPathOfAlias('runtime') . DIRECTORY_SEPARATOR . 'cache';
		if (file_exists($cachePath . DIRECTORY_SEPARATOR . $className . 'RawCache.php')) {
			$data = file_get_contents($cachePath . DIRECTORY_SEPARATOR . $className . 'RawCache.php');
			$data = unserialize($data);
			if (is_array($data)) {
				$this->findParent($id, $data, $parents);
				$parents = array_reverse($parents);
			}
		}
		$this->result = $parents;

		return $parents;
	}

	protected function findParent($id, $data, &$path)
	{
		foreach ($data as $index => $row) {
			if (isset($row['checked']) && $row['checked']) {
				continue;
			}
			if ($row['id'] == $id) {
				$data[$index]['checked'] = true;
				$path[]                  = $row;
				if ($row['parent_id']) {
					$this->findParent($row['parent_id'], $data, $path);
				}
				break;
			}
		}
	}

	public function actionFindTree($className, $id = 0, $includeRoot = true, array $exclude = array(), $level = -1)
	{
		$data      = array();
		$cachePath = Yii::getPathOfAlias('runtime') . DIRECTORY_SEPARATOR . 'cache';
		if (file_exists($cachePath . DIRECTORY_SEPARATOR . $className . 'Cache.php')) {
			$rows = file_get_contents($cachePath . DIRECTORY_SEPARATOR . $className . 'Cache.php');
			$rows = unserialize($rows);
			if (is_array($rows)) {
				$data = $this->getBranch($rows, $id, $exclude, 0, $level);
				if ($includeRoot == false) {
					if (isset($data['children'])) {
						$data = $data['children'];
					}
				}
			} else {
				$data = array();
			}
		}
		$this->result = $data;

		return $data;
	}

	protected function getBranch($rows, $id = 0, $exclude = array(), $level, $max = -1)
	{
		$data = array();
		foreach ($rows as $row) {
			if ($id) {
				if ($row['id'] == $id) {
					if ($max == -1 || $max > $level) {
						if (isset($row['children']) && is_array($row['children']) && count($row['children'])) {
							$row['children'] = $this->getBranch($row['children'], 0, $exclude, 0, $max);
						}
					} else {
						unset($row['children']);
					}

					return $row;
				} else {
					if (isset($row['children']) && is_array($row['children']) && count($row['children'])) {
						$data = $this->getBranch($row['children'], $id, $exclude, 0, $max);
						if (is_array($data) && isset($data['id'])) {
							return $data;
						}
					}
					continue;
				}
			} else {
				if (is_array($exclude) && count($exclude) && in_array($row['id'], $exclude)) {
					Yii::trace('Exclude ' . $row['id'], 'Xpress.services');
					continue;
				}
				//reset level
				if (is_array($row) && isset($row['level'])) {
					$row['level'] = $level;
				}
				if ($max == -1 || $max > $level) {
					if (isset($row['children']) && is_array($row['children']) && count($row['children'])) {
						$row['children'] = $this->getBranch($row['children'], $id, $exclude, $level + 1, $max);
					}
				} else {
					unset($row['children']);
				}
				$data[] = $row;
			}
		}

		return $data;
	}

	public function actionBuildTree(array $data, array $attributes = array())
	{
		if (count($data)) {
			$rows = array();
			foreach ($data as $model) {
				if (count($attributes)) {
					$attributes = CMap::mergeArray($attributes, array('id', 'parent_id'));
					$rows[]     = array_intersect_key($model->attributes, array_combine($attributes, $attributes));
				} else {
					$rows[] = $model->attributes;
				}
			}

			$raw = serialize($rows);
			//cache raw data
			$filename  = get_class($model);
			$cachePath = Yii::getPathOfAlias('runtime') . DIRECTORY_SEPARATOR . 'cache';
			if (file_exists($cachePath)) {
				file_put_contents($cachePath . DIRECTORY_SEPARATOR . $filename . 'RawCache.php', $raw);
			}
			$parent = 0;
			$data   = $this->result = $this->buildRecursive($parent, 0, $rows);
			$data   = serialize($data);
			//cache tree
			if (file_exists($cachePath)) {
				file_put_contents($cachePath . DIRECTORY_SEPARATOR . $filename . 'Cache.php', $data);
			}
		} else {
			$this->result = array();
		}

		return $this->result;
	}

	protected function buildRecursive(&$parent, $level, &$rows)
	{
		$data    = array();
		$sortKey = array();
		foreach ($rows as $index => $row) {
			if (isset($row['added']) && $row['added']) {
				continue;
			}

			$id = is_array($parent) && isset($parent['id']) ? $parent['id'] : 0;
			if ($row['parent_id'] == $id) {
				$rows[$index]['added'] = true;
				$row['level']          = $level;
				$row['children']       = $this->buildRecursive($row, $level + 1, $rows);
				$data[]                = $row;
				if (isset($row['ordering'])) {
					$sortKey[] = $row['ordering'];
				} elseif (isset($row['name'])) {
					$sortKey[] = $row['name'];
				}
			}
		}
		if (count($sortKey)) {
			array_multisort($sortKey, $data);
		}

		return $data;
	}
}