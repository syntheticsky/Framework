<?php

/*
* Class: ViewBase
*
* Attaches templates, build page,
* parse place holders, show page.
*/

class ViewBase
{
  protected $page = "";
  protected $data = array();

  public function __construct()
  {
    $this->data['_globTmp'] = DEF_GLOBAL_TPL;
    $this->data['JS_SCRIPT'] = DEF_JS_SCRIPT;
    $this->data['CSS_DIR'] = CSS_DIR;
  }
//assign data to parse data array
  public function assignData($data = array())
  {
    if (DEBUG_MODE)
    {
      $this->data['DEBUG'][0]["QUERY_AMOUNT"] = Statistics::getSqlQueryAmount();
      $this->data['DEBUG'][0]["SQL_TIME"] = Statistics::getSqlTime();

      Statistics::totalTimerEnd();

      $this->data['DEBUG'][0]["PHP_PERCENTS"] = Statistics::getPhpPercents();
      $this->data['DEBUG'][0]["SQL_PERCENTS"] = Statistics::getSqlPercents();
      $this->data['DEBUG'][0]["TOTAL_TIME"] = Statistics::getTotalTime();
    }
    else
    {
      $this->data['DEBUG'] = array();
    }

    foreach ($data as $key => $val)
    {
      $this->data[$key] = $val;
    }
  }

//attaches necessary moduls
  private function buildPage()
  {
    $tmpPath = GLOB_TEMPLATES_DIR . DS . $this->data["_globTmp"] . TEMPLATES_FILE_TYPE;
    $this->page = file_get_contents($tmpPath);
    $contentPath = TEMPALTES_DIR . DS . $this->data["_nextPage"] . TEMPLATES_FILE_TYPE;
    $content = file_get_contents($contentPath);

    $this->replaceTag("PAGE_CONTENT", $content);

    return true;
  }

//replace all place holders
  private function parseData()
  {
    foreach ($this->data as $tag => $value)
    {
      if (!is_array($value))
      {
	      $this->replaceTag($tag, $value);
      }
      else
      {
	      $this->page = $this->replaceBloc($tag, $value, $this->page);
      }
    }

    return true;
  }

//replace place holders that contains other place holders (recursive function)
  private function replaceBloc($blockTag, $data, $content)
  {
    $scan = array();
    $blockContent = "";

    $blockTag = PLACE_HOLDER_START . strtoupper($blockTag) . PLACE_HOLDER_END;

    $regExp = '/' . $blockTag . '(.*)' . $blockTag . '/s';
    preg_match($regExp, $content, $scan);
    if (isset($scan[0]) && isset($scan[1]))
    {
      $blockTemplate = $scan[1];
      $blockTag = $scan[0];

      $blockCount = count($data);
      if (!$blockCount)
      {
        preg_replace($regExp, '', $blockTemplate);
      }
      for ($i = 0; $i < $blockCount; $i++)
      {
        $partBplockConetnt = $blockTemplate;
        foreach ($data[$i] as $tag => $val)
        {
        	if (!is_array($val))
        	{
        	  $tag = PLACE_HOLDER_START.strtoupper($tag).PLACE_HOLDER_END;
        	  $partBplockConetnt = str_replace($tag, htmlspecialchars_decode($val), $partBplockConetnt);
        	}
        	else
        	{
        	  $partBplockConetnt = $this->replaceBloc($tag, $val, $partBplockConetnt);
        	}
        }
        $blockContent .= $partBplockConetnt;
      }
    }

    return str_replace($blockTag, $blockContent, $content);
  }

//replace sing place holder
  private function replaceTag($tag, $value)
  {
    $tag = PLACE_HOLDER_START.strtoupper($tag).PLACE_HOLDER_END;
    $this->page = str_replace($tag, htmlspecialchars_decode($value), $this->page);

    return true;
  }

  public function display()
  {
    $this->buildPage();
    $this->parseData();

    header('Content-Type: text/html; charset=utf-8');
    print $this->page;

    if (DEBUG_MODE)
    {
      echo '<pre style="color: #777;">';
      print_r($this->data);
      echo '</pre>';
    }
  }
}
