<?php

/**
* Component_DumpFilter
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Component_DumpFilter extends Core_Component_Auth
{
	public $helpers = array('Form');

	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE)
	{
		$this->setData('value', $this->request->getPost('name'));
		$this->setData('table', $params['table']);
	}
}
