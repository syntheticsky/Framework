<?php

/*
* Class: ViewIndex
*
* View of index page.
*/

class ViewAdmin extends View
{
  public function __construct($pageName)
  {
    $this->data['_globTmp'] = ADMIN_GLOBAL_TPL;
    $this->data['JS_SCRIPT'] = DEF_JS_SCRIPT;
    $this->data['CSS_DIR'] = CSS_DIR;
    $this->data["_nextPage"] = ADMIN_PAGE . DS . 'index';
    $this->data["PAGE_TITLE"] = ADMIN_PAGE_TITLE;
  }
}
