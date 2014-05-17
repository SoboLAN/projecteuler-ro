<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Database;
use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;

use PDO as PDO;
use PDOException as PDOException;

class ProblemContent
{
    public function getProblem($problemId)
    {
        $db = Database::getConnection();
        
        try {
            $problemSt = $db->prepare(
                'SELECT title_romanian, text_romanian, text_english, publish_date, last_main_update, is_translated, hits ' .
                'FROM translations ' .
                'WHERE problem_id=?'
            );
            
            $problemSt->bindParam(1, $problemId, PDO::PARAM_INT);
            $problemSt->execute();
            $dbProblem = $problemSt->rowCount() == 0 ? false : $problemSt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            $message = "calling ProblemContent::getProblem with problem id $problemId failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        if (! $dbProblem) {
            return array();
        }
        
        $result = array(
            'id' => $problemId,
            'title_romanian' => $dbProblem->title_romanian,
            'text_romanian' => $dbProblem->text_romanian,
            'text_english' => $dbProblem->text_english,
            'publish_date' => $dbProblem->publish_date,
            'last_main_update' => $dbProblem->last_main_update,
            'is_translated' => $dbProblem->is_translated,
            'hits' => $dbProblem->hits
        );
        
        return $result;
    }
    
    public function increaseHits($problemId)
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare('UPDATE translations SET hits=hits+1 WHERE problem_id=?');
            $statement->bindParam(1, $problemId, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProblemContent::increaseHits with problem id $problemId failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
    }
    
    public function getTags($problemId)
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT m.tag_id, t.tag_name ' .
                'FROM tag_mappings m ' .
                'JOIN tags t ON m.tag_id = t.tag_id ' .
                'WHERE m.problem_id = ?'
            );
            $statement->bindParam(1, $problemId, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProblemContent::getTags with problem id $problemId failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array('id' => $row->tag_id, 'name' => $row->tag_name);
        }
        
        return $result;
    }
    
    public function areNeighboursTranslated($problemId)
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT problem_id, is_translated ' .
                'FROM translations ' .
                'WHERE problem_id IN (?, ?)'
            );
            
            $previousId = $problemId - 1;
            $nextId = $problemId + 1;
            
            $statement->bindParam(1, $previousId, PDO::PARAM_INT);
            $statement->bindParam(2, $nextId, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProblemContent::areNeighboursTranslated with problem id $problemId failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            
            $locator = ($row->problem_id == $problemId - 1) ? 'prev' : 'next';
            
            $isTranslated = ($row->is_translated == 1);
            
            $result[$locator] = $isTranslated;
        }
        
        return $result;
    }
}
