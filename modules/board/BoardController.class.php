<?php

	class BoardController extends Controller {
		
		const DEFAULT_AOP = 20;
		
		public function init() {
			$action = $this->module->action;
			
			switch ($action) {
				case 'dispList' :
					$boardName = isset($_GET['board_name']) ? escape($_GET['board_name']) : NULL;
					$aop = isset($_GET['aop']) ? escape($_GET['aop']) : self::DEFAULT_AOP;
					$nowPage = isset($_GET['page']) ? escape($_GET['page']) : 1;

					if ($boardName === NULL && $_GET['menu']) {
						$boardInfo = $this->model->getBoardInfo($_GET['menu']);

						if (!$boardInfo) {
							Context::printErrorPage(array(
								'en' => 'Cannot excute board - current menu is not connected with any board',
								'kr' => '게시판을 실행 할 수 없습니다 - 해당 메뉴와 연결된 게시판이 없습니다'
							));
							return;
						}
					}else if ($boardName === NULL) {
						Context::printErrorPage(array(
							'en' => 'Cannot excute board - board ID not defined',
							'kr' => '게시판을 실행 할 수 없습니다 - 게시판 ID가 지정되지 않음'
						));
						return;
					}else
						$boardInfo = $this->model->getBoardInfo($boardName);

					
					$boardId = $boardInfo->id;
					$this->model->setProperties(array(
						'aop' => $aop,
						'boardId' => $boardId,
						'nowPage' => $nowPage
					));
					$this->view->nowPage = $nowPage;
					break;

				case 'dispArticle' :
					$articleNo = $_GET['article_no'] ? escape($_GET['article_no']) : ($_GET['no'] ? escape($_GET['no']) : NULL);
					$articleData = $this->model->getArticleData($articleNo);
					$boardName = $articleData->boardName;

					$this->view->setProperties(array(
						'articleNo' => $articleNo,
						'articleData' => $articleData,
						'boardName' => $boardName
					));
					break;
			}

			Context::getInstance()->selectedMenu = $boardName;
		}

	}

