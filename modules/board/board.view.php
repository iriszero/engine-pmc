<?php
	
	class BoardModule_View extends BoardModule {
		
		function printArticlePrefix($orderKey, $category=NULL) {
			$html = '';
			
			if (!$orderKey)
				$html .= '<span class="dot-blank">&#183</span>';
			else {
				$depth = (int)(strlen($orderKey) / 2);
				for ($i=0; $i<$depth; $i++)
					$html .= '<span class="reply-blank">&nbsp;</span>';
				$html .= '<div class="reply-icon"></div>&nbsp;';
			}
			if ($category)
				$html .= '<span class="tag-type">['.$category.']&nbsp;</span>';
			
			echo $html;
		}
		
		function dispList() {
			if (!$this->boardInfo)
				self::execTemplate('board_not_found');
				
			else {
				Context::set('nowPage', $this->nowPage);
				Context::set('pageNumbers', self::getModel()->getPageNumbers());
				Context::set('QS', substr(
						($_GET['board_name'] ? '&board_name=' . $_GET['board_name']  : '') .
						($_GET['aop'] ? '&aop=' . $_GET['aop']  : '') 
					,1)
				);
				Context::set('articleData', array_merge(
						self::getModel()->getNoticeArticles(),
						self::getModel()->getArticleDatas()
					)
				);
				self::execTemplate('board');
			}
		}
		
		function dispArticle() {
			if (!$this->articleNo)
				self::execTemplate('article_not_found');
				
			else {
				$data = self::getModel()->getArticleData();
				if (!$data) {
					self::execTemplate('article_not_found');
					return;
				}
				//if ($data->read_permission)
				
				Context::set('title', $data->title);
				Context::set('board', ($data->boardName_kr ? $data->boardName_kr : $data->boardName));
				Context::set('upload_time', $data->upload_time);
				Context::set('writer', $data->writerNick);
				Context::set('url', (USE_SHORT_URL ? 
					getUrl() . '/' . $this->articleNo :
					getUrl() . '/?module=board&action=dispArticle&article_no=' . $this->articleNo
				));
				Context::set('content', $data->content);
				
				self::execTemplate('article');
			}
		}
		
	}
	