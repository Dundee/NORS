<?php

/**
 * ActiveRecord_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class ActiveRecord_Post extends Core_ActiveRecord
{
	public function __construct($id = false)
	{
		parent::__construct('post', $id);
	}
}