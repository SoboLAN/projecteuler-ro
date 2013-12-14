<?php
	require_once ('db.config.php');

	class DBConn
	{
		private $db_connect_id;
		private $queryresult = false;
		private $sql_error_sql = '';
		private $sql_transaction_in_progress = false;

		public function __construct ()
		{
			$this->db_connect_id = @new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_DBNAME);

			//equals to 0 if no error...
			if ($this->db_connect_id->connect_errno != 0)
			{
				die ('FAILED TO CONNECT TO DATABASE: ' . $this->db_connect_id->connect_error);
			}
			
			$this->db_connect_id->set_charset("utf8");
		}

		/**
		* Closes the connection to the database server.
		*
		* @return bool true if connection closes successfully, false otherwise.
		*
		* @access public
		*/
		public function closeConnection ()
		{
			$result = $this->db_connect_id->close ();

			return $result;
		}

		private function sql_error ($querytext)
		{
			$this->sql_error_sql = $querytext;

			if ($this->sql_transaction_in_progress)
			{
				$this->sqlTransaction ('rollback');
			}
			
		
			//blabla
		}
		
		/** Tells whether the last executed query (including prepared statements) triggered an error.
		*
		* @return mixed the sql statement that triggered the error, FALSE if no error was triggered.
		*
		* @access public
		*/
		public function error ()
		{
			return (($this->sql_error_sql == '') ? false : $this->sql_error_sql);
		}

		/**
		* Executes a query or a MySQL command.
		*
		* @param string $querytext The query or MySQL command to be executed.
		*
		* @return bool false if the querytext is empty or if the query cannot be executed, true if successful.
		*
		* @access public
		*/
		public function executeQuery ($querytext)
		{
			if ($querytext == '')
			{
				return false;
			}

			$this->queryresult = NULL;
			$this->sql_error_sql = '';

			if (($this->queryresult = $this->db_connect_id->query ($querytext)) === false)
			{
				$this->sql_error ($querytext);

				return false;
			}

			return true;
		}
		
		public function escape ($string_to_be_escaped)
		{
			return $this->db_connect_id->real_escape_string ($string_to_be_escaped);
		}
		
		public function seek ($offset = 0)
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}

			if ($this->queryresult === false)
			{
				return false;
			}

			if (! is_int ($offset) OR $offset < 0 OR $offset >= $this->queryresult->num_rows)
			{
				return false;
			}

			if (($seek_result = $this->queryresult->data_seek ($offset)) === false)
			{
				return false;
			}
		}
		
		public function nextRowNum ()
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}
			
			if (($fetch_result = $this->queryresult->fetch_row ()) == NULL)
			{
				return false;
			}
			
			return $fetch_result;
		}
		
		public function nextRowAssoc ()
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}
			
			if (($fetch_result = $this->queryresult->fetch_assoc ()) == NULL)
			{
				return false;
			}
			
			return $fetch_result;
		}
		
		public function getRowNum ($offset = 0)
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}
			
			$this->seek ($offset);
			
			if (($fetch_result = $this->queryresult->fetch_row ()) == NULL)
			{
				return false;
			}
			
			return $fetch_result;
		}
		
		public function getRowAssoc ($offset = 0)
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}
			
			$this->seek ($offset);
			
			if (($fetch_result = $this->queryresult->fetch_assoc ()) == NULL)
			{
				return false;
			}
			
			return $fetch_result;
		}

		public function getRowCount ()
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}

			if ($this->queryresult === false)
			{
				return false;
			}

			return $this->queryresult->num_rows;
		}

		public function getAffectedRows ()
		{
			if ($this->sql_error_sql != '')
			{
				return false;
			}

			return $this->db_connect_id->affected_rows;
		}	

		/** Starts, commits or rolls back a transaction.
		*
		* @param string $status Can be one of these values: begin, commit, rollback.
		*
		* @return bool true on success, false on failure.
		*/
		public function sqlTransaction ($status = 'begin')
		{
			switch ($status)
			{
				case 'begin':
				{
					//returns TRUE on success, FALSE on failure
					$result = $this->db_connect_id->autocommit (false);

					//mark the start of the transaction
					$this->sql_transaction_in_progress = true;

					//return the result
					return $result;	
				}

				case 'commit':
				{
					if ($this->sql_transaction_in_progress)
					{
						//returns TRUE on success, FALSE on failure
						$result = $this->db_connect_id->commit ();

						//returns TRUE on success, FALSE on failure
						$result_2 = $this->db_connect_id->autocommit (true);

						//mark the end of the transaction
						$this->sql_transaction_in_progress = false;

						//successful only if both the commit and the autocommit are successful
						return ($result AND $result_2);
					}
					
					return true;
				}

				case 'rollback':
				{
					if ($this->sql_transaction_in_progress)
					{
						//returns TRUE on success, FALSE on failure
						$result = $this->db_connect_id->rollback ();

						//returns TRUE on success, FALSE on failure
						$result_2 = $this->db_connect_id->autocommit (true);

						//mark the end of the transaction
						$this->sql_transaction_in_progress = false;

						//successful only if both the rollback and the autocommit are successful
						return ($result AND $result_2);
					}
					
					return true;
				}
				
				default: return false;
			}
		}
	}
?>