<?php

/**
 * Table_Comment
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class Table_Comment extends Core_Table
{
	public function __construct()
	{
		parent::__construct('comment');
	}

	public function getList($orderBy = FALSE,
	                        $order = FALSE,
	                        $limit = FALSE,
	                        $name = FALSE)
	{

		if ($orderBy == FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order === FALSE) {
			$order = 'desc';
		}
		$order = ($order == 'asc' ? 'asc' : 'desc');

		$sql = "SELECT c.`id_" . $this->table . "`,
		               c.`user`,
		               p.`name` AS post,
		               c.`date`
		        FROM `" . tableName($this->table) . "` AS c
		        LEFT JOIN `" . tableName('post') . "` AS p USING (`id_post`)
		        " . ($name ? "WHERE `user` LIKE '" . clearInput($name) . "%'" : '') . "
				ORDER BY " . $orderBy . " " . $order . " "
				. ($limit ? "LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $lines;
	}

	public function getCount($name = FALSE)
	{
		$sql = "SELECT count(*) AS count
		        FROM `" . tableName($this->table) . "`
		        " . ($name ? "WHERE `user` LIKE '" . clearInput($name) . "%'" : '');
		try{
			$line = $this->db->getRow($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $line['count'];
	}
}
