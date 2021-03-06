<?php

/**
 * Table_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class Table_Post extends Core_Table
{
	public function __construct()
	{
		parent::__construct('post');
	}

	public function getByCategory($category, $orderBy = FALSE, $order = FALSE, $limit = FALSE)
	{
		if ($orderBy === FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order === FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT p.*,
		               c.`name` AS category_name,
		               count(co.`id_comment`) AS num_of_comments,
		               IF(u.`fullname` != '', u.`fullname`, u.`name`) AS user_name
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('category') . "` AS c USING (`id_category`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        LEFT JOIN `" . tableName('user') . "` AS u USING (`id_user`)
		        WHERE p.`active` = 1 AND
		              p.`date` <= '" . date("Y-m-d H:i:00") . "' AND
		              p.`id_category` = '" . intval($category) . "'
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order)
		        . ($limit ? " LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				if (strpos($ex->getMessage(), "comment' doesn't exist") !== FALSE) {
					$comment = new Table_Comment();
					$comment->create();
				} else $this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		$class = 'ActiveRecord_' . ucfirst($this->table);
		if ( !iterable($lines) ) return NULL;
		foreach ($lines as $line) {
			$current = new $class();
			$current->setFromArray($line);
			$instances[] = $current;
		}
		return $instances;
	}

	public function getActive($orderBy=FALSE, $order=FALSE, $limit = FALSE)
	{
		if ($orderBy==FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order===FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT p.*,
		               c.`name` AS category_name,
		               count(`id_comment`) AS num_of_comments,
		               IF(u.`fullname` != '', u.`fullname`, u.`name`) AS user_name
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('category') . "` AS c USING (`id_category`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        LEFT JOIN `" . tableName('user') . "` AS u USING (`id_user`)
		        WHERE p.`active` = 1 AND p.`date` <= '" . date("Y-m-d H:i:00") . "'
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order)
		        . ($limit ? " LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				if (strpos($ex->getMessage(), "comment' doesn't exist") !== FALSE) {
					$comment = new Table_Comment();
					$comment->create();
				} elseif (strpos($ex->getMessage(), "user' doesn't exist") !== FALSE) {
					$comment = new Table_User();
					$comment->create();
				} else $this->create();
				echo $ex->getMessage();
				return FALSE;
			} else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		$class = 'ActiveRecord_' . ucfirst($this->table);
		if ( !iterable($lines) ) return NULL;
		foreach ($lines as $line) {
			$current = new $class();
			$current->setFromArray($line);
			$instances[] = $current;
		}
		return $instances;
	}

	public function getCountByCategory($category)
	{
		$sql = "SELECT count(*) AS count
		        FROM `" . tableName($this->table) . "`
		        WHERE `active` = 1 AND
		              `date` <= '" . date("Y-m-d H:00:00") . "' AND
		              `id_category` = '" . intval($category) . "'";
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

		$sql = "SELECT p.`id_" . $this->table . "`,
		               p.`name`,
		               cat.`name` AS category,
		               COUNT(`id_comment`) AS num_of_comments,
		               p.`seen` AS num_of_visits,
		               CONCAT(p.`karma`,'/', p.`evaluated`) AS karma,
		               p.`date`,
		               p.`active`
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('comment') . "` AS c USING (`id_post`)
		        LEFT JOIN `" . tableName('category') . "` AS cat USING (`id_category`)";

		if ($name) {
			$sql .= " WHERE p.`name` LIKE '%" . clearInput($name) . "%' OR p.`id_" . $this->table . "` = '" . clearInput($name) . "'";
		}

		$sql .= " GROUP BY p.`id_post`
		          ORDER BY " . $orderBy . " " . $order . " "
		          . ($limit ? "LIMIT " . clearInput($limit) : '');

		try{
			$lines = $this->db->getRows($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				if (strpos($ex->getMessage(), "comment' doesn't exist") !== FALSE) {
					$comment = new Table_Comment();
					$comment->create();
				} else $this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $lines;
	}

	public function getAll($orderBy=FALSE, $order=FALSE, $limit = FALSE)
	{
		if ($orderBy==FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order===FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT p.*,
		               c.`name` AS category_name,
		               count(`id_comment`) AS num_of_comments
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('category') . "` AS c USING (`id_category`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order)
		        . ($limit ? " LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		$class = 'ActiveRecord_' . ucfirst($this->table);
		if ( !iterable($lines) ) return NULL;
		foreach ($lines as $line) {
			$current = new $class();
			$current->setFromArray($line);
			$instances[] = $current;
		}
		return $instances;
	}

	public function getCount($name = FALSE)
	{
		$sql = "SELECT count(*) AS count
		        FROM `" . tableName($this->table) . "`
		        WHERE `active` = 1";

		if ($name) {
			$sql .= " AND `name` LIKE '%" . clearInput($name) . "%' OR `id_" . $this->table . "` = '" . clearInput($name) . "'";
		}

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
