<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Database;
use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;

use PDO as PDO;
use PDOException as PDOException;

class ShowAllContent
{
    public function getProblems($translated)
    {
        $db = Database::getConnection();
        
        try {
            $translatedBool = (bool) $translated;
            $field = $translatedBool ? 'text_romanian' : 'text_english';
            $paramValue = $translatedBool ? 1 : 0;
            
            $statement = $db->prepare(
                "SELECT problem_id, publish_date, $field AS text " .
                'FROM translations ' .
                'WHERE is_translated=? ' .
                'ORDER BY problem_id ASC'
            );
            $statement->bindParam(1, $paramValue, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ShowAllContent::getProblems with translated $paramValue failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array(
                'id' => $row->problem_id,
                'text' => $row->text,
                'publish_date' => $row->publish_date
            );
        }
        
        return $result;
    }
}
