<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

/**
* The XClientScript extends CClientScript to allow page work in standard requestas well as AJAX request
* which is the main request type of Xpress Admin Control Panel.
* 
* @package Xpress
*/
class XClientScript extends CClientScript
{
    const POS_LAST=5;
    protected $orderScripts=array();
    protected $orderScriptFiles=array();
    protected $orderCss=array();
    protected $orderCssFiles=array();
    
    
    /**
    * This function is for compatible with old code
    * 
    * @param mixed $id
    * @param mixed $script
    * @param mixed $position
    * @return CClientScript
    */
    public function registerLiveScript($id,$script,$position = null)
    {
        return parent::registerScript($id,$script,$position);
    }

    /**
    * This function is for compatible with old code
    * 
    * @param mixed $url
    * @param mixed $media
    * @return CClientScript
    */
    public function registerLiveCssFile($url, $media = '')
    {
        return parent::registerCssFile($url, $media);
    }

    protected function sortScriptFiles($position)
    {
        if(isset($this->scriptFiles[$position], $this->orderScriptFiles[$position]))
        {
            $scriptFiles = $this->scriptFiles[$position];
            $orderScriptFiles = $this->orderScriptFiles[$position];
            if (count($scriptFiles) && count($orderScriptFiles))
            {
                $scriptFiles = array_diff_key($scriptFiles, $orderScriptFiles);
                asort($orderScriptFiles);
                foreach ($orderScriptFiles as $url => $order)
                {
                    $scriptFiles[$url] = $url;
                }
                $this->scriptFiles[$position] = $scriptFiles;
            }
        }
    }

    protected function sortScripts($position)
    {
        if(isset($this->scripts[$position], $this->orderScripts[$position]))
        {
            $scripts = $this->scripts[$position];
            $orderScripts = $this->orderScripts[$position];
            if (count($scripts) && count($orderScripts))
            {
                $tmp = $scripts;
                $scripts = array_diff_key($scripts, $orderScripts);
                asort($orderScripts);
                foreach ($orderScripts as $id => $order)
                {
                    $scripts[$id] = $tmp[$id];
                }
                $this->scripts[$position] = $scripts;
            }
        }
    }

    protected function sortCssFiles()
    {
        $cssFiles = $this->cssFiles;
        $orderCssFiles = $this->orderCssFiles;
        if (count($cssFiles) && count($orderCssFiles))
        {
            $tmp = $cssFiles;
            $cssFiles = array_diff_key($cssFiles, $orderCssFiles);
            asort($orderCssFiles);
            foreach ($orderCssFiles as $url => $order)
            {
                $cssFiles[$url] = $tmp[$url];
            }
            $this->cssFiles = $cssFiles;
        }
    }

    protected function sortCss()
    {
        $css = $this->css;
        $orderCss = $this->orderCss;
        if (count($css) && count($orderCss))
        {
            $tmp = $css;
            $css = array_diff_key($css, $orderCss);
            asort($orderCss);
            foreach ($orderCss as $id => $order)
            {
                $css[$id] = $tmp[$id];
            }
            $this->css = $css;
        }
    }

    /**
     * Inserts the scripts in the head section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderHead(&$output)
    {
        //do sort by order
        $this->sortCssFiles();
        $this->sortCss();
        $this->sortScriptFiles(self::POS_HEAD);
        $this->sortScripts(self::POS_HEAD);
        parent::renderHead($output);
    }

    /**
     * Inserts the scripts at the beginning of the body section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderBodyBegin(&$output)
    {
        //do sort by order
        $this->sortScriptFiles(self::POS_BEGIN);
        $this->sortScripts(self::POS_BEGIN);
        parent::renderBodyBegin($output);
    }

    /**
     * Inserts the scripts at the end of the body section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderBodyEnd(&$output)
    {
        //do sort by order
        $this->sortScriptFiles(self::POS_END);
        $this->sortScriptFiles(self::POS_READY);
        $this->sortScriptFiles(self::POS_LOAD);
        $this->sortScriptFiles(self::POS_LAST);
        $this->sortScripts(self::POS_END);
        $this->sortScripts(self::POS_READY);
        $this->sortScripts(self::POS_LOAD);
        $this->sortScripts(self::POS_LAST);
        parent::renderBodyEnd($output);
        //render file at ready and load
        $fullPage=0;
        $output=preg_replace('/(<\\/body\s*>)/is','<###end###>$1',$output,1,$fullPage);
        $html='';
        if(isset($this->scriptFiles[self::POS_READY]))
        {
            foreach($this->scriptFiles[self::POS_READY] as $scriptFile)
                $html.=CHtml::scriptFile($scriptFile)."\n";
        }
        if(isset($this->scriptFiles[self::POS_LOAD]))
        {
            foreach($this->scriptFiles[self::POS_LOAD] as $scriptFile)
                $html.=CHtml::scriptFile($scriptFile)."\n";
        }
        $scripts=isset($this->scripts[self::POS_LAST]) ? $this->scripts[self::POS_LAST] : array();
        if(!empty($scripts))
        {
//            $html.=CHtml::script(implode("\n",$scripts))."\n";
            foreach($scripts as $script)
                if (strpos($script,'<noscript>') === 0)
                    $html .= $script."\n";
                else
                    $html.="<script type=\"text/javascript\">".$script."</script>\n";
        }
        if(isset($this->scriptFiles[self::POS_LAST]))
        {
            foreach($this->scriptFiles[self::POS_LAST] as $scriptFile)
                $html.=CHtml::scriptFile($scriptFile)."\n";
        }
        if($fullPage)
            $output=str_replace('<###end###>',$html,$output);
        else
            $output=$output.$html;
    }

    public function registerScript($id,$script,$position=null, $order=0)
    {
        $order = CPropertyValue::ensureInteger($order);
        if ($order)
            $this->orderScripts[$position][$id]=$order;
        return parent::registerScript($id,$script,$position);
    }

    public function registerScriptFile($url,$position=null, $order=0)
    {
        $order = CPropertyValue::ensureInteger($order);
        if ($order)
            $this->orderScriptFiles[$position][$url]=$order;
        return parent::registerScriptFile($url,$position);
    }

    public function registerCss($id,$css,$media='', $order=0)
    {
        $order = CPropertyValue::ensureInteger($order);
        if ($order)
            $this->orderCss[$id]=$order;
        return parent::registerCss($id,$css,$media);
    }

    public function registerCssFile($url,$media='', $order=0)
    {
        $order = CPropertyValue::ensureInteger($order);
        if ($order)
            $this->orderCssFiles[$url]=$order;
        return parent::registerCssFile($url,$media);
    }
}