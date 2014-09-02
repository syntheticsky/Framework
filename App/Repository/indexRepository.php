<?php

/*
* Class: pagesRepository
*
* Interface that works with the table Topics
*/

class indexRepository extends Repository
{
  public $pageTable;
  private $pageTableColumns;

  private $mysql;

  public function __construct()
  {
    $this->mysql = MySql::getInstance();
    $this->pageTable = 'pages';
    $this->pageTableColumns = $this->mysql->getColumnsNames($this->pageTable);
  }
  public function lastInsertId()
  {
  	return $this->mysql->lastInsertId();
  }

// 	public function find($id)
//   {
//     $query = 'SELECT *
// 	      FROM ' . $this->table . '
// 	      WHERE id = ?';
//     return $this->mysql->select($query, array($id));
//   }
// //return some page of topics
//   public function findAll($page = null)
//   {

//     $query = 'SELECT *
// 	      FROM ' . $this->table . '
// 	      WHERE 1';
// 	  if ($page) {
// 	  	$page = (int)$page;
//     	$from = ADMIN_ITEMS_PER_PAGE * ($page - 1);
//     	$to = $from + ADMIN_ITEMS_PER_PAGE;
//     	$query .= ' LIMIT ' . $from . ', ' . $to;
// 	  }
//     return $this->mysql->select($query);
//   }

  public function getMainMenu()
  {
    $query = 'SELECT id, title, alias
        FROM ' . $this->pageTable . '
        WHERE menu = 1 AND status = 1
        ORDER BY weight ASC';
    $request = Validator::getInstance();
    $menu = $this->mysql->select($query);
    $arg = $request->getUrlParts(0);
    foreach ($menu as $key => $value)
    {
      if ($value['alias'] == $arg || (!$arg && $value['alias'] == 'index'))
      {
        $menu[$key]['active'] = 'active';
      }
      if ($value['alias'] == 'index') {
        $menu[$key]['alias'] = '';
      }
    }
    return $menu;
  }

  public function execute($query)
  {
  	return $this->mysql->execute($query);
  }

  public function findPageByAlias($alias)
  {
    $query = 'SELECT *
              FROM pages
              WHERE alias = :alias';
    if ($result = $this->mysql->select($query, array(':alias' => $alias)))
    {
      return $result[0];
    }
    return false;
  }

  public function findNodeByAlias($alias)
  {
    $query = 'SELECT *
              FROM node
              WHERE alias = :alias';
    if ($result = $this->mysql->select($query, array(':alias' => $alias)))
    {
      return $result[0];
    }
    return false;
  }

  public function getRecentNews()
  {
    $result = array();
    $query = 'SELECT n.*
              FROM node n
              INNER JOIN pages p
                ON p.id = n.page_id
              WHERE p.alias = :alias
              ORDER BY n.updated DESC
              LIMIT 0, 2';

    $params[':alias'] = 'news';

    if ($rows = $this->mysql->select($query, $params))
    {
      foreach ($rows as $row) {
        $ids[] = $row['id'];
      }
      if ($ids)
      {
        $images = $this->getImages($ids);
        foreach ($rows as $key => $row) {
          if (isset($images[$row['id']]))
          {
            $rows[$key]['image'] = $images[$row['id']];
          }
        }
      }
      $result = $rows;
    }

    return $result;
  }

