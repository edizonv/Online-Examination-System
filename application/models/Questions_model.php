<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_model extends CI_Model {

  public $topicsTable = "oes_topics";
  public $questionsTable = "oes_questions";
  public $choicesTable = "oes_choices";
  public $answersTable = "oes_answers";
  public $examinersTable = "oes_examiners";
  public $usersTable = "oes_users";

  function autocompleteSearch($term) {
    $autocompleteSearch = $this->db
      ->select('topic_id AS id, topic_title AS title, topic_date_added AS dateAdded, topic_status AS status')
      ->from($this->topicsTable)
      ->like('topic_title', $term)
      ->limit(10)
      ->get()
      ->result();
    return $autocompleteSearch;
  }

  function ifExisting($term) {
    $ifExisting = $this->db
      ->select('topic_title AS title')
      ->from($this->topicsTable)
      ->where('topic_title', $term)
      ->get();
    return $ifExisting;
  }

  function ifQuestionExist($data) {
    $ifQuestionExist = $this->db
      ->select('question_question AS question')
      ->from($this->questionsTable)
      ->where('question_question', $data['term'])
      ->where('question_id', $data['hiddenID'])
      ->get();
    return $ifQuestionExist;
  }

  function ifChoiceExist($data = []) {
    $ifChoiceExist = $this->db
      ->select('choice_text AS choice')
      ->from($this->choicesTable)
      ->where('choice_text', $data['choiceTerm'])
      ->where('choice_id', $data['hiddenID'])
      ->where('question_no', $data['hiddenQuestionID'])
      ->get();
    return $ifChoiceExist;
  }

  function searchTopics($term, $page, $limit) {
    $searchTopics = $this->db
      ->limit($limit, $page)
      ->select('topic_id AS id, topic_title AS title, topic_date_added AS dateAdded, topic_status AS status')
      ->from($this->topicsTable)
      ->like('topic_slug', $term)
      ->get();
    return $searchTopics;
  }

  function countSearchTopics($term) {
    $searchTopics = $this->db
      ->like('topic_slug', $term)
      ->get($this->topicsTable);
    return $searchTopics;
  }

  function getAllTopics($page, $limit) {
  	$getAllTopics = $this->db
  		->limit($limit, $page)
  		->select('topic_id AS id, topic_title AS title, topic_date_added AS dateAdded, topic_status AS status')
  		->from($this->topicsTable)
    	->get();
    return $getAllTopics;
  }

  function getAllTopicsById($page, $limit, $id) {
    $getAllTopics = $this->db
      ->limit($limit, $page)
      ->select('t.topic_id AS id, t.topic_title AS title, t.topic_date_added AS dateAdded, t.topic_status AS status, e.examiner_topics AS topics, e.examiner_status AS estatus')
      ->from($this->topicsTable . ' AS t')
      ->join($this->examinersTable. ' AS e', 'e.examiner_topics = t.topic_id')
      ->join($this->usersTable. ' AS u', 'u.user_id = e.examiner_id')
      ->where('u.user_id', $id)
      ->get();
    return $getAllTopics;
  }

  function countAllTopics() {
  	$countAllTopics = $this->db->get($this->topicsTable);
    return $countAllTopics;
  }

  function getQuestionsById($id) {
    $getQuestionsById = $this->db
      ->query("SELECT DISTINCT t.topic_id AS id,
        t.topic_title AS title,
        t.topic_date_added AS dateAdded,
        t.topic_status AS status,
        q.question_no AS no,
        q.question_question AS questions,
        q.question_status AS qstat,
        c.choice_choice AS choices,
        c.choice_type AS type,
        c.choice_text AS choicesText,
        a.answer_answer AS answer
        FROM $this->topicsTable AS t
        LEFT JOIN $this->questionsTable AS q ON t.topic_id = q.question_id
        LEFT JOIN $this->choicesTable AS c ON q.question_no = c.question_no
        LEFT JOIN $this->answersTable AS a ON c.question_no = a.question_no
        WHERE t.topic_id = $id")
      ->result();

    return $getQuestionsById;
  }

  function getQuestionsByIdByExaminer($id, $limit, $page) {
    $getQuestionsByIdByExaminer = $this->db
      ->query("SELECT DISTINCT t.topic_id AS id, t.topic_title AS title,
        t.topic_date_added AS dateAdded,
        t.topic_status AS status,
        q.question_no AS no,
        q.question_question AS questions,
        q.question_status AS qstat,
        c.choice_choice AS choices,
        c.choice_type AS type,
        e.examiner_topic_date_taken AS dateTaken,
        e.examiner_remaining_time AS remainingTime,
        c.choice_text AS choicesText,
        a.answer_answer AS answer
        FROM oes_topics AS t
        LEFT JOIN oes_questions AS q ON t.topic_id = q.question_id
        LEFT JOIN oes_choices AS c ON q.question_no = c.question_no
        LEFT JOIN oes_answers AS a ON c.question_no = a.question_no
        LEFT JOIN oes_examiners AS e ON t.topic_id = e.examiner_id
        WHERE t.topic_id = 1 && q.question_status = 1 && c.choice_id = 1 GROUP BY q.question_question ORDER BY q.question_question DESC LIMIT $page, $limit")
      ->result();

    return $getQuestionsByIdByExaminer;
  }

  function countQuestionsByIdByExaminer($id) {
    $countQuestionsByIdByExaminer = $this->db
      ->query("SELECT DISTINCT t.topic_id AS id, t.topic_title AS title,
        t.topic_date_added AS dateAdded,
        t.topic_status AS status,
        q.question_no AS no,
        q.question_question AS questions,
        q.question_status AS qstat,
        c.choice_choice AS choices,
        c.choice_type AS type,
        e.examiner_topic_date_taken AS dateTaken,
        e.examiner_remaining_time AS remainingTime,
        c.choice_text AS choicesText,
        a.answer_answer AS answer
        FROM oes_topics AS t
        LEFT JOIN oes_questions AS q ON t.topic_id = q.question_id
        LEFT JOIN oes_choices AS c ON q.question_no = c.question_no
        LEFT JOIN oes_answers AS a ON c.question_no = a.question_no
        LEFT JOIN oes_examiners AS e ON t.topic_id = e.examiner_id
        WHERE t.topic_id = 1 && q.question_status = 1 && c.choice_id = 1 GROUP BY q.question_question ORDER BY q.question_question DESC")
      ->num_rows();

    return $countQuestionsByIdByExaminer;
  }


  function getQuestionsByQuestionID($id) {
    $getQuestionsByQuestionID = $this->db
      ->query("SELECT question_no AS no, choice_choice AS choice, choice_text AS choices FROM oes_choices")
      ->result();

    return $getQuestionsByQuestionID;
  }

  function getTopicById($id) {
    $getTopicInfoById = $this->db
      ->select('topic_title AS title, topic_status AS status, topic_duration AS duration')
      ->from($this->topicsTable)
      ->where('topic_id', $id)
      ->get()
      ->row();
    return $getTopicInfoById;
  }

  function getTopicInfoById($id) {
    $getTopicInfoById = $this->db
      ->select('question_question AS questions')
      ->from($this->questionsTable)
      ->where('question_id', $id)
      ->get();
    return $getTopicInfoById;
  }


  function countQuestionsById($id) {
    $countQuestionsById = $this->db->get($this->topicsTable);
    return $countQuestionsById;
  }

  function add($data = array() ) {
    $setData = [
      'topic_title' => $data['term'],
      'topic_slug'  => str_replace(' ', '-', str_replace('+', 'plus', $data['term']) )
    ];
    $add = $this->db->insert($this->topicsTable, $setData);
    return $add;
  }

  function editQuestionInfoById($id, $no) {
    $editQuestionInfoById = $this->db
      ->query("SELECT DISTINCT t.topic_id AS id,
        t.topic_title AS title,
        t.topic_date_added AS dateAdded,
        t.topic_status AS status,
        q.question_no AS no,
        q.question_question AS questions,
        q.question_status AS questionStatus,
        c.choice_choice AS choices,
        c.choice_text AS choicesText,
        c.choice_type AS choiceType,
        a.answer_answer AS answer
        FROM $this->topicsTable AS t
        LEFT JOIN $this->questionsTable AS q ON t.topic_id = q.question_id
        LEFT JOIN $this->choicesTable AS c ON q.question_no = c.question_no
        LEFT JOIN $this->answersTable AS a ON c.question_no = a.question_no
        WHERE t.topic_id = $id && q.question_no = $no")
      ->result();

    return $editQuestionInfoById;
  }

  function add_question($data = [] ) {
    $setData = [
      'question_question' => $data['term'],
      'question_id'  => $data['hiddenID'],
      'question_status'  => 1
    ];
    $add_question = $this->db->insert($this->questionsTable, $setData);
    return $add_question;
  }

  function updateQuestionInfo($data = []) {
    $setData = [
      'question_question' => $data['question'],
      'question_status' => $data['questionStatus']
    ];
    $updateQuestionInfo = $this->db
      ->where('question_id', $data['hiddenID'])
      ->where('question_no', $data['hiddenQuestionID'])
      ->update($this->questionsTable, $setData);
    return $updateQuestionInfo;
  }

  function updateAnswerInfo($data = []) {

    $setData = [
      'answer_answer' =>  $data['choice']
    ];
     $updateAnswerInfo = $this->db
      ->where('answer_id', $data['hiddenID'])
      ->where('question_no', $data['hiddenQuestionID'])
       ->update($this->answersTable, $setData);
     return $updateAnswerInfo;
  }

  function insertAnswerInfo($data = []) {

    $setData = [
      'answer_id' =>  $data['hiddenID'],
      'question_no' => $data['hiddenQuestionID'],
      'answer_answer' =>  $data['choice']
    ];
     $insertAnswerInfo = $this->db
       ->insert($this->answersTable, $setData);
     return $insertAnswerInfo;
  }

  function updateChoiceTxtInfo($setData = [], $where = []) {

    $updateChoiceTxtInfo = $this->db
      ->where($where)
      ->update($this->choicesTable, $setData);
    return $updateChoiceTxtInfo;
  }

  function add_choice($data = []) {
    if($data['choiceType'] == 3) {
       $data['hiddenChoice'] = "";
       $data['choiceType'] = 3;
    }
    $insertChoice = [
      'choice_id' =>  $data['hiddenID'],
      'question_no' =>  $data['hiddenQuestionID'],
      'choice_choice' =>  $data['hiddenChoice'],
      'choice_text' =>  $data['choiceTerm'],
      'choice_type' =>  $data['choiceType']
    ];
    $add_choice = $this->db->insert($this->choicesTable, $insertChoice);
    return $add_choice;
  }

  function changeAtatus($id, $newStatus) {
    $data = [
        'topic_status' => $newStatus
    ];
    $disableTopic = $this->db
      ->where('topic_id', $id)
      ->update($this->topicsTable, $data);
    return $disableTopic;
  }

  function insertExaminers($data = [], $examiners = []) {
    $set = "";
    if(isset($examiners) && $examiners != "") {
      for($x =0; $x < count($examiners); $x++ ) {
         $set[] = [
          'examiner_id'     =>  $examiners[$x],
          'examiner_topics' =>  $data['hiddenID']
        ];
      }
      $insertExaminers = $this->db->insert_batch($this->examinersTable, $set);
    }
  }

  function updateExaminersList($id) {
    $where = '';
    for($x = 0; $x < count($id); $x++) {
      $where = [
        'examiner_id' => $id[$x]
      ];
    }
    $updateExaminersList = $this->db->delete($this->examinersTable, $where);
  }

  function deleteAExaminersByTopic($data = []) {

      $where = [
        'examiner_topics' =>  $data['hiddenID']
      ];
   
    $updateExaminersList = $this->db->delete($this->examinersTable, $where);
  }

  function add_duration($data = []) {
    $set = [
      'topic_duration'  =>  $data['duration']
    ];
    $where = [
      'topic_id'  =>  $data['hiddenID']
    ];
    $add_duration = $this->db->where($where)->update($this->topicsTable, $set);

    return $add_duration;
  }

  function updateExamDateTaken($topicId, $date, $userId) {
    $where =[
      'examiner_topics' =>  $topicId,
      'examiner_id' =>  $userId
    ];

    $set =[
      'examiner_topic_date_taken' =>  $date
    ];
    $updateExamDateTaken = $this->db->where($where)->update($this->examinersTable, $set);

    return $updateExamDateTaken;
  }

  function updateRemainingTime($topicId, $remainingTime, $userId) {
     $where =[
      'examiner_topics' =>  $topicId,
      'examiner_id' =>  $userId
    ];

    $set =[
      'examiner_remaining_time' =>  $remainingTime
    ];
    $updateRemainingTime = $this->db->where($where)->update($this->examinersTable, $set);

    return $updateRemainingTime;
  }

}