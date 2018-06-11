<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questions extends CI_Controller {

	function __construct() {
		parent::__construct();
		if ( ! $this->session->userdata('userSessId') ) {
      $this->session->set_flashdata('msg', 'Please login to continue!');
			redirect(base_url().'users/login');
		} else {
      $this->load->model('Questions_model');
			$this->load->model('Users_model');
		}
	}

	function index() {
		$this->home();
  }
    
  function home() {

    $this->template->set('title', 'Home');
	   $this->template->load('template', 'home');
  }

  function all() {  		
		$this->load->library('pagination');

		$config['full_tag_open'] 		= 	"<ul class='pagination pull-right'>";
		$config['full_tag_close'] 	=		"</ul>";
		$config['num_tag_open'] 		= 	'<li>';
		$config['num_tag_close']		= 	'</li>';
		$config['cur_tag_open']			= 	"<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close']		= 	"<span class='sr-only'></span></a></li>";
		$config['next_tag_open']		= 	"<li>";
		$config['next_tagl_close']	= 	"</li>";
		$config['prev_tag_open'] 		= 	"<li>";
		$config['prev_tagl_close'] 	= 	"</li>";
		$config['first_tag_open'] 	= 	"<li>";
		$config['first_tagl_close'] = 	"</li>";
		$config['last_tag_open'] 		= 	"<li>";
		$config['last_tagl_close'] 	= 	"</li>";
		$config["base_url"] = base_url() . "Questions/all";
		$config["total_rows"] = $this->Questions_model->countAllTopics()->num_rows();
		$config["per_page"] = 15;
		$config['uri_segment'] = 3;

		$limit = $config['per_page'];

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$data["links"] = $this->pagination->create_links();

    if ($this->session->userdata('userPositionSessId') == 1 ) {
      $data['allTopics'] = $this->Questions_model->getAllTopicsById($page, $limit, $this->session->userdata('userIDSess') )->result();
    } else {
      $data['allTopics'] = $this->Questions_model->getAllTopics($page, $limit)->result();
    }

	  $this->template->set('title', 'Home');
		$this->template->load('template', 'questions', $data);
  }

  function autocomplete_search() {
  	$term = $this->input->post('term');
		$results = $this->Questions_model->autocompleteSearch($term);
		$json_array = array();
		foreach($results as $rows) {
			array_push($json_array, $rows->title);
		}
		echo json_encode($json_array);
  }


  function ifExist() {
  	$term = $this->input->post('term');
		$results = $this->Questions_model->ifExisting($term);
		echo $results->num_rows();
  }

  function ifQuestionExist() {
  	$data = $this->input->post();
		$results = $this->Questions_model->ifQuestionExist($data);
		echo $results->num_rows();
  }

  function ifChoiceExist() {
  	$data = $this->input->post();
		$results = $this->Questions_model->ifChoiceExist($data);
		echo $results->num_rows();
  }

  function manage($id) {
    
    $results['examiners'] = $this->Users_model->getAllExaminersById($id);

  	$results['questions'] = $this->Questions_model->getQuestionsById($id);
  	$results['topic'] = $this->Questions_model->getTopicById($id);

  	$this->template->set('title', 'Mangage Questionnaires');
		$this->template->load('template', 'manage', $results);
  }

  function take() {
    $term = $this->input->post('examiner');
    $results = $this->Users_model->getAllExaminers($term);
    $json_array = array();
    foreach($results as $rows) {
      array_push($json_array, "#".$rows->id.' - '.$rows->name);
    }
    echo json_encode($json_array);
  }

  function redirect() {
		$term = str_replace('+', 'plus', $this->input->post('term') );
		redirect(base_url().'questions/results/'.str_replace(' ', '-', $term)  );
  }

  function results() {
  	$term = $this->uri->segment(3);
  	$this->load->library('pagination');
		$config['full_tag_open'] 		= 	"<ul class='pagination pull-right'>";
		$config['full_tag_close'] 	=		"</ul>";
		$config['num_tag_open'] 		= 	'<li>';
		$config['num_tag_close']		= 	'</li>';
		$config['cur_tag_open']			= 	"<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close']		= 	"<span class='sr-only'></span></a></li>";
		$config['next_tag_open']		= 	"<li>";
		$config['next_tagl_close']	= 	"</li>";
		$config['prev_tag_open'] 		= 	"<li>";
		$config['prev_tagl_close'] 	= 	"</li>";
		$config['first_tag_open'] 	= 	"<li>";
		$config['first_tagl_close'] = 	"</li>";
		$config['last_tag_open'] 		= 	"<li>";
		$config['last_tagl_close'] 	= 	"</li>";
		$config["base_url"] = base_url() . "Questions/results/".$term."/";
		$config["total_rows"] = $this->Questions_model->countSearchTopics($term)->num_rows();
		$config["per_page"] = 15;
		$config['uri_segment'] = 4;
		$limit = $config['per_page'];

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$data["links"] = $this->pagination->create_links();
		$data['allTopics'] = $this->Questions_model->searchTopics($term, $page, $limit)->result();

		$this->template->set('title', 'Home');
		$this->template->load('template', 'questions', $data);
  }

  function add() {
  	$data = $this->input->post();
  	if ($this->Questions_model->add($data) ) {
  		$this->session->set_flashdata('questionAdded', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>1 New question added</div>');
  		redirect(base_url().'/questions/all' );
  	}
  }

  function edit($id, $no) {
  	$data['questions'] = $this->Questions_model->editQuestionInfoById($id, $no);
  	$this->template->set('title', 'Home');
		$this->template->load('template', 'edit', $data);
  }

  function edit_question() {
  	$data = $this->input->post();	 
  	$newArray = array();
  	if(isset($data['hiddenChoice']) == "") {
  		$index = "A";
  	} else {
    	$index = $data['hiddenChoice'];
    }
  	$id =  $data['hiddenID'];
  	$no =  $data['hiddenQuestionID'];
  	if(isset($data['choiceText']) ) {
    	for($x = 0; $x < count($data['choiceText']); $x++ ) {
    		$setData = [
    			'choice_choice'	=>	$index,
    			'choice_text'		=>	$data['choiceText'][$x]
    		];
    		$where = [
    			'question_no'		=>	$no,
    			'choice_id' 		=> 	$id,
    			'choice_choice' => 	$index
    		];
    		$this->Questions_model->updateChoiceTxtInfo($setData, $where);
    		$index++;
    	}
  	}
  	if($data['hiddenAnswer']) {
    	if($data['choice']) {
	    	$this->Questions_model->updateAnswerInfo($data);
	    }
		} else {
			if($data['choice']) {
    		$this->Questions_model->insertAnswerInfo($data);
    	}
    }
  	if ($this->Questions_model->updateQuestionInfo($data) ) {
  		$this->session->set_flashdata('questionUpdated', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>1 question updated</div>');
  		redirect($_SERVER['HTTP_REFERER']);
  	}
  }

  function add_question() {
  	$data = $this->input->post();
  	if ($this->Questions_model->add_question($data) ) {
  		$this->session->set_flashdata('questionAdded', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>1 New question added</div>');
  		redirect($_SERVER['HTTP_REFERER']);
  	}
  }

  function add_choice() {
  	$data = $this->input->post();
  	if ($this->Questions_model->add_choice($data) ) {
  		$this->session->set_flashdata('choiceAdded', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>1 New choice added</div>');
  		redirect($_SERVER['HTTP_REFERER']);
  	}
  }

  function status() {
		$id = $this->uri->segment(3);
		$status = $this->uri->segment(4);
		if ($status == 1) {
			$newStatus = 0;
		} else {
			$newStatus = 1;
		}
		if ($this->Questions_model->changeAtatus($id, $newStatus) ) {
			$this->session->set_flashdata('questionStatusUpdated', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Status Updated!</div>');
  		redirect($_SERVER['HTTP_REFERER']);
		}
  }

  function add_examiner() {
    $data = $this->input->post();

    $examiners = "";
    $oldExaminers = "";

    if(isset($data['examiner']) ) {
      for($x = 0; $x < count($data['examiner']); $x++) {
        $examiners[] = $data['examiner'][$x];
      }
    }


    if(isset($data['oldExaminers']) ) {
      for($x = 0; $x < count($data['oldExaminers']); $x++) {
        $old[]  = $data['oldExaminers'][$x];
      }
      for($x = 0; $x < count($data['hiddenOldExaminers']); $x++) {
        $new[] = $data['hiddenOldExaminers'][$x];
      }

      if($diff = array_diff($new, $old) ) {
        $this->Questions_model->updateExaminersList($diff);
      }

    } else {

      $this->Questions_model->deleteAExaminersByTopic($data);
    }
     $this->Questions_model->insertExaminers($data, $examiners);
     $this->session->set_flashdata('updateExaminersByTopic', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Topic Examiners updated!</div>');
    redirect($_SERVER['HTTP_REFERER']);
  }

  function logstart($id) {
    $this->session->set_userdata('start', '1');
    $this->session->set_userdata('topicid', $id);
    date_default_timezone_set('Asia/Manila');
    $this->session->set_userdata('startDate', date("m d Y G:i:s") );

  

    if (!$this->session->userdata('start') ) {
      $this->Questions_model->updateExamDateTaken($id, date("M d Y G:i:s"), $this->session->userdata('userIDSess'));
    }
    redirect(base_url().'questions/instructions/'.$id); 
  }

  function updateRemainingTime() {
     $this->Questions_model->updateRemainingTime($this->input->post('topicId'), $this->input->post('duration'), $this->session->userdata('userIDSess'));
  }


  function takenow($id) {
    if($this->session->userdata('topicid') == $id) {

      $this->load->library('pagination');
      $config['full_tag_open']    =   "<ul class='pagination'>";
      $config['full_tag_close']   =   "</ul>";
      $config['num_tag_open']     =   '<li class="hidden">';
      $config['num_tag_close']    =   '</li>';
      $config['cur_tag_open']     =   "<li class='disabled'><li class='active hidden'><a href='#'>";
      $config['cur_tag_close']    =   "<span class='sr-only'></span></a></li>";
      $config['next_tag_open']    =   "<li>";
      $config['next_tagl_close']  =   "</li>";
      $config['prev_tag_open']    =   "<li class='hidden'>";
      $config['prev_tagl_close']  =   "</li>";
      $config['first_tag_open']   =   "<li class='hidden'>";
      $config['first_tagl_close'] =   "</li>";
      $config['last_tag_open']    =   "<li class='hidden'>";
      $config['last_tagl_close']  =   "</li>";
      //For NEXT PAGE Setup
      $config['next_link'] = 'Next';
      $config['next_tag_open'] = '<li>';
      $config['next_tag_close'] = '</li>';

      $config["base_url"] = base_url() . "Questions/takenow/".$id."/";
      $config["total_rows"] = $this->Questions_model->countQuestionsByIdByExaminer($id);
      $config["per_page"] = 1;
      $config['uri_segment'] = 4;
      $limit = $config['per_page'];

      $this->pagination->initialize($config);

      $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

      $data["links"] = $this->pagination->create_links();

      $data['topic'] = $this->Questions_model->getTopicById($id);
      $data['getChoices'] = $this->Questions_model->getQuestionsByQuestionID($id);
      $data['questions'] = $this->Questions_model->getQuestionsByIdByExaminer($id, $limit, $page);


      $this->template->set('title', 'Take Exam');
      $this->template->load('template', 'takenow', $data);
    } else {
      $this->session->set_flashdata('invalidId', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid Exam!</div>');
      redirect(base_url().'questions/all'); 
    }
  }

  function instructions($id) {

    if ($this->session->userdata('start') ) {
      redirect(base_url().'questions/takenow/'.$id); 
      exit;
    }

    $data['topic'] = $this->Questions_model->getTopicById($id);
    $this->template->set('title', 'Read first before taking exam');
    $this->template->load('template', 'instructions', $data);
  }

  function add_duration() {
    $formData = $this->input->post();
    $data['hiddenID'] = $formData['hiddenID'];
    $data['duration'] = $formData['hours'] . ':' . $formData['minutes'];
    if ($this->Questions_model->add_duration($data) ) {
      $this->session->set_flashdata('durationAdded', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Duration Added!</div>');
      redirect($_SERVER['HTTP_REFERER']);
    }

    

  }
}