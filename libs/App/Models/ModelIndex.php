<?php

/*
* Class: ModelIndex
*
* Model of index page.
*/
class ModelIndex extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// public function index($page)
	// {
	// 	if ($page['single'])
	// 	{
	// 		$tpl = 'page';
	// 		if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '.tpl'))
	// 		{
	// 			$tpl = $page['template'];
	// 		}
	// 		$nodes = $this->nodesRepo->findByPage($page['id']);
	// 		foreach ($nodes as $key => $value) {
	// 			$this->data['NODE' . $key][] = $value;
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$tpl = 'page-list';
	// 		if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '-list.tpl'))
	// 		{
	// 			$tpl = $page['template'] . '-list';
	// 		}
	// 		$nodes = $this->nodesRepo->findByPage($page['id']);
	// 		$this->data['NODES'] = $nodes;
	// 	}

	// 	$news = $this->repository->getRecentNews();
	// 	foreach ($news as $key => $value) {
	// 		$value['body'] = substr(strip_tags(html_entity_decode($value['body'])), 0, BODY_STRIP_LENGTH);
	// 		$news[$key] = $value;
	// 	}
	// 	$this->data['NEWS'] = $news;

	// 	$this->data["_nextPage"] = $tpl;
	// 	$this->data = array_merge($this->data, $page);
	// }

	// public function pageAction($page)
	// {
	// 	if ($page['single'])
	// 	{
	// 		foreach ($page as $k => $value)
	// 		{
	// 			$page[$k] = stripslashes(html_entity_decode($value));
	// 		}
	// 		$tpl = 'page';
	// 		if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '.tpl'))
	// 		{
	// 			$tpl = $page['template'];
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$gallery = array();
	// 		$hasTag = $this->request->getGet('tag');
	// 		$tpl = 'page-list';
	// 		if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '-list.tpl'))
	// 		{
	// 			$tpl = $page['template'] . '-list';
	// 		}
	// 		$currentPage = $this->request->getGet('page');
	//     if (!$currentPage)
	//     {
	//       $currentPage = 1;
	//     }
	// 		$nodesCount = $this->nodesRepo->countNodesByPage($page['id'], $hasTag);
	// 		$nodes = $this->nodesRepo->findByPage($page['id'], $currentPage, $hasTag);
	// 		foreach ($nodes as $k => $value)
	// 		{
	// 			$value['node_iframe'] = stripslashes($value['node_iframe']);
	// 			if (isset($value['node_image']))
	// 			{
	// 				$value['node_image'] = '<img src="/uploads/thumbs/' . $value['node_image'] . '">';
	// 			}
	// 			else
	// 			{
	// 				$value['node_image'] = ''	;
	// 			}
	// 			if ($value['node_iframe'])
	// 			{
	// 				$value['node_image'] = $value['node_iframe'];
	// 			}
	// 			if ($page['alias'] == 'news')
	// 			{
	// 				$value['node_body'] = substr(strip_tags(html_entity_decode($value['node_body'])), 0, BODY_STRIP_LENGTH);
	// 			}
	// 			$nodes[$k] = $value;
	// 			$gallery = array_merge($gallery, $this->nodesRepo->getNodeImages($value['node_id']));
	// 		}
	// 		$this->data['GALLERY'] = $gallery;
	// 		$tags = $this->nodesRepo->getVideoTags();
	// 		foreach ($tags as $key => $value)
	// 		{
	// 			$tags[$key]['tag_active'] = $value['tag_id'] == $hasTag ? 'active' : '';
	// 		}
	// 		if ($tags[0])
	// 		{
	// 			$this->data['TAGS'] = $tags;
	// 		}
	// 		$this->data['NODES'] = $nodes;
 //    	$this->data['PAGINATOR'] = $this->helper->getPaginator($nodesCount, ITEMS_PER_PAGE, $currentPage);
	// 	}
	// 	$this->data["_nextPage"] = $tpl;
	// 	$this->data = array_merge($this->data, $page);
	// }

	// public function nodeAction($node)
	// {
	// 	$this->data["_nextPage"] = 'node';
	// 	$this->data = array_merge($this->data, $node);
	// 	$images = $this->nodesRepo->getNodeImages($node['id']);
	// 	$pTitle = $this->data['PAGE_TITLE'] = $this->nodesRepo->getPageTitle($node['page_id']);
	// 	$this->data['PAGE_TITLE'] = $pTitle[0]['title'];
	// 	$this->data['IMAGE'] = $images[0]['image_name'];
	// 	$this->data['IMAGE_TITLE'] = $images[0]['image_title'] ? $images[0]['image_title'] : '<br />';
	// 	// if ($page['single'])
	// 	// {
	// 	// 	$tpl = 'default';
	// 	// 	if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '.tpl'))
	// 	// 	{
	// 	// 		$tpl = $page['template'];
	// 	// 	}
	// 	// }
	// 	// else
	// 	// {
	// 	// 	$tpl = 'default-list';
	// 	// 	if ($page['template'] && file_exists(getcwd() . DS . TEMPALTES_DIR . DS . $page['template'] . '-list.tpl'))
	// 	// 	{
	// 	// 		$tpl = $page['template'] . '-list.tpl';
	// 	// 	}
	// 	// 	$this->data['nodes'] = $this->nodesRepo->findByPage($page['id']);
	// 	// }
	// 	// $this->data["_nextPage"] = $tpl;
	// 	// $this->data = array_merge($this->data, $page);
	// }
	// public function notFound()
	// {
	// 	$this->data['_nextPage'] = '404';
	// }
}
