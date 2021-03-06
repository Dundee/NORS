<?php


/**
* Provides CSV (by semicolon separated values) output
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_View_Csv extends Core_View
{
	public function __construct(Core_Model $model,$action){
		parent::__construct($model,$action);
	}

	/**
	 * display
	 *
	 */
	public function display(){

		$action = $this->modelAction;
		$this->model->$action();
		$data = $this->model->getData();

		$lines = $data['output'];
		$instance = $data['instance'];
		if (!iterable($lines)) return FALSE;
		$out = '';
		foreach($instance->fields as $key=>$field){
			$out .= $key.'; ';
		}
		$out .= "\n";
		foreach($lines as $line){
			foreach($instance->fields as $key=>$field){
				if (!isset($line[$key])) continue;
				$line[$key] = str_replace(';',',',$line[$key]);
				$line[$key] = nl2br($line[$key]);
				$out .= $line[$key].'; ';
			}
			$out .= "\n";
		}


		headers('application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"export.csv\"");
		echo $out;
	}

	public function __destruct(){

	}
}
