<?php
/**
 * @author
 * @package Xpress
 * @subpackage XUser
 */


class SettingsApi extends ApiController
{
    /**
     * Rebuild all module cache files and page and categogy caches
     */
    public function actionRebuildCache()
    {
        foreach (Yii::app()->Modules as $id => $params)
            $this->actionDb2php(array('module' => $id));

    }

    /**
     * Rebuild system cache or cache of a module
     *
     * @param array $params
     *   - Module string Module name or system (Cms) cache if empty
     * @return ServiceResult
     */
    public function actionDb2php($module = '')
    {
        //Get module's params in DB
        Yii::import('Xpress.models.Setting', true);
        $criteria = new CDbCriteria();

        //For Cms module, save settings as system's settings
        if (!in_array($module, array('Xpress', 'Admin')))
            $criteria->addCondition("module = '{$module}'");
        else
            $criteria->addCondition("module = ''");

        $criteria->order = 'name';
        $params = Setting::model()->findAll($criteria);

//        if (count($params) < 1) return;

        //For Cms module, save settings as system's settings
        if (in_array($module, array('Xpress', 'Admin'))) $module = '';

        //load user custom setting
//        UserSetting::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));

        if (count($params) <= 0) return;

        $consts = array();
        foreach ($params as $param) {
            if (!is_numeric($param->value)) $param->value = "'" . addslashes($param->value) . "'";
            {
                $consts[] = (empty($param->description) ? "" : "//{$param->description}\n")
                    . "const " . (empty($module) ? 'SETTINGS_' : strtoupper($module) . '_')
                    . "{$param->name} = {$param->value};";
            }
        }

        $consts = implode("\n\n", $consts);

        $php =
            "<?php
/**
* DONOT modify this file as it's automatically generated based on setting parameters.
**/

{$consts}";
        $filename = $module . 'Settings.php';
        $path = Yii::getPathOfAlias('runtime') . "/cache/";

        if (!is_dir($path) || !is_writeable($path)) {
            errorHandler()->log(Yii::t('Settings.Api', '{path} does not exist or is not writable.', array('{path}' => $path)));
            $this->result = $path;
            return;
        }

        try {
            @file_put_contents($path . $filename, $php);
        } catch (Exception $ex) {
            errorHandler()->log(Yii::t('Settings.Api', 'Cannot create file {filename} under {path}. ', array('{path}' => $path, '{filename}' => $filename)));
        }

        $this->result = $filename;
    }

    public function actionReorder($curId, $prevId = null, $nextId = null)
    {
        $tmp = $curId;

        $tmp = json_decode($tmp);

        if (!empty($tmp)) {
            $module = $tmp->Module; // We got module name here
            $groupName = $tmp->GroupName; // We got group name here
            $curName = $tmp->Name;
        } else {
            errorHandler()->log(Yii::t('Settings.Api', 'Invalid request'));
            return;
        }

        $tmp = $prevId;
        $tmp = json_decode($tmp);
        if (!empty($tmp)) {
            $prevName = $tmp->Name;
        } else {
            $prevName = '';
        }

        $tmp = $nextId;
        $tmp = json_decode($tmp);
        if (!empty($tmp)) {
            $nextName = $tmp->name;
        } else {
            $nextName = '';
        }


        $prevParam = Setting::model()->findByPk(array('name' => $prevName, 'module' => $module));
        $curParam = Setting::model()->findByPk(array('name' => $curName, 'module' => $module));
        $nextParam = Setting::model()->findByPk(array('name' => $nextName, 'module' => $module));

        // Move an item to top
        if ($prevName == '') {
            // All items < current item move up
            Setting::model()->updateCounters(array('ordering' => 1), "module=:Module AND setting_group=:GroupName AND ordering<:CurOrdering", array(":Module" => $module, ':GroupName' => $groupName, ':CurOrdering' => $curParam->Ordering));

            // Current page's ordering = 0
            $curParam->ordering = 0;
            $curParam->save();
        } // Move an item to bottom
        elseif ($nextName == '') {
            // All item > current item move down
            Setting::model()->updateCounters(array('ordering' => -1), "module=:Module AND setting_group=:GroupName AND ordering>:CurOrdering", array(":Module" => $module, ':GroupName' => $groupName, ':CurOrdering' => $curParam->Ordering));

            // Current item ordering = prev ordering
            $curParam->ordering = $prevParam->ordering;
            $curParam->save();
        } else {
            // Move up
            if ($curParam->Ordering > $nextParam->Ordering) {
                Setting::model()->updateCounters(array('ordering' => 1), "module=:Module AND setting_group=:GroupName AND ordering>:Begin AND ordering<:End", array(":Module" => $module, ':GroupName' => $groupName, ':Begin' => $prevParam->ordering, ':End' => $curParam->ordering));
                $curParam->ordering = $nextParam->ordering;
                $curParam->save();
            } else {
                // Move down
                Setting::model()->updateCounters(array('ordering' => -1), "module=:Module AND setting_group=:GroupName AND ordering>:Begin AND ordering<:End", array(":Module" => $module, ':GroupName' => $groupName, ':Begin' => $curParam->ordering, ':End' => $nextParam->ordering));
                $curParam->ordering = $prevParam->ordering;
                $curParam->save();
            }

        }
    }

