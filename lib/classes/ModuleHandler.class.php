<?php
	
	/**
	 * @ author prevdev@gmail.com
	 * @ 2013.05
	 *
	 *
	 * ModuleHandler Class
	 * Load and handling modules
	 */
	
	class ModuleHandler extends Handler {
		
		var $moduleInfo;
		
		static function isModule($moduleID) {
			$path = MODULE_DIR . '/__module.php';
			return (is_file($path) && is_readable($path)) ? true : false;
		}
		
		function ModuleHandler($moduleID) {
			define('MODULE_DIR', ROOT_DIR . '/modules/' . $moduleID);
		}
		
		
		function getModule($moduleID) {
			if (!ModuleHandler::isModule($moduleID)) return NULL;
			
			$filePath = MODULE_DIR . '/__module.php';
			$classID = ucfirst(strtolower($moduleID)) . 'Module';
			
			require $filePath;
			
			if (class_exists($classID)) {
				if (is_file(MODULE_DIR . '/conf/info.json')) {
					$this->moduleInfo = json_decode(readFileContent(MODULE_DIR . '/conf/info.json'));
					if ($this->moduleInfo === NULL)
						Context::printWarning(array(
							'en' => 'Unexpected token ILLEGAL in conf/info.json',
							'kr' => 'conf/info.json 파일 파싱에 실패했습니다'
						));
					if ($this->moduleInfo->layout)
						Context::getInstance()->setLayout($this->moduleInfo->layout);
				}
				
				$_loader = create_function('', "return new ${classID}();");
				
				$GLOBALS['__Module__'] = $_loader();
				return $GLOBALS['__Module__'];
			}
			return NULL;
		}
		
		function getModuleAction($action) {
			if ($action === NULL) return;
			if (!is_file(MODULE_DIR . '/conf/info.json')) return;
			if (!$GLOBALS['__Module__']) return;
			
			if ($this->moduleInfo) {
				$actions = $this->moduleInfo->actions; //array
				
				for ($i=0; $i<count($actions); $i++) {
					if ($action == $actions[$i]->name) {
						switch ($actions[$i]->type) {
							case 'model' :
								$o = $GLOBALS['__Module__']->getModel();
								break;
							case 'view' :
								$o = $GLOBALS['__Module__']->getView();
								break;
							case 'controller' :
								$o = $GLOBALS['__Module__']->getController();
								break;
							default :
								continue;
						}
						
						
						if (method_exists($o, $action)) {
							if ($actions[$i]->layout) 
								Context::getInstance()->setLayout($actions[$i]->layout);
							return create_function('', '$GLOBALS[\'__Module__\']->get' . ucfirst($actions[$i]->type) . '()->' . $action . '();');
						}else {
							Context::printErrorPage(array(
								'en' => 'Cannot execute module action - method do not exists',
								'kr' => '모듈 액션을 실행할 수 없습니다 - 메소드가 존재 하지 않음'
							));
							return NULL;
						}
					}
				}
				Context::printErrorPage(array(
					'en' => 'Cannot execute module action - permission denined by configuration file',
					'kr' => '모듈 액션을 실행할 수 없습니다 - Cofiguration 파일에 의해 권한이 거부됨'
				));
				return NULL;
			}
			return NULL;
		}
		
		public function procModule() {
			$moduleID = Context::getInstance()->moduleID;
			$moduleAction = Context::getInstance()->moduleAction;
			
			
			if (!$moduleID) {
				Context::printErrorPage(array(
					'en' => 'Cannot load module - module not defined',
					'kr' => '모듈을 불러올 수 없습니다 - 모듈이 정의되지 않음'
				));
				return;
				
			}else if (!ModuleHandler::isModule($moduleID)) {
				Context::printErrorPage(array(
					'en' => 'Cannot load module - module not found',
					'kr' => '모듈을 불러올 수 없습니다 - 모듈을 찾을 수 없음'
				));
				return;
			}
			
			$_module = $this->getModule($moduleID);
			if ($_module === NULL) {
				Context::printErrorPage(array(
					'en' => 'Cannot load module in ModuleHandler::procModule',
					'kr' => '모듈을 불러올 수 없습니다 - ModuleHandler::procModule'
				));
				return;
			}
			if ($moduleAction_func = $this->getModuleAction($moduleAction ? $moduleAction : NULL)) {
				$GLOBALS['__ModuleAction__'] = $moduleAction;
				$GLOBALS['__ModuleActionFunc__'] = $moduleAction_func;
			}
			$_module->init();
		}
		
	}
	