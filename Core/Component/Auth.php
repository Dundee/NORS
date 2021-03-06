<?php

/**
 * Adds authentication and authorization to Component class
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Component_Auth extends Core_Component
{
	/**
	* Current user
	* @var Core_User $user
	*/
	protected $user;

	public function __construct(Core_Controller $controller = NULL,
	                            $name = FALSE,
	                            $params = FALSE)
	{
		parent::__construct($controller, $name, $params);
		$userModel = new Table_User();
		$this->user = new Core_User($userModel);
	}

	public function auth()
	{
		if (!$this->user->logged()) return FALSE;
		return TRUE;
	}
}
