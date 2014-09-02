<?php

/*
* Class: ViewIndex
*
* View of index page.
*/

class ViewIndex extends View
{
  public function __construct($pageName)
  {
    parent::__construct($pageName);
    $this->data["_nextPage"] = INDEX_PAGE;
    $this->data["PAGE_TITLE"] = INDEX_PAGE_TITLE;
  }
}