  public function getImages($ids = array())
  {
    $result = array();
    $query = 'SELECT nid, name
              FROM images
              WHERE nid IN (' . implode(', ', $ids) . ')';
    $rows = $this->mysql->execute($query);
    while ($row = $rows->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
      $result[$row['nid']] = $row['name'];
    }

    return $result;
  }

//   public function insert($params)
//   {
//   	return $this->mysql->insert($params, $this->table);
//   }

//   public function update($params, $conditions)
//   {
//   	return $this->mysql->update($params, $conditions, $this->table);
//   }

//   public function getParams($data)
//   {
//   	$params = array();
//     foreach ($this->columns as $name)
//     {
//     	if (isset($data[$name]))
//     	{
//     		$key = ':' . $name;
//     		$params[$key] = $data[$name];
//     	}
//     }
//     return $params;
//   }

//   public function checkUniqueField($value, $fieldName) {
//     return $this->mysql->checkUniqueField($value, $fieldName, $this->table);
//   }

//   public function formatAlias($value)
//   {
//     $value = preg_replace('/[\W\d_А-Яа-я\s]/', '', $value);
//     $value = strtolower($value);
//     if (!$this->checkUniqueField($value, 'alias'))
//     {
//       $this->modifyToUnique($value);
//     }
//   }
//   public function modifyToUnique($value, $prefix = null, $suffix = null) {
//     $prefix ? $value = $prefix . '-' . $value : '';
//     $suffix ? $value = $value . '-' . $suffix : '';
//     return $value;
//   }
// //return some page of topics messages
// 	public function getTopicsMessages($topicId, $messagesAmount, $pageNumber = 1)
// 	{
// 		$topicId = (int)$topicId;
// 		$pageNumber = (int)$pageNumber;

// 		$pagesAmount = ceil($messagesAmount / MESSAGES_PER_PAGE);

// 		$offset = MESSAGES_PER_PAGE;

// 		if ($pageNumber <= ($pagesAmount / 2))
// 		{
// 			$limitStart = ($pageNumber - 1) * MESSAGES_PER_PAGE;

// 			$query = "
// 				SELECT text, submitted
// 				FROM ".MESSAGES_TABLE."
// 				WHERE topic_id = $topicId
// 				ORDER BY submitted
// 				LIMIT ".$limitStart.", ".$offset;

// 			return $this->mysql->select($query);
// 		}
// 		else
// 		{
// 			$limitStart = ($pagesAmount - ($pageNumber - 1) - 1) * MESSAGES_PER_PAGE;

// 			$messagesOnLastPage = $messagesAmount - ($pagesAmount - 1) * MESSAGES_PER_PAGE;

// 			if ($pagesAmount == $pageNumber)
// 			{
// 				$offset = $messagesOnLastPage;
// 			}
// 			else
// 			{
// 				$limitStart -= MESSAGES_PER_PAGE - $messagesOnLastPage;
// 			}

// 			$query = "
// 				SELECT text, submitted
// 				FROM ".MESSAGES_TABLE."
// 				WHERE topic_id = $topicId
// 				ORDER BY submitted DESC LIMIT ".$limitStart.", ".$offset;

// //			echo $query;

// 			$result = $this->mysql->select($query);

// 			if ($result)
// 			{
// 				return array_reverse($result);
// 			}
// 			else
// 			{
// 				return false;
// 			}
// 		}
// 	}

// //return topics title and messages_amount (using in pages navigation)
// 	public function getTopicInfoById($topicId)
// 	{
// 		$topicId = (int)$topicId;

// 		$query = "
// 			SELECT title, messages_amount
// 			FROM ".TOPICS_TABLE."
// 			WHERE id = $topicId
// 			LIMIT 1";

// 		$result = $this->mysql->select($query);

// 		return $result[0];
// 	}

// //check is this id exists
// 	public function checkId($topicId)
// 	{
// 		$topicId = (int)$topicId;

// 		$query = "SELECT id FROM ".TOPICS_TABLE."
// 			WHERE id = ? LIMIT 1";

// 		return $this->mysql->select($query, array($topicId));
// 	}

// 	public function addTopic($title)
// 	{
// 		$query = "INSERT INTO ".TOPICS_TABLE."
// 			(title) VALUES (?)";

// 		$query1 = "UPDATE ".FORUMS_TABLE."
// 			SET topics_amount = topics_amount + 1
// 			WHERE id = 1";

// 		if ($this->mysql->insert($query, array($title)))
// 		{
// 			return $this->mysql->insert($query1);
// 		}
// 		else
// 		{
// 			return false;
// 		}
// 	}

// //return topics amount from table forum (using in topics navigation)
// 	public function getAmount()
// 	{
// 		$query = "SELECT topics_amount FROM ".FORUMS_TABLE."
// 				WHERE id = 1 LIMIT 1";

// 		$result = $this->mysql->select($query);

// 		return $result[0]["topics_amount"];
// 	}

// //checks if the user came to this topic, and if no so - add 1 to views
// 	public function checkViews($topicId)
// 	{
// 		$remotoAddr = $_SERVER["REMOTE_ADDR"];

// 		$query = "SELECT id FROM ".VIEWS_TABLE."
// 			WHERE id_topic = $topicId AND ip = '$remotoAddr'
// 			";

// 		$result = $this->mysql->select($query);

// 		if (!$result)
// 		{
// 			$query = "INSERT INTO ".VIEWS_TABLE."
// 					(id_topic, ip)
// 					VALUES
// 					($topicId, '$remotoAddr')
// 				";

// 			$result = $this->mysql->insert($query);

// 			if ($result)
// 			{
// 				$query = "UPDATE ".TOPICS_TABLE."
// 						SET views = views + 1
// 						WHERE id = $topicId
// 					";
// 				$result = $this->mysql->select($query);
// 			}
// 		}
// 	}
}
