<?php
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
		
		public function __construct ($arg_problem_id, $arg_is_translated, $arg_title_english, $arg_title_romanian, $arg_text_english, $arg_text_romanian)
		{
			$this->problem_id = $arg_problem_id;
			$this->is_translated = $arg_is_translated == '0' ? false: true;
			$this->title_romanian = $arg_title_romanian;
			$this->title_english = $arg_title_english;
			$this->text_romanian = $arg_text_romanian;
			$this->text_english = $arg_text_english;
		}
		
		public static function withID ($problem_id)
		{
			require_once ('db.class.php');
			
			$dbconn = new DBConn ();
			
			$problem_id = $dbconn->escape ($problem_id);
			
			$r = $dbconn->executeQuery ("SELECT * FROM translations WHERE problem_id=" . $problem_id);
			
			if (! $r OR $dbconn->getRowCount () !== 1)
			{
				return false;
			}
			
			$row = $dbconn->nextRowAssoc ();
			
			$instance = new self ($problem_id, $row['is_translated'], $row['title_english'], $row['title_romanian'], $row['text_english'], $row['text_romanian']);
			
			$instance->setPublishDate ($row['publish_date']);
			
			$dbconn->closeConnection ();
			
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
			require_once ('db.class.php');
			
			$db = new DBConn ();
			
			$r = $db->executeQuery ("UPDATE translations SET hits=hits+1 WHERE problem_id=" . $this->problem_id);
			
			if (! $r)
			{
				return false;
			}
			
			$db->closeConnection ();
			
			return true;
		}
	}
?>