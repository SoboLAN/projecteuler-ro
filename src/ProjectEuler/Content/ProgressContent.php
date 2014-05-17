<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Database;
use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;

use PDO as PDO;
use PDOException as PDOException;

class ProgressContent
{
    public function getProblemsInfo()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT problem_id, is_translated, publish_date, title_romanian ' .
                'FROM translations ' .
                'ORDER BY problem_id ASC'
            );
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProgressContent::getProblemsInfo failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array(
                'id' => $row->problem_id,
                'is_translated' => ($row->is_translated == 1),
                'publish_date' => $row->publish_date,
                'title_romanian' => $row->title_romanian
            );
        }
        
        return $result;
    }
}
