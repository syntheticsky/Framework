<?php

/*
* Class: Helper
*
* This class contains methods that are
* required for forming some html content
*/

class Helper
{
	protected static $instance;
	protected $request;

    private $config;

	private  function __construct()
	{
		$this->request = Request::getInstance();
        $this->config = Yaml::parse(__DIR__ . '/../' . 'config.yml');
	}

	public static function getInstance()
	{
		if (null === self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

    public function getConfig() {
        return $this->config;
    }

	public function getPaginator($count, $itemsPerPage = ITEMS_PER_PAGE, $page = 1)
	{
		$queryString = '';
		$get = $this->request->getGet();
		if ($get)
		{
			unset($get['page']);
			$queryString = '&amp;' . http_build_query($get, '', '&amp;');
		}

		$totalCount = ceil($count / $itemsPerPage);
		$paginator = $this->getNavigation($count, $itemsPerPage, $page);
		$paginatorStr = '';

		if (count($paginator) > 1)
		{
			foreach ($paginator as $key => $value) {
				switch ($value)
				{
					case '<<';
						$paginatorStr .= '<a href = "?page=1' . $queryString . '">(1)<<... </a>';
					break;

					case '...';
						$paginatorStr .= ' ... ';
					break;

					case '>>';
						$paginatorStr .= '<a href = "?page=' . $totalCount . $queryString . '"> ...>>(' . $totalCount . ')</a>';
					break;

					default:
						if ($value == $page)
						{
							$paginatorStr .= '<b>' . $value . '</b>';
						}
						else
						{
							$paginatorStr .= '<a href = "?page=' . $value . $queryString . '">' . $value . '</a>';
						}

						if (isset($paginator[$key + 1]) && is_numeric($paginator[$key + 1]))
						{
							$paginatorStr .= ', ';
						}
				}
			}
		}
		return $paginatorStr;
	}
	public function getSearchNavigation($messagesAmount, $text, $currPage = 1)
	{
		$pagesAmount = ceil($messagesAmount /MAX_SEARCH_RESULTS);

		$navigation = $this->getNavigation($messagesAmount, MAX_SEARCH_RESULTS, $currPage);

		$navigationSize = count($navigation);

		for ($i = 0; $i < $navigationSize; $i++)
		{
			switch ($navigation[$i])
			{
				case "<<";
				$navigationStr .= "<a href = '?page=search&sheet=1&text=$text'>(1)<<... </a>";
				break;

				case "...";
				$navigationStr .= " ... ";
				break;

				case ">>";
				$navigationStr .= "<a href = '?page=search&sheet=$pagesAmount&text=$text'> ...>>($pagesAmount)</a>";
				break;

				default:
				if ($navigation[$i] == $currPage)
				{
					$navigationStr .= "<b>[".$navigation[$i]."]</b>";
				}
				else
				{
					$navigationStr .= "<a href = '?page=search&sheet={$navigation[$i]}&text=$text'>{$navigation[$i]}</a>";
				}

				if (is_numeric($navigation[$i + 1]))
				{
					$navigationStr .= ", ";
				}
			}
		}

		return $navigationStr;
	}

	public function getTopicsNavigation($currPage = 1, $topicsAmount)
	{
		$pagesAmount = ceil($topicsAmount / TOPICS_PER_PAGE);

		$navigation = $this->getNavigation($topicsAmount, TOPICS_PER_PAGE, $currPage);

		$navigationSize = count($navigation);

		for ($i = 0; $i < $navigationSize; $i++)
		{
			switch ($navigation[$i])
			{
				case "<<";
				$navigationStr .= "<a href = '?page=index&sheet=1'>(1)<<... </a>";
				break;

				case "...";
				$navigationStr .= " ... ";
				break;

				case ">>";
				$navigationStr .= "<a href = '?page=index&sheet=".$pagesAmount."'> ...>>(".$pagesAmount.")</a>";
				break;

				default:
				if ($navigation[$i] == $currPage)
				{
					$navigationStr .= "<b>[".$navigation[$i]."]</b>";
				}
				else
				{
					$navigationStr .= "<a href = '?page=index&sheet=".$navigation[$i]."'>".$navigation[$i]."</a>";
				}

				if (is_numeric($navigation[$i + 1]))
				{
					$navigationStr .= ", ";
				}
			}
		}

		return $navigationStr;
	}

	public function getMessagesNavigation($messagesAmount, $idTopic, $currPage = 1)
	{
		$navigationStr = "";

		$navigation = $this->getNavigation($messagesAmount, MESSAGES_PER_PAGE, $currPage);

		$pagesAmount = ceil($messagesAmount / MESSAGES_PER_PAGE);

		$navigationSize = count($navigation);

		for ($i = 0; $i < $navigationSize; $i++)
		{
			switch ($navigation[$i])
			{
				case "<<";
				$navigationStr .= "<a href = '?page=topic&id=".$idTopic."&sheet=1'>(1)<<... </a>";
				break;

				case "...";
				$navigationStr .= " ... ";
				break;

				case ">>";
				$navigationStr .= "<a href = '?page=topic&id=".$idTopic."&sheet=".$pagesAmount."'> ...>>(".$pagesAmount.")</a>";
				break;

				default:
				if ($navigation[$i] == $currPage)
				{
					$navigationStr .= "<b>[".$navigation[$i]."]</b>";
				}
				else
				{
					$navigationStr .= "<a href = '?page=topic&id=".$idTopic."&sheet=".$navigation[$i]."'>".$navigation[$i]."</a>";
				}

				if (is_numeric($navigation[$i + 1]))
				{
					$navigationStr .= ", ";
				}
			}
		}

		return $navigationStr;
	}

	public function getShortNavigation($itemsAmount, $itemsPerPage, $idTopic)
	{
		$navigationStr = "";
		$pagesAmount = ceil($itemsAmount / $itemsPerPage);

		if ($pagesAmount == 0)
		{
			$navigationStr = "<a class = 'smallAndGrey' href = '?page=topic&id=".$idTopic."&sheet=1'>1</a>";	;
		}

		if ($pagesAmount <= 13)
		{
			for ($i = 1; $i <= $pagesAmount; $i++)
			{
				$navigationStr .= "<a class = 'smallAndGrey' href = '?page=topic&id=".$idTopic."&sheet=".$i."'>".$i."</a>";

				if ($i != $pagesAmount)
				{
					$navigationStr .= ", ";
				}
			}
		}
		else
		{
			for ($i = 1; $i <= 4; $i++)
			{
				$navigationStr .= "<a class = 'smallAndGrey' href = '?page=topic&id=".$idTopic."&sheet=".$i."'>".$i."</a>";

				if ($i != 5)
				{
					$navigationStr .= ", ";
				}
			}

			$navigationStr .= " ... ";

			for ($i = $pagesAmount - 2; $i <= $pagesAmount; $i++)
			{
				$navigationStr .= "<a class = 'smallAndGrey' href = '?page=topic&id=".$idTopic."&sheet=".$i."'>".$i."</a>";

				if ($i != $pagesAmount )
				{
					$navigationStr .= ", ";
				}
			}
		}

		return $navigationStr;
	}

	private function getNavigation($itemsAmount, $itemsPerPage, $currPage = 1)
	{
		$navigation = array();

		$leftOffset = 0;
		$rigthOffset = 0;

		$pagesAmount = ceil($itemsAmount / $itemsPerPage);

		if ($pagesAmount <= 13)
		{
			for ($i = 0; $i < $pagesAmount; $i++)
			{
				$navigation[] = $i + 1;
			}
		}
		else
		{
			if ($pagesAmount == 0)
			{
				$pagesAmount = 1;
			}

			if ($currPage > 3)
			{
				if ($currPage < ($pagesAmount / 2) + 1)
				{
					$leftOffset = $currPage - 3;
					$navigation[] = "<<";
				}
			}

			if ($currPage < ($pagesAmount - 2))
			{
				if ($currPage >= ($pagesAmount / 2) + 1)
				{
					$rightOffset = $pagesAmount - $currPage - 2;
				}
			}

			for ($i = 0 + $leftOffset; $i < 5 + $leftOffset; $i++)
			{
				$navigation[] = $i + 1;
			}

			$navigation[] = "...";

			for ($i = 5 + $rightOffset; $i > 0 + $rightOffset; $i--)
			{
				$navigation[] = $pagesAmount - $i + 1;
			}

			if ($currPage < ($pagesAmount - 2))
			{
				if ($currPage >= ($pagesAmount / 2)  + 1)
				{
					$navigation[] = ">>";
				}
			}
		}

		return $navigation;
	}

	public function getLightStr($text)
	{
		return "<span style = 'background-color:white;'>".$text."</span>";
	}
}
