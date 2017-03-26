<?php

class miReestrCountDictionary {
	public $col1;
	public $all_count;
	public $match_value;
	public $match_count;
	public $percen_true;
	public $percent_false;
}

class model_wordByMI extends Model
{
	public function addRecordsInTable() {
		try {
			$rootParam = "CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END";
			$dth = $this->connectPDO();
			$sqlDel = 'DELETE FROM ' . MIReestr;
			$dth->exec($sqlDel);
			$sqlInsert = 'INSERT INTO ' . MIReestr . ' ( col1, word_id) select rd.col1, m.id FROM ' . TableReestrDistinct . ' rd INNER JOIN ' . Morpheme . ' m 
			ON UPPER (rd.col5) REGEXP('.$rootParam.') WHERE rd.col15>0 GROUP BY rd.col1, m.id;';
//		die ($sqlInsert);
			$dth->exec($sqlInsert);
			$dth = NULL;
			$this->updateRecordsInTable();
			return 0;
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	public function get_data()
	{
		$dth= $this->connectPDO();
		$rootParam="CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END";
//		$rootParam="CASE WHEN GROUP_CONCAT(DISTINCT m.customRoot SEPARATOR ', ') IS NULL
//		THEN GROUP_CONCAT(DISTINCT m.root SEPARATOR ', ') ELSE m.customRoot END";
		$sql="SELECT ss1.*, GROUP_CONCAT(DISTINCT mrs.col2_section SEPARATOR ', ') selectorMI,
 		GROUP_CONCAT(DISTINCT ss1.root SEPARATOR ', ') roots FROM 
		(SELECT mr.col1, COUNT(DISTINCT mrm.id) cnt, 
		GROUP_CONCAT(DISTINCT m.word SEPARATOR ', ') wordConcat, 
		GROUP_CONCAT(DISTINCT mrm.id SEPARATOR ', ') selectorWord, rd.col15, $rootParam root
		,mr.match_value, mr.match_count, mr.percen_true, mr.percent_false
		-- ,ws.word words, ws.id, s.s_id, s.w_id, GROUP_CONCAT(DISTINCT s1.s_id SEPARATOR ', ') sid_s, s1.w_id sid_w 
		-- ,GROUP_CONCAT(DISTINCT wf.word SEPARATOR ', ') wordConcat -- wordF
		,GROUP_CONCAT(DISTINCT s1.s_id SEPARATOR ', ') sid_s 
		,GROUP_CONCAT(DISTINCT wf.word SEPARATOR ', ') wordF
		FROM miReestrCountDictionary mr 
		INNER JOIN morpheme m ON mr.word_id=m.id 
		INNER JOIN reestr_distinct rd ON mr.col1=rd.col1 
		LEFT JOIN mi_reestr_main mrm ON UPPER(mrm.miName) REGEXP($rootParam)
		LEFT JOIN morpheme ws ON UPPER(ws.word) REGEXP(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END)
      	LEFT JOIN synonyms s ON (s.s_id=ws.id)
      	LEFT JOIN synonyms s1 ON (s1.w_id=ws.id OR s1.w_id=s.w_id)
      	LEFT JOIN morpheme wf ON (wf.id=s1.s_id OR wf.id=s.s_id OR wf.id=s1.w_id OR wf.id=s.s_id)
		WHERE (m.isExclude IS NULL OR m.isExclude =0) 
		GROUP BY mr.col1, rd.col15, root ORDER BY cnt DESC) ss1 
		LEFT JOIN mi_reestr_section mrs ON ss1.col15=mrs.col1
		GROUP BY ss1.col1,ss1.cnt,ss1.wordConcat, ss1.col15;"; // LIMIT 10
//		die ($sql);
		$result=$this->getAllRecinArray($sql);
		$dth=NULL;
		return $result;
	}

	/**
	SELECT fwords.*, COUNT(*) countWord, m.word,UPPER (rd.col5) REGEXP(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END) isReestrMI,
	GROUP_CONCAT(DISTINCT mrm.id SEPARATOR ', ') selectorWord, rd.col15,
	mrs.col2_section
	FROM
	(SELECT mr.col1, CASE WHEN s.s_id IS NULL THEN mr.word_id ELSE s.s_id END words_id FROM miReestrCountDictionary mr
	LEFT JOIN synonyms s ON (mr.word_id = s.s_id OR mr.word_id = s.w_id)
	-- WHERE mr.word_id IN (10,39,38)
	GROUP BY mr.col1, words_id  -- убираем совсем повторения
	) fwords
	INNER JOIN morpheme m
	ON fwords.words_id=m.id
	INNER JOIN reestr_distinct rd
	ON rd.col1=fwords.col1
	LEFT JOIN mi_reestr_main mrm
	ON UPPER(mrm.miName) REGEXP(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END)
	LEFT JOIN mi_reestr_section mrs
	ON rd.col15=mrs.col1

	GROUP BY fwords.col1, fwords.words_id
	 */

	/*
	 * ver 2
	 *
	 * SELECT DISTINCT fwords.*
  ,(LENGTH(rd.col5) - LENGTH(REPLACE(UPPER(rd.col5), CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END, ''))) / LENGTH(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END) countWord
  , m.word , UPPER (rd.col5) REGEXP(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END) isReestrMI
    , mrm.id wordSection
    , mrs.col2_section miSection
  FROM
  (SELECT mr.col1, CASE WHEN s.s_id IS NULL THEN mr.word_id ELSE s.s_id END words_id FROM miReestrCountDictionary mr
  LEFT JOIN synonyms s ON (mr.word_id = s.s_id OR mr.word_id = s.w_id)
  GROUP BY mr.col1, words_id  -- убираем совсем повторения
  ) fwords
  INNER JOIN morpheme m
  ON fwords.words_id=m.id
  INNER JOIN reestr_distinct rd
  ON rd.col1=fwords.col1
  LEFT JOIN mi_reestr_section mrs   -- раздел для МИ
  ON rd.col15=mrs.col1
  LEFT JOIN mi_reestr_main mrm      -- раздел для слова
  ON UPPER(mrm.miName) REGEXP(CASE WHEN m.customRoot IS NULL THEN m.root ELSE m.customRoot END)
  WHERE m.isExclude IS NULL OR m.isExclude=''
--  ) WordsMi


--  GROUP BY fwords.col1, fwords.words_id
--  HAVING countWord>1
	 *
	 * ***/
	
	public function updateRecordsInTable() {
		$results=$this->get_data();
		$dth=$this->connectPDO();
		foreach ($results as $result) {
			$mi= new miReestrCountDictionary();
			$mi->col1=($result[col1]);
			/*
			 * еще добавить:
			 * если есть синонимы, то пересчитать кол-во, согласно синонимов
			 * обновить слова?? или пометить, что есть синонимы
			 * обновить найденные разделы присвоенные МИ
			 * и далее по старой схеме
			 * */
			$mi->all_count=($result[cnt]);
			$arr1=explode(", ",$result[selectorWord]);
			$arr2=explode(", ",$result[selectorMI]);
//			$mi->all_count=count($arr2);
			$result = array_intersect($arr1, $arr2);
			$mi->match_count=count($result);
			$mi->match_value=implode($result,", ");
			$mi->percen_true=$mi->match_count*100/$mi->all_count;
			$mi->percent_false=100-$mi->percen_true;
			$update="UPDATE miReestrCountDictionary SET match_value = '$mi->match_value' ,match_count = $mi->match_count 
			,percen_true = $mi->percen_true,percent_false = $mi->percent_false WHERE col1 = '$mi->col1'";
			$dth->exec($update);
		}
		
	}
}