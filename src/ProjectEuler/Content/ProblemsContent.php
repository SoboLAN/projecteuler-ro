<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Database;
use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;
use ProjectEuler\Main\Config;

use PDO as PDO;
use PDOException as PDOException;

class ProblemsContent
{
    public function getNrPages()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare('SELECT COUNT(*) AS \'count\' FROM translations');
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getNrPages failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $row = $statement->fetch(PDO::FETCH_OBJ);
        
        $maxProblemsPerPage = Config::getValue('problems.max_per_page');
        
        $count = (int) $row->count;
        
        $count = ($count % $maxProblemsPerPage == 0) ?
                ($count / $maxProblemsPerPage) :
                floor($count / $maxProblemsPerPage) + 1;
        
        return $count;
    }
    
    public function getTagIds()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare('SELECT tag_id FROM tags ORDER BY tag_id ASC');
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getTagsIds failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = $row->tag_id;
        }
        
        return $result;
    }
    
    public function getTags()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT DISTINCT t.tag_id, t.tag_name, ' .
                    '(SELECT COUNT(m.tag_id) ' .
                    'FROM tag_mappings m ' .
                    'WHERE t.tag_id = m.tag_id) AS \'occurrences\' ' .
                'FROM tags t ' .
                'LEFT JOIN tag_mappings m ON t.tag_id = m.tag_id'
            );
            
            $statement->execute();
            
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getTags failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array(
                'id' => $row->tag_id,
                'name' => $row->tag_name,
                'occurrences' => $row->occurrences
            );
        }
        
        return $result;
    }
    
    public function getProblems($page)
    {
        $db = Database::getConnection();
        
        $maxProblemsPerPage = Config::getValue('problems.max_per_page');
        $limit = ($page - 1) * $maxProblemsPerPage;
        
        try {
            $statement = $db->prepare(
                'SELECT problem_id, is_translated, title_romanian, title_english, publish_date, hits ' .
                'FROM translations ' .
                'ORDER BY problem_id ASC ' .
                'LIMIT ?, ?'
            );
            
            $statement->bindParam(1, $limit, PDO::PARAM_INT);
            $statement->bindParam(2, $maxProblemsPerPage, PDO::PARAM_INT);
            $statement->execute();
            
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getProblems with page $page failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array(
                'id' => $row->problem_id,
                'is_translated' => ($row->is_translated == 1),
                'title_romanian' => $row->title_romanian,
                'title_english' => $row->title_english,
                'publish_date' => $row->publish_date,
                'hits' => $row->hits
            );
        }
        
        return $result;
    }
    
    public function getProblemsCount()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT COUNT(*) AS nr_problems ' .
                'FROM translations ' .
                'ORDER BY problem_id ASC'
            );
            
            $statement->execute();
            
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getProblemsCount failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $row = $statement->fetch(PDO::FETCH_OBJ);
        
        return $row->nr_problems;
    }
    
    public function getFilteredProblems($tags, $page)
    {
        $db = Database::getConnection();
        
        $maxProblemsPerPage = Config::getValue('problems.max_per_page');
        $limit = ($page - 1) * $maxProblemsPerPage;
        
        try {
            $tagsAsString = implode(',', $tags);
            
            $statement = $db->prepare(
                'SELECT DISTINCT t.problem_id, t.is_translated, t.title_romanian, t.title_english, t.publish_date, t.hits ' .
                'FROM translations t ' .
                'JOIN tag_mappings m ON t.problem_id=m.problem_id ' .
                "WHERE m.tag_id IN ($tagsAsString) " .
                'ORDER BY problem_id ASC ' .
                'LIMIT ?, ?'
            );
            
            $statement->bindParam(1, $limit, PDO::PARAM_INT);
            $statement->bindParam(2, $maxProblemsPerPage, PDO::PARAM_INT);
            $statement->execute();
            
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getFilteredProblems with tags " . implode(', ', $tags) . " failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $result = array();
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $result[] = array(
                'id' => $row->problem_id,
                'is_translated' => ($row->is_translated == 1),
                'title_romanian' => $row->title_romanian,
                'title_english' => $row->title_english,
                'publish_date' => $row->publish_date,
                'hits' => $row->hits
            );
        }
        
        return $result;
    }
    
    public function getFilteredProblemsCount($tags)
    {
        $db = Database::getConnection();
        
        try {
            $tagsAsString = implode(',', $tags);
            
            $statement = $db->prepare(
                'SELECT DISTINCT t.problem_id ' .
                'FROM translations t ' .
                'JOIN tag_mappings m ON t.problem_id=m.problem_id ' .
                "WHERE m.tag_id IN ($tagsAsString)"
            );
            
            $statement->execute();
            
        } catch (PDOException $ex) {
            $message = "calling ProblemsContent::getFilteredProblemsCount with tags " . implode(', ', $tags) . " failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        return $statement->rowCount();
    }
}
