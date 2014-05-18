<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Database;
use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;

use PDO as PDO;
use PDOException as PDOException;

class StatisticsContent
{
    public function getStatistics()
    {
        $db = Database::getConnection();
        
        try {
            $statement = $db->prepare(
                'SELECT title_english, title_romanian, text_english, text_romanian, is_translated ' .
                'FROM translations'
            );
            $statement->execute();
        } catch (PDOException $ex) {
            $message = "calling StatisticsContent::getStatistics failed";
            Logger::log("$message: " . $ex->getMessage());
            throw new PEException($message, PEException::ERROR);
        }
        
        $rowCount = $statement->rowCount();
        $romanianCount = 0;
        $totalTitleEN = $totalTitleRO = $totalTextEN = $totalTextRO = 0;
        $minTitleEN = $minTitleRO = $minTextEN = $minTextRO = 1000 * 1000;
        $maxTitleEN = $maxTitleRO = $maxTextEN = $maxTextRO = 0;
        
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            
            $totalTitleEN += strlen($row->title_english);
            $totalTitleRO += strlen($row->title_romanian);
            $totalTextEN += strlen($row->text_english);
            $totalTextRO += strlen($row->text_romanian);

            $isTranslated = ($row->is_translated == '1');

            if (strlen($row->title_english) < $minTitleEN) {
                $minTitleEN = strlen($row->title_english);
            }

            if (strlen($row->title_english) > $maxTitleEN) {
                $maxTitleEN = strlen ($row->title_english);
            }

            if ($isTranslated) {
                if (strlen($row->title_romanian) < $minTitleRO) {
                    $minTitleRO = strlen($row->title_romanian);
                }

                if (strlen($row->title_romanian) > $maxTitleRO) {
                    $maxTitleRO = strlen($row->title_romanian);
                }

                if (strlen ($row->text_romanian) < $minTextRO) {
                    $minTextRO = strlen($row->text_romanian);
                }

                if (strlen ($row->text_romanian) > $maxTextRO) {
                    $maxTextRO = strlen($row->text_romanian);
                }

                $romanianCount++;
            }

            if (strlen($row->text_english) < $minTextEN) {
                $minTextEN = strlen($row->text_english);
            }

            if (strlen($row->text_english) > $maxTextEN) {
                $maxTextEN = strlen($row->text_english);
            }
        }
        
        return array(
            'nr_problems' => $rowCount,
            'nr_problems_translated' => $romanianCount,
            'total_text_en' => $totalTextEN,
            'total_text_ro' => $totalTextRO,
            'max_text_en' => $maxTextEN,
            'max_text_ro' => $maxTextRO,
            'min_text_en' => $minTextEN,
            'min_text_ro' => $minTextRO,
            'total_title_en' => $totalTitleEN,
            'total_title_ro' => $totalTitleRO,
            'max_title_en' => $maxTitleEN,
            'max_title_ro' => $maxTitleRO,
            'min_title_en' => $minTitleEN,
            'min_title_ro' => $minTitleRO,
        );
    }
}
