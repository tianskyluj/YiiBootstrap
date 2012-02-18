<?php

Yii::import('zii.widgets.CMenu');

class EBootstrapTabNavigation extends CMenu {
	public $pills = false;
	public $stacked = false;
	
	public $jsFileDropdown = null;
	public $jsFileTab = null;

	public function init() {
		parent::init();
		
		EBootstrap::mergeClass($this->htmlOptions, array('nav'));
		
		if ($this->pills)
			EBootstrap::mergeClass($this->htmlOptions, array('nav-pills'));
		else
			EBootstrap::mergeClass($this->htmlOptions, array('nav-tabs'));
		
		if ($this->stacked)
			EBootstrap::mergeClass($this->htmlOptions, array('nav-stacked'));

		if (is_null($this->jsFileDropdown)) {
			$jsFile = dirname(__FILE__).'/js/bootstrap-dropdown.js';
			$this->jsFileDropdown = Yii::app()->getAssetManager()->publish($jsFile);
			Yii::app()->clientScript->registerScriptFile($this->jsFileDropdown);
		}
		if (is_null($this->jsFileTab)) {
			$jsFile = dirname(__FILE__).'/js/bootstrap-tab.js';
			$this->jsFileTab = Yii::app()->getAssetManager()->publish($jsFile);
			Yii::app()->clientScript->registerScriptFile($this->jsFileTab);
		}
	}
	
	public function run() {
		parent::run();
	}
	
	public function renderMenu($items) {
		if (count($items)) {
			echo EBootstrap::openTag('ul', $this->htmlOptions)."\n";
			$this->renderMenuRecursive($items);
			echo EBootstrap::closeTag('ul')."\n";
		}
	}
	
	protected function renderMenuRecursive($items, $sub = false) {
        $count=0;
        $first = true;
        $n=count($items);
        foreach($items as $item) {
            $count++;
            $options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
            $class=array();
            if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
            if($count===1 && $this->firstItemCssClass!==null)
				$class[]=$this->firstItemCssClass;
            if($count===$n && $this->lastItemCssClass!==null)
				$class[]=$this->lastItemCssClass;
            if($this->itemCssClass!==null)
				$class[]=$this->itemCssClass;
            if($class!==array()) {
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
            }
			
			if(isset($this->itemTemplate) || isset($item['template']))
				$template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
			else
				$template = '';
			
			if(isset($item['items']) && count($item['items'])) {
				EBootstrap::mergeClass($options, array('dropdown'));
				$item['linkOptions'] = (isset($item['linkOptions'])) ? $item['linkOptions'] : array();
				EBootstrap::mergeClass($item['linkOptions'], array('dropdown-toggle'));
				
				$item['linkOptions']['data-toggle'] = 'dropdown';
				$item['label'] .= '<b class="caret"></b>';
			}
			else {
				if ($this->pills)
					$item['linkOptions']['data-toggle'] = 'pill';
				else
					$item['linkOptions']['data-toggle'] = 'tab';
			}
								
            echo EBootstrap::openTag('li', $options);            

            $menu=$this->renderMenuItem($item);
            if(!empty($template)) {
				echo strtr($template,array('{menu}'=>$menu));
            }
            else
				echo $menu;

            if(isset($item['items']) && count($item['items'])) {
            	$options = isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions;
            	if (isset($options['class']))
					$options['class'] = implode(' ', explode(' ', $options['class'])+array('dropdown-menu'));
				else
					$options['class'] = 'dropdown-menu';
            	
				echo "\n".EBootstrap::openTag('ul', $options)."\n";
				$this->renderMenuRecursive($item['items'], true);
				echo EBootstrap::closeTag('ul')."\n";
            }
			
            echo EBootstrap::closeTag('li')."\n";
        }
    }
}


?>