    /**
     * Delete a parameter by id
     *
     * @param int $paramId: Parameter id
     */
    public function actionDelete($paramId)
    {
        $tmp = $paramId;
        $tmp = json_decode($tmp);

        if (!empty($tmp)) {
            $module = $tmp->module; // We got module name here
            $groupName = $tmp->setting_group; // We got group name here
            $name = $tmp->name;
        } else {
            errorHandler()->log(Yii::t('Settings.Api', 'PARAMETER_INVALID_INPUT'));
            return;
        }

        $param = Setting::model()->findByPk(array('name' => $name, 'module' => $module));
        if (is_null($param)) {
            errorHandler()->log(Yii::t('Settings.Api', 'PARAMETER_INVALID_INPUT'));
            return;
        }

        if (!$param->delete()) {
            errorHandler()->log($this->normalizeModelErrors($param->Errors));
        } else {
            // Move up params
            Setting::model()->updateCounters(array('ordering' => -1), "module=:Module AND setting_group=:GroupName AND ordering>:Begin", array(":Module" => $module, ':GroupName' => $groupName, ':Begin' => $param->ordering));
            $this->actionDb2php();
        }
    }

    /**
     * create a new page
     *
     * @param mixed $params
     */
    public function actionCreate(array $attributes)
    {
        $input = new XInputFilter($attributes);
        $param = $input->getModel('Setting');

        if ($param->module == 'System') {
            $param->module = '';
        }

        // Get max ordering in a group
        $sql = "
                SELECT MAX(ordering)
                FROM " . SITE_ID . "_setting
                WHERE module=:Module AND setting_group=:GroupName
                ";
        $con = Yii::app()->db;
        $command = $con->createCommand($sql);
        $maxOrdering = $command->queryScalar(array(':Module' => $param->module, ':GroupName' => $param->setting_group));
        $param->ordering = $maxOrdering + 1;

        $temp = Setting::model()->findByPk(array('name' => $param->Name, 'module' => $param->module));
        if (!is_null($temp)) {
            errorHandler()->log(Yii::t('Settings.Api', 'PARAMETER_EXISTS'));
        } else {
            if (!$param->save()) {
                errorHandler()->log($this->normalizeModelErrors($param->Errors));
            } else {
                $this->actionDb2php();
            }
        }
        $this->result = $param;
    }

    public function actionUpdate(array $attributes, $oldName = null, $oldModule = null, $oldOrdering = null)
    {
        $input = new XInputFilter($attributes);
        $param = $input->getModel('Setting');

        if ($param->module == 'System') {
            $param->module = '';
        }

        // Get old Name & Module
        if ($oldModule == 'System') $oldModule = '';

        if ($oldName == $param->name && $oldModule == $param->module) {
            // User didn't change primary key -> update as usual
            foreach ($param->attributes as $key => $attr) {
                if ($key !== 'name' && $key !== 'module') {
                    $updatedFields[$key] = $param->$key;
                }
            }
            Setting::model()->updateByPk(array('name' => $oldName, 'module' => $oldModule), $updatedFields);
            $this->actionDb2php();
        } else // User has changed primary key (Name or Module)
        {
            // Check if this new primary key exists or not
            $temp = Setting::model()->findByPk(array('name' => $param->name, 'module' => $param->module));

            if (!empty($temp)) {
                errorHandler()->log(Yii::t('Settings.Api', 'PARAMETER_EXISTS'));
            } else {
                Setting::model()->deleteByPk(array('name' => $oldName, 'module' => $oldModule));
                $param->ordering = $oldOrdering;
                if (!$param->save()) {
                    errorHandler()->log($this->normalizeModelErrors($param->Errors));
                } else {
                    $this->actionDb2php();
                }
            }
        }
    }

    /**
     * Set application locale. The locale information will be saved into browser's cookies
     *
     * @param array $params
     *   - string locale Yii's locale ID format (langeId_regioanId, i.e. en_us)
     */
    public function actionSetLocale($locale = 'en_us')
    {
        //Set cookies
        $localeCookie = new CHttpCookie('Locale', $locale);
        $localeCookie->expire = time() + 15 * 24 * 3600;
        Yii::app()->request->cookies['Locale'] = $localeCookie;

        //Set application langauge
        Yii::app()->setLanguage($locale);
    }


    /**
     * Generate modules.php file in runtime/cache.
     * The file is used to load modules in application configuration
     */
    public function actionGenerateModuleConfig()
    {
        $detectedModules = $this->actionDetectModules();

        $modules = Module::model()->findAll(new CDbCriteria(array(
            'order' => 'name',
            'condition' => 'enabled = true',
        )));
        $config = array();
        foreach ($modules as $module) {
            if (!isset($detectedModules[$module->name])) continue;
            $config[$module->name] = array(
                'class' => $detectedModules[$module->name]
            );
        }
        $str = "<?php\nreturn " . var_export($config, true) . "\n?>";
        file_put_contents(Yii::getPathOfAlias('runtime.cache') . '/modules.php', $str);
    }

    public function actionDetectModules()
    {
        $baseModules = $this->scanModules(Yii::app()->basePath . '/modules');
        $customModules = $this->scanModules(Yii::getPathOfAlias('site'));
        return $this->result = CMap::mergeArray($baseModules, $customModules);
    }

    private function scanModules($folder)
    {
        static $modules = array();
        $dirs = scandir($folder);
        foreach ($dirs as $name) {
            if ($name == '.' || $name == '..') continue;

            $dir = $folder . '/' . $name;
            if (!is_dir($dir)) continue;
            if (file_exists($dir . "/{$name}Module.php")) {
                // 1. this is a module
                $classPath = $dir . "/{$name}Module";
                $classPath = str_replace(Yii::getPathOfAlias('site'), 'site', $classPath);
                $classPath = str_replace(Yii::getPathOfAlias('application'), 'application', $classPath);
                $modules[$name] = str_replace('/', '.', $classPath);
                // 2. find sub modules\
                if (file_exists($dir . '/modules'))
                    $this->scanModules($dir . '/modules');
            }
        }
        return $modules;
    }
}
?>
