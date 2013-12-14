<?php

namespace ProjectEuler;

use ProjectEuler\Main\Database;

class PEProblem
{
	private $problem_id;
	private $is_translated;
	private $title_english;
	private $title_romanian;
	private $text_english;
	private $text_romanian;
	
	private $last_main_update;
		
	private $publish_date;
	
	public function __construct($arg_problem_id, $arg_is_translated, $arg_title_english, $arg_title_romanian, $arg_text_english, $arg_text_romanian)
	{
		$this->problem_id = $arg_problem_id;
		$this->is_translated = $arg_is_translated == '0' ? false: true;
		$this->title_romanian = $arg_title_romanian;
		$this->title_english = $arg_title_english;
		$this->text_romanian = $arg_text_romanian;
		$this->text_english = $arg_text_english;
	}
	
	public static function withID($problem_id)
	{
        $db = Database::getConnection();
        
		$statement = $db->prepare('SELECT * FROM translations WHERE problem_id=?');
        
        $statement->bindParam(1, $problem_id, \PDO::PARAM_INT);
        $statement->execute();
        
		if ($statement->rowCount() !== 1)
		{
			return false;
		}
		
		$row = $statement->fetch(\PDO::FETCH_OBJ);
		
		$instance = new self($problem_id, $row->is_translated, $row->title_english, $row->title_romanian, $row->text_english, $row->text_romanian);
		
		$instance->setPublishDate($row->publish_date);
        
		return $instance;
	}
		
	public function isTranslated ()
	{
		return $this->is_translated;
	}
		
	public function getProblemID ()
	{
		return $this->problem_id;
	}
		
	public function getTitleEN ()
	{
		return $this->title_english;
	}
		
	public function getTitleRO ()
	{
		return $this->title_romanian;
	}
		
	public function getTextEN ()
	{
		return $this->text_english;
	}
		
	public function getTextRO ()
	{
		return $this->text_romanian;
	}
		
	public function setLastMainUpdate ($timestamp)
	{
		$this->last_main_update = $timestamp;
	}
		
	public function getLastMainUpdate ()
	{
		return $this->last_main_update;
	}
		
	public function setPublishDate ($pubdate)
	{
		$this->publish_date = $pubdate;
	}
	
	public function getPublishDate ()
	{
		return $this->publish_date;
	}
	
	public function increaseHits ()
	{
		$db = Database::getConnection();
		
		$statement = $db->prepare('UPDATE translations SET hits=hits+1 WHERE problem_id=?');
        
        $statement->bindParam(1, $this->problem_id, \PDO::PARAM_INT);
        $statement->execute();
			
		if ($statement->rowCount() !== 1)
		{
			return false;
		}
        
		return true;
	}
}
