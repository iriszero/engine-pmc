<?php

	class BoardArticleController extends Controller {
			
		public function init() {
			$articleNo = $_GET['article_no'] ? escape($_GET['article_no']) : ($_GET['no'] ? escape($_GET['no']) : NULL);
			$articleData = $this->model->getArticleData($articleNo);
			$boardName = $articleData->boardName;
			
			if ($articleData->readable_group) {
				$me = User::getCurrent();
				if (!$me || !$me->checkGroup($articleData->readable_group)) {
					goBack('글을 볼 권한이 없습니다');
				}
			}
			$this->view->commentable = true;
			if ($articleData->commentable_group) {
				$me = User::getCurrent();
				if (!$me || !$me->checkGroup($articleData->commentable_group))
					$this->view->commentable = false;
			}
			
			$commentDatas = $this->model->getArticleComments($articleNo);
			$fileDatas = $this->model->getArticleFiles($articleNo);
			
			for ($i=0; $i<count($commentDatas); $i++) {
				$commentDatas[$i]->content = join('<br>', explode("\n", $commentDatas[$i]->content));
				$commentDatas[$i]->writer = USE_REAL_NAME ? $commentDatas[$i]->user_name : $commentDatas[$i]->nick_name;
			}
			// 조회수
			$row = DBHandler::for_table('article')
				->where('no', $articleData->no)
				->find_one();

			if ($row) {
				$row->set_expr('hits', 'hits + 1');
				$row->save();
			}

			if (User::getCurrent()) {
				$adminGroup = isset($articleData->admin_group) ? 
					array_merge(json_decode($articleData->admin_group), User::getMasterAdmin()) :
					User::getMasterAdmin();

				$isBoardAdmin = User::getCurrent()->checkGroup($adminGroup);
			}

			$this->view->setProperties(array(
				'articleNo' => $articleNo,
				'articleData' => $articleData,
				'boardName' => $boardName,
				'commentDatas' => $commentDatas,
				'fileDatas' => $fileDatas,
				'isBoardAdmin' => $isBoardAdmin
			));
			
			Context::getInstance()->selectedMenu = $boardName;
		}

	}
