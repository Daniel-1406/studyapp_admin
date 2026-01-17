<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        $data = $info=$this->storeinfo->getStoreInfo();
        $this->load->view('login', $data);
    }
    
    public function login() {
        $val = $this->admin->adminlogin();
        if ($val == "wrong") {
            $data = $info=$this->storeinfo->getStoreInfo();
            $data["val"] = "<span style='color:red'>Wrong Username or password!</span>";
            $this->load->view("login", $data);
        } else {
            $this->opendashboard();
        }
    }
    public function opendashboard(){
        if ($this->session->userdata("admin_pass") == "")
        redirect("welcome/");
        $data = $info=$this->storeinfo->getStoreInfo();
        $this->load->view("dashboard",$data);
}

public function openschools() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get schools data for the table
    $db = $this->welcomemodel->getschoolsview();
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["school_count"] = $db["productCount"];
    
    $this->load->view("uploads/schools", $data);
}

// AJAX: Add School
public function addschool_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("sch_name", "School Name", "required|trim|min_length[2]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $school_name = $this->input->post("sch_name");
        $result = $this->welcomemodel->addschool($school_name);
        echo json_encode($result);
    }
}

// AJAX: Get School by ID
public function getschool_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $school = $this->welcomemodel->getschoolbyid($id);
    if ($school) {
        echo json_encode(array(
            'status' => 'success',
            'school' => $school
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'School not found!'
        ));
    }
}

// AJAX: Update School
public function updateschool_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("sch_id", "School ID", "required|numeric");
    $this->form_validation->set_rules("sch_name", "School Name", "required|trim|min_length[2]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $id = $this->input->post("sch_id");
        $school_name = $this->input->post("sch_name");
        $result = $this->welcomemodel->updateschool($id, $school_name);
        echo json_encode($result);
    }
}

// AJAX: Delete School
public function deleteschool_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deleteschool($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid school ID!'
        ));
    }
}


public function addschool() {
    if ($this->session->userdata("admin_pass") == "")
        redirect("welcome/");
    
    $this->form_validation->set_rules("sch_name", "School Name", "required|trim");
    
    if ($this->form_validation->run() == FALSE) {
        // Validation failed, reload the page with validation errors
        $data = $this->storeinfo->getStoreInfo();
        
        // Get schools data for the table
        $db = $this->welcomemodel->getschoolsview();
        $data["dbhead"] = $db["head"];
        $data["dbbody"] = $db["body"];
        
        $this->load->view('uploads/schools', $data);
    } else {
        // Validation passed, add school
        $feedback = $this->welcomemodel->addschool();
        $data = $this->storeinfo->getStoreInfo();
        $data["feedback"] = $feedback;
        
        // Get schools data for the table
        $db = $this->welcomemodel->getschoolsview();
        $data["dbhead"] = $db["head"];
        $data["dbbody"] = $db["body"];
        
        $this->load->view('uploads/schools', $data);
    }
}

public function viewschools() {
    if ($this->session->userdata("admin_pass") == "")
        redirect("welcome/");
    $db = $this->welcomemodel->getschoolsview();
    $data = $info=$this->storeinfo->getStoreInfo();
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $this->load->view("manage", $data);
}
  // ========== DEPARTMENTS CONTROLLER METHODS ==========
    
  public function opendepartments() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get departments data for the table
    $db = $this->welcomemodel->getdepartmentsview();
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["department_count"] = $db["departmentCount"];
    
    // Get schools for dropdown
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    
    $this->load->view("uploads/departments", $data);
}

// AJAX: Add Department
public function adddepartment_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("dept_name", "Department Name", "required|trim|min_length[2]");
    $this->form_validation->set_rules("sch_id", "School", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $dept_name = $this->input->post("dept_name");
        $sch_id = $this->input->post("sch_id");
        $result = $this->welcomemodel->adddepartment($dept_name, $sch_id);
        echo json_encode($result);
    }
}

// AJAX: Get Department by ID
public function getdepartment_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $department = $this->welcomemodel->getdepartmentbyid($id);
    if ($department) {
        echo json_encode(array(
            'status' => 'success',
            'department' => $department
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Department not found!'
        ));
    }
}

// AJAX: Update Department
public function updatedepartment_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("dept_id", "Department ID", "required|numeric");
    $this->form_validation->set_rules("dept_name", "Department Name", "required|trim|min_length[2]");
    $this->form_validation->set_rules("sch_id", "School", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $id = $this->input->post("dept_id");
        $dept_name = $this->input->post("dept_name");
        $sch_id = $this->input->post("sch_id");
        $result = $this->welcomemodel->updatedepartment($id, $dept_name, $sch_id);
        echo json_encode($result);
    }
}

// AJAX: Delete Department
public function deletedepartment_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deletedepartment($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid department ID!'
        ));
    }
}

// AJAX: Get schools for dropdown (for use in modals)
public function getschoolsfordropdown_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $schools = $this->welcomemodel->getschoolsfordropdown();
    echo json_encode(array(
        'status' => 'success',
        'schools' => $schools
    ));
}




 // ========== COURSES CONTROLLER METHODS ==========
    
 public function opencourses() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get courses data for the table
    $db = $this->welcomemodel->getcoursesview();
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["course_count"] = $db["courseCount"];
    
    // Get schools and semesters for dropdowns
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    $data["departments"] = $this->welcomemodel->getdepartmentsfordropdown();
    $data["semesters"] = $this->welcomemodel->getsemesters();
    
    $this->load->view("uploads/courses", $data);
}

// AJAX: Add Course
public function addcourse_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("course_code", "Course Code", "required|trim|min_length[2]|max_length[20]");
    $this->form_validation->set_rules("course_title", "Course Title", "required|trim|min_length[3]|max_length[200]");
    $this->form_validation->set_rules("semester", "Semester", "required|numeric|greater_than[0]|less_than[9]");
    $this->form_validation->set_rules("sch_id", "School", "required|numeric");
    $this->form_validation->set_rules("dept_id", "Department", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        // Check if course code already exists
        $course_code = $this->input->post("course_code");
        if ($this->welcomemodel->checkcoursecode($course_code)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Course code already exists!'
            ));
            return;
        }
        
        $course_data = array(
            'code' => $course_code,
            'title' => $this->input->post("course_title"),
            'semester' => $this->input->post("semester"),
            'sch_id' => $this->input->post("sch_id"),
            'dept_id' => $this->input->post("dept_id")
        );
        
        $result = $this->welcomemodel->addcourse($course_data);
        echo json_encode($result);
    }
}

// AJAX: Get Course by ID
public function getcourse_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $course = $this->welcomemodel->getcoursebyid($id);
    if ($course) {
        echo json_encode(array(
            'status' => 'success',
            'course' => $course
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Course not found!'
        ));
    }
}

// AJAX: Update Course
public function updatecourse_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("course_id", "Course ID", "required|numeric");
    $this->form_validation->set_rules("course_code", "Course Code", "required|trim|min_length[2]|max_length[20]");
    $this->form_validation->set_rules("course_title", "Course Title", "required|trim|min_length[3]|max_length[200]");
    $this->form_validation->set_rules("semester", "Semester", "required|numeric|greater_than[0]|less_than[9]");
    $this->form_validation->set_rules("sch_id", "School", "required|numeric");
    $this->form_validation->set_rules("dept_id", "Department", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $course_id = $this->input->post("course_id");
        $course_code = $this->input->post("course_code");
        
        // Check if course code already exists (excluding current course)
        if ($this->welcomemodel->checkcoursecode($course_code, $course_id)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Course code already exists!'
            ));
            return;
        }
        
        $course_data = array(
            'code' => $course_code,
            'title' => $this->input->post("course_title"),
            'semester' => $this->input->post("semester"),
            'sch_id' => $this->input->post("sch_id"),
            'dept_id' => $this->input->post("dept_id")
        );
        
        $result = $this->welcomemodel->updatecourse($course_id, $course_data);
        echo json_encode($result);
    }
}

// AJAX: Delete Course
public function deletecourse_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deletecourse($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid course ID!'
        ));
    }
}

// AJAX: Get Departments by School
public function getdepartmentsbyschool_ajax($school_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $departments = $this->welcomemodel->getdepartmentsbyschool($school_id);
    echo json_encode(array(
        'status' => 'success',
        'departments' => $departments
    ));
}

// AJAX: Get semesters
public function getsemesters_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $semesters = $this->welcomemodel->getsemesters();
    echo json_encode(array(
        'status' => 'success',
        'semesters' => $semesters
    ));
}

 // ========== TOPICS CONTROLLER METHODS ==========
    
 public function opentopics() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get filter parameters
    $school_id = $this->input->get('school_id');
    $dept_id = $this->input->get('dept_id');
    $course_id = $this->input->get('course_id');
    
    // Get topics data for the table with filters
    $db = $this->welcomemodel->gettopicsview($school_id, $dept_id, $course_id);
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["topic_count"] = $db["topicCount"];
    
    // Get dropdown data
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    $data["departments"] = $this->welcomemodel->getdepartmentsfordropdown();
    $data["courses"] = $this->welcomemodel->getcoursesfordropdown($school_id, $dept_id);
    
    // Store filter values for repopulating form
    $data["filter_school_id"] = $school_id;
    $data["filter_dept_id"] = $dept_id;
    $data["filter_course_id"] = $course_id;
    
    $this->load->view("uploads/topics", $data);
}

// AJAX: Add Topic
public function addtopic_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("topic_name", "Topic Name", "required|trim|min_length[2]|max_length[200]");
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $topic_name = $this->input->post("topic_name");
        $course_id = $this->input->post("course_id");
        
        // Check if topic name already exists for this course
        if ($this->welcomemodel->checktopicname($topic_name, $course_id)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Topic name already exists for this course!'
            ));
            return;
        }
        
        $result = $this->welcomemodel->addtopic($topic_name, $course_id);
        echo json_encode($result);
    }
}

// AJAX: Get Topic by ID
public function gettopic_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $topic = $this->welcomemodel->gettopicdetails($id);
    if ($topic) {
        echo json_encode(array(
            'status' => 'success',
            'topic' => $topic
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Topic not found!'
        ));
    }
}

// AJAX: Update Topic
public function updatetopic_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("topic_id", "Topic ID", "required|numeric");
    $this->form_validation->set_rules("topic_name", "Topic Name", "required|trim|min_length[2]|max_length[200]");
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $topic_id = $this->input->post("topic_id");
        $topic_name = $this->input->post("topic_name");
        $course_id = $this->input->post("course_id");
        
        // Check if topic name already exists for this course (excluding current topic)
        if ($this->welcomemodel->checktopicname($topic_name, $course_id, $topic_id)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Topic name already exists for this course!'
            ));
            return;
        }
        
        $result = $this->welcomemodel->updatetopic($topic_id, $topic_name, $course_id);
        echo json_encode($result);
    }
}

// AJAX: Delete Topic
public function deletetopic_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deletetopic($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid topic ID!'
        ));
    }
}

// AJAX: Get Courses by School and Department
public function getcoursesbyfilters_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $school_id = $this->input->get('school_id');
    $dept_id = $this->input->get('dept_id');
    
    $courses = $this->welcomemodel->getcoursesbyfilters($school_id, $dept_id);
    echo json_encode(array(
        'status' => 'success',
        'courses' => $courses
    ));
}

  // ========== QUESTIONS CONTROLLER METHODS ==========
    
  public function openquestions() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get filter parameters
    $school_id = $this->input->get('school_id');
    $dept_id = $this->input->get('dept_id');
    $course_id = $this->input->get('course_id');
    $topic_id = $this->input->get('topic_id');
    
    // Get questions data for the table with filters
    $db = $this->welcomemodel->getquestionsview($school_id, $dept_id, $course_id, $topic_id);
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["question_count"] = $db["questionCount"];
    
    // Get dropdown data
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    $data["departments"] = $this->welcomemodel->getdepartmentsfordropdown($school_id);
    $data["courses"] = $this->welcomemodel->getcoursesfordropdown($school_id, $dept_id);
    $data["topics"] = $this->welcomemodel->gettopicsbycourse($course_id);
    $data["answer_options"] = $this->welcomemodel->getansweroptions();
    
    // Store filter values for repopulating form
    $data["filter_school_id"] = $school_id;
    $data["filter_dept_id"] = $dept_id;
    $data["filter_course_id"] = $course_id;
    $data["filter_topic_id"] = $topic_id;
    
    $this->load->view("uploads/questions", $data);
}

// AJAX: Add Question
public function addquestion_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("topic_id", "Topic", "required|numeric");
    $this->form_validation->set_rules("qst", "Question", "required|trim|min_length[3]");
    $this->form_validation->set_rules("option_a", "Option A", "required|trim");
    $this->form_validation->set_rules("option_b", "Option B", "required|trim");
    $this->form_validation->set_rules("option_c", "Option C", "required|trim");
    $this->form_validation->set_rules("option_d", "Option D", "required|trim");
    $this->form_validation->set_rules("ans", "Correct Answer", "required|in_list[a,b,c,d]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $question_data = array(
            'crse_id' => $this->input->post("course_id"),
            'topic_id' => $this->input->post("topic_id"),
            'qst' => $this->input->post("qst", false), // false to prevent XSS filtering that breaks HTML
            'option_a' => $this->input->post("option_a", false),
            'option_b' => $this->input->post("option_b", false),
            'option_c' => $this->input->post("option_c", false),
            'option_d' => $this->input->post("option_d", false),
            'ans' => $this->input->post("ans"),
            'instruction' => $this->input->post("instruction", false),
            'explanation' => $this->input->post("explanation", false)
        );
        
        $result = $this->welcomemodel->addquestion($question_data);
        echo json_encode($result);
    }
}

// AJAX: Get Question by ID
public function getquestion_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $question = $this->welcomemodel->getquestionbyid($id);
    if ($question) {
        echo json_encode(array(
            'status' => 'success',
            'question' => $question
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Question not found!'
        ));
    }
}

// AJAX: Update Question
public function updatequestion_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("question_id", "Question ID", "required|numeric");
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("topic_id", "Topic", "required|numeric");
    $this->form_validation->set_rules("qst", "Question", "required|trim|min_length[3]");
    $this->form_validation->set_rules("option_a", "Option A", "required|trim");
    $this->form_validation->set_rules("option_b", "Option B", "required|trim");
    $this->form_validation->set_rules("option_c", "Option C", "required|trim");
    $this->form_validation->set_rules("option_d", "Option D", "required|trim");
    $this->form_validation->set_rules("ans", "Correct Answer", "required|in_list[a,b,c,d]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $question_id = $this->input->post("question_id");
        $question_data = array(
            'crse_id' => $this->input->post("course_id"),
            'topic_id' => $this->input->post("topic_id"),
            'qst' => $this->input->post("qst", false),
            'option_a' => $this->input->post("option_a", false),
            'option_b' => $this->input->post("option_b", false),
            'option_c' => $this->input->post("option_c", false),
            'option_d' => $this->input->post("option_d", false),
            'ans' => $this->input->post("ans"),
            'instruction' => $this->input->post("instruction", false),
            'explanation' => $this->input->post("explanation", false)
        );
        
        $result = $this->welcomemodel->updatequestion($question_id, $question_data);
        echo json_encode($result);
    }
}

// AJAX: Delete Question
public function deletequestion_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deletequestion($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid question ID!'
        ));
    }
}

// AJAX: Get Topics by Course
public function gettopicsbycourse_ajax($course_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $topics = $this->welcomemodel->gettopicslistbycourse($course_id);
    echo json_encode(array(
        'status' => 'success',
        'topics' => $topics
    ));
}

// AJAX: Preview Question
public function previewquestion_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $question = $this->welcomemodel->getquestionbyid($id);
    if ($question) {
        echo json_encode(array(
            'status' => 'success',
            'question' => $question
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Question not found!'
        ));
    }
}
 // ========== BATCH UPLOAD CONTROLLER METHODS ==========
    
 public function batchupload() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get dropdown data
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    $data["answer_options"] = $this->welcomemodel->getansweroptions();
    
    $this->load->view("uploads/batch_upload", $data);
}
public function processbatchupload() {
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("topic_id", "Topic", "required|numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
        return;
    }
    
    // Check if file was uploaded
    if (!isset($_FILES['questions_file']) || empty($_FILES['questions_file']['name'])) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Please select a file to upload.'
        ));
        return;
    }
    
    // Create temp directory
    $upload_path = './uploads/temp/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }
    
    // Configure upload - ONLY TXT, DOC, DOCX files
    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'txt|doc|docx';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $config['overwrite'] = FALSE;
    
    $this->load->library('upload', $config);
    
    if (!$this->upload->do_upload('questions_file')) {
        $error = $this->upload->display_errors();
        error_log('Upload error: ' . $error);
        echo json_encode(array(
            'status' => 'error',
            'message' => $error
        ));
        return;
    }
    
    $upload_data = $this->upload->data();
    $file_path = $upload_data['full_path'];
    $file_extension = strtolower($upload_data['file_ext']);
    
    error_log('File uploaded: ' . $file_path . ', Extension: ' . $file_extension);
    
    try {
        // Process uploaded file using the new format
        $questions_data = $this->processUploadedFileByType($file_path, $file_extension);
        
        if (empty($questions_data)) {
            unlink($file_path);
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No valid data found in the uploaded file. Please check the format.'
            ));
            return;
        }
        
        error_log('Questions extracted: ' . count($questions_data) . ' records');
        
        // Add course and topic IDs to each question
        $course_id = $this->input->post("course_id");
        $topic_id = $this->input->post("topic_id");
        
        $processed_data = array();
        foreach ($questions_data as $question) {
            $question['course_id'] = $course_id;
            $question['topic_id'] = $topic_id;
            $processed_data[] = $question;
        }
        
        // Determine if this is just validation or actual upload
        $is_validation_only = !$this->input->post('confirm_upload');
        
        // Load model
        $this->load->model('welcomemodel');
        
        // Validate data
        $validation_result = $this->welcomemodel->validatebatchdata($processed_data);
        
        if (!empty($validation_result['errors'])) {
            unlink($file_path);
            echo json_encode(array(
                'status' => 'validation_error',
                'message' => 'Data validation failed',
                'validation_result' => $validation_result
            ));
            return;
        }
        
        if ($is_validation_only) {
            // Return validation success only
            unlink($file_path);
            echo json_encode(array(
                'status' => 'success',
                'message' => 'File validation successful',
                'validation_result' => $validation_result,
                'is_validation' => true
            ));
        } else {
            // Actually save the questions to database
            $save_result = $this->welcomemodel->addbatchquestions($validation_result['validated_data']);
            
            // Clean up temp file
            unlink($file_path);
            
            if ($save_result['status'] === 'success') {
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Questions uploaded successfully',
                    'upload_result' => $save_result,
                    'validation_result' => $validation_result,
                    'is_validation' => false
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Failed to save questions to database',
                    'upload_result' => $save_result,
                    'validation_result' => $validation_result
                ));
            }
        }
        
    } catch (Exception $e) {
        // Clean up temp file if exists
        if (isset($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }
        
        error_log('Exception: ' . $e->getMessage());
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Error processing file: ' . $e->getMessage()
        ));
    }
}

private function processUploadedFileByType($file_path, $file_extension) {
    switch ($file_extension) {
        case '.txt':
            return $this->processTextFileNewFormat($file_path);
        
        case '.doc':
        case '.docx':
            return $this->processWordFileNewFormat($file_path, $file_extension);
        
        default:
            throw new Exception("Unsupported file format: $file_extension. Only TXT, DOC, and DOCX files are allowed.");
    }
}

private function processTextFileNewFormat($file_path) {
    $questions = [];
    $content = file_get_contents($file_path);
    
    // Clean up the content
    $content = trim($content);
    
    // Split by lines
    $lines = preg_split('/\r\n|\r|\n/', $content);
    
    $line_number = 0;
    foreach ($lines as $line) {
        $line_number++;
        $line = trim($line);
        
        // Skip empty lines
        if (empty($line)) {
            continue;
        }
        
        // Check if line ends with pipe
        if (substr($line, -1) !== '|') {
            error_log("Line $line_number: Does not end with pipe character: " . substr($line, 0, 50) . "...");
            continue;
        }
        
        // Remove the ending pipe
        $line = rtrim($line, '|');
        
        // Split by double commas
        $parts = explode(',,', $line);
        
        // We need at least 6 parts (Question, A, B, C, D, Answer)
        if (count($parts) >= 6) {
            $question = [
                'question' => trim($parts[0]),
                'option_a' => trim($parts[1]),
                'option_b' => trim($parts[2]),
                'option_c' => trim($parts[3]),
                'option_d' => trim($parts[4]),
                'correct_answer' => strtoupper(trim($parts[5])),
                'instruction' => isset($parts[6]) ? trim($parts[6]) : '',
                'explanation' => isset($parts[7]) ? trim($parts[7]) : ''
            ];
            
            // Only add if we have a question
            if (!empty($question['question'])) {
                $questions[] = $question;
            }
        } else {
            error_log("Line $line_number: Invalid format - expected at least 6 parts separated by double commas, got " . count($parts));
        }
    }
    
    return $questions;
}

private function processWordFileNewFormat($file_path, $file_extension) {
    $questions = [];
    
    if ($file_extension === '.docx') {
        $questions = $this->processDocxFileNewFormat($file_path);
    } elseif ($file_extension === '.doc') {
        $questions = $this->processDocFileNewFormat($file_path);
    }
    
    return $questions;
}

private function processDocxFileNewFormat($file_path) {
    $questions = [];
    
    // Create a temporary directory for extraction
    $temp_dir = sys_get_temp_dir() . '/docx_extract_' . uniqid();
    mkdir($temp_dir, 0777, true);
    
    // Extract DOCX (it's a ZIP file)
    $zip = new ZipArchive;
    if ($zip->open($file_path) === TRUE) {
        $zip->extractTo($temp_dir);
        $zip->close();
        
        // Read the main document content
        $document_xml = $temp_dir . '/word/document.xml';
        if (file_exists($document_xml)) {
            $xml_content = file_get_contents($document_xml);
            
            // Remove XML namespaces to simplify parsing
            $xml_content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xml_content);
            
            // Extract all text between <w:t> tags
            preg_match_all('/<w:t[^>]*>([^<]+)<\/w:t>/i', $xml_content, $matches);
            
            if (!empty($matches[1])) {
                $full_text = implode("\n", $matches[1]);
                
                // Process the text using the new format
                $lines = preg_split('/\r\n|\r|\n/', $full_text);
                
                $line_number = 0;
                foreach ($lines as $line) {
                    $line_number++;
                    $line = trim($line);
                    
                    // Skip empty lines
                    if (empty($line)) {
                        continue;
                    }
                    
                    // Check if line ends with pipe
                    if (substr($line, -1) !== '|') {
                        continue;
                    }
                    
                    // Remove the ending pipe
                    $line = rtrim($line, '|');
                    
                    // Split by double commas
                    $parts = explode(',,', $line);
                    
                    // We need at least 6 parts
                    if (count($parts) >= 6) {
                        $question = [
                            'question' => trim($parts[0]),
                            'option_a' => trim($parts[1]),
                            'option_b' => trim($parts[2]),
                            'option_c' => trim($parts[3]),
                            'option_d' => trim($parts[4]),
                            'correct_answer' => strtoupper(trim($parts[5])),
                            'instruction' => isset($parts[6]) ? trim($parts[6]) : '',
                            'explanation' => isset($parts[7]) ? trim($parts[7]) : ''
                        ];
                        
                        if (!empty($question['question'])) {
                            $questions[] = $question;
                        }
                    }
                }
            }
        }
        
        // Clean up
        $this->deleteDirectory($temp_dir);
    }
    
    return $questions;
}

private function processDocFileNewFormat($file_path) {
    $questions = [];
    
    // Read the file as binary
    $content = file_get_contents($file_path);
    
    // Convert binary to text (basic approach for .doc files)
    $text = '';
    for ($i = 0; $i < strlen($content); $i++) {
        $char = $content[$i];
        // Keep printable characters, newlines, and tabs
        if (ord($char) >= 32 && ord($char) <= 126) {
            $text .= $char;
        } elseif (ord($char) == 10 || ord($char) == 13) {
            $text .= "\n";
        } elseif (ord($char) == 9) {
            $text .= "\t";
        }
    }
    
    // Process the text using the new format
    $lines = preg_split('/\r\n|\r|\n/', $text);
    
    $line_number = 0;
    foreach ($lines as $line) {
        $line_number++;
        $line = trim($line);
        
        // Skip empty lines
        if (empty($line)) {
            continue;
        }
        
        // Check if line ends with pipe
        if (substr($line, -1) !== '|') {
            continue;
        }
        
        // Remove the ending pipe
        $line = rtrim($line, '|');
        
        // Split by double commas
        $parts = explode(',,', $line);
        
        // We need at least 6 parts
        if (count($parts) >= 6) {
            $question = [
                'question' => trim($parts[0]),
                'option_a' => trim($parts[1]),
                'option_b' => trim($parts[2]),
                'option_c' => trim($parts[3]),
                'option_d' => trim($parts[4]),
                'correct_answer' => strtoupper(trim($parts[5])),
                'instruction' => isset($parts[6]) ? trim($parts[6]) : '',
                'explanation' => isset($parts[7]) ? trim($parts[7]) : ''
            ];
            
            if (!empty($question['question'])) {
                $questions[] = $question;
            }
        }
    }
    
    return $questions;
}

// Add new template download methods
public function downloadtexttemplate() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    $filename = "questions_template.txt";
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Create template content
    $template = "Question Format Template
================================

INSTRUCTIONS:
1. Each question must be on a single line
2. Fields are separated by DOUBLE COMMAS (,,)
3. Each line must end with a PIPE character (|)
4. All fields except Instruction and Explanation are required
5. Correct Answer must be A, B, C, or D (case insensitive)

FORMAT:
Question,,Option A,,Option B,,Option C,,Option D,,Correct Answer,,Instruction,,Explanation|

EXAMPLES:
What is 2 + 2?,,3,,4,,5,,6,,B,,Choose the correct answer.,,2 + 2 equals 4, which is option B.|
What is the capital of France?,,London,,Berlin,,Paris,,Madrid,,C,,,,Paris is the capital of France.|
Chemical symbol for water?,,H,,HO,,H2O,,OH,,C,,,,H2O is the chemical formula for water.|
Who wrote 'Romeo and Juliet'?,,Charles Dickens,,William Shakespeare,,Mark Twain,,Jane Austen,,B,,,,William Shakespeare wrote Romeo and Juliet.|

YOUR QUESTIONS (Replace these with your own):
[Your Question 1],,[Option A],,[Option B],,[Option C],,[Option D],,[Correct Answer],,[Instruction],,[Explanation]|
[Your Question 2],,[Option A],,[Option B],,[Option C],,[Option D],,[Correct Answer],,[Instruction],,[Explanation]|
[Your Question 3],,[Option A],,[Option B],,[Option C],,[Option D],,[Correct Answer],,[Instruction],,[Explanation]|
";
    
    echo $template;
    exit;
}

public function downloadwordtemplate() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Create a simple text file that can be opened in Word
    $filename = "questions_template_for_word.txt";
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Create template content
    $template = "Questions Import Template for Microsoft Word
=================================================

INSTRUCTIONS FOR WORD USERS:
1. Type each question on a separate line
2. Use DOUBLE COMMAS (,,) to separate fields
3. End each line with a PIPE character (|)
4. Save this file as .txt format
5. Upload the .txt file to the system

FORMAT PER LINE:
Question,,Option A,,Option B,,Option C,,Option D,,Correct Answer,,Instruction,,Explanation|

EXAMPLE QUESTIONS:
What is 2 + 2?,,3,,4,,5,,6,,B,,Choose the correct answer.,,2 + 2 equals 4, which is option B.|
What is the capital of France?,,London,,Berlin,,Paris,,Madrid,,C,,,,Paris is the capital of France.|

YOUR QUESTIONS:
[Type your question here],,[Option A],,[Option B],,[Option C],,[Option D],,[A/B/C/D],,[Instruction if any],,[Explanation if any]|
";
    
    echo $template;
    exit;
}


private function processCSVFile($file_path) {
    $questions = [];
    
    if (($handle = fopen($file_path, 'r')) !== FALSE) {
        $row = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $row++;
            // Skip header row
            if ($row === 1 && (strtolower($data[0]) === 'question' || strtolower($data[0]) === 'question text')) {
                continue;
            }
            
            // Check if we have at least 6 columns (Question, A, B, C, D, Correct Answer)
            if (count($data) >= 6) {
                $question = [
                    'question' => trim($data[0]),
                    'option_a' => trim($data[1]),
                    'option_b' => trim($data[2]),
                    'option_c' => trim($data[3]),
                    'option_d' => trim($data[4]),
                    'correct_answer' => strtoupper(trim($data[5])),
                    'instruction' => isset($data[6]) ? trim($data[6]) : '',
                    'explanation' => isset($data[7]) ? trim($data[7]) : ''
                ];
                $questions[] = $question;
            }
        }
        fclose($handle);
    }
    
    return $questions;
}

private function processExcelFile($file_path) {
    // Load PHPExcel library
    $this->load->library('PHPExcel');
    
    $questions = [];
    
    // Determine reader type based on file extension
    $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    
    if ($file_ext == 'xlsx') {
        $reader = new PHPExcel_Reader_Excel2007();
    } else {
        $reader = new PHPExcel_Reader_Excel5();
    }
    
    $phpExcel = $reader->load($file_path);
    $worksheet = $phpExcel->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    
    for ($row = 1; $row <= $highestRow; $row++) {
        // Skip header row
        if ($row === 1 && (strtolower($worksheet->getCell('A'.$row)->getValue()) === 'question' || 
            strtolower($worksheet->getCell('A'.$row)->getValue()) === 'question text')) {
            continue;
        }
        
        $question = [
            'question' => trim($worksheet->getCell('A'.$row)->getValue()),
            'option_a' => trim($worksheet->getCell('B'.$row)->getValue()),
            'option_b' => trim($worksheet->getCell('C'.$row)->getValue()),
            'option_c' => trim($worksheet->getCell('D'.$row)->getValue()),
            'option_d' => trim($worksheet->getCell('E'.$row)->getValue()),
            'correct_answer' => strtoupper(trim($worksheet->getCell('F'.$row)->getValue())),
            'instruction' => trim($worksheet->getCell('G'.$row)->getValue()),
            'explanation' => trim($worksheet->getCell('H'.$row)->getValue())
        ];
        
        // Only add if we have a question
        if (!empty($question['question'])) {
            $questions[] = $question;
        }
    }
    
    return $questions;
}

private function processTextFile($file_path) {
    $questions = [];
    $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line_num => $line) {
        // Skip header row if exists
        if ($line_num === 0 && (strpos(strtolower($line), 'question') !== false || 
            strpos(strtolower($line), 'question,') !== false)) {
            continue;
        }
        
        // Try to parse as CSV first (for tab-separated or comma-separated)
        $data = str_getcsv($line);
        
        if (count($data) >= 6) {
            $question = [
                'question' => trim($data[0]),
                'option_a' => trim($data[1]),
                'option_b' => trim($data[2]),
                'option_c' => trim($data[3]),
                'option_d' => trim($data[4]),
                'correct_answer' => strtoupper(trim($data[5])),
                'instruction' => isset($data[6]) ? trim($data[6]) : '',
                'explanation' => isset($data[7]) ? trim($data[7]) : ''
            ];
            $questions[] = $question;
        }
    }
    
    return $questions;
}

private function processWordFile($file_path) {
    $questions = [];
    
    // For DOCX files (they're ZIP archives)
    if (strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'docx') {
        $questions = $this->processDocxFile($file_path);
    } 
    // For DOC files - simpler text extraction
    elseif (strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'doc') {
        $questions = $this->processDocFile($file_path);
    }
    
    return $questions;
}

private function processDocxFile($file_path) {
    $questions = [];
    
    // Create a temporary directory for extraction
    $temp_dir = sys_get_temp_dir() . '/docx_extract_' . uniqid();
    mkdir($temp_dir, 0777, true);
    
    // Extract DOCX (it's a ZIP file)
    $zip = new ZipArchive;
    if ($zip->open($file_path) === TRUE) {
        $zip->extractTo($temp_dir);
        $zip->close();
        
        // Read the main document content
        $document_xml = $temp_dir . '/word/document.xml';
        if (file_exists($document_xml)) {
            $xml = simplexml_load_file($document_xml);
            
            // Register namespaces
            $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
            
            // Get all text runs
            $texts = $xml->xpath('//w:t');
            $full_text = '';
            foreach ($texts as $text) {
                $full_text .= (string)$text . "\n";
            }
            
            // Process the text as if it were a CSV
            $lines = explode("\n", $full_text);
            $current_question = [];
            $question_count = 0;
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Simple parsing logic - adjust based on your Word file structure
                // This assumes each question is on its own line with comma/tab separation
                $data = str_getcsv($line);
                
                if (count($data) >= 6) {
                    $question = [
                        'question' => trim($data[0]),
                        'option_a' => trim($data[1]),
                        'option_b' => trim($data[2]),
                        'option_c' => trim($data[3]),
                        'option_d' => trim($data[4]),
                        'correct_answer' => strtoupper(trim($data[5])),
                        'instruction' => isset($data[6]) ? trim($data[6]) : '',
                        'explanation' => isset($data[7]) ? trim($data[7]) : ''
                    ];
                    $questions[] = $question;
                }
            }
        }
        
        // Clean up
        $this->deleteDirectory($temp_dir);
    }
    
    return $questions;
}

private function processDocFile($file_path) {
    $questions = [];
    
    // For .doc files, we'll use a simple text extraction approach
    // Note: This is a basic approach and may not work perfectly for all .doc files
    
    // Read the file as binary and extract text between certain markers
    $content = file_get_contents($file_path);
    
    // Try to extract text (basic approach)
    preg_match_all('/[^\x00-\x1F\x7F-\xFF]{4,}/', $content, $matches);
    
    if (!empty($matches[0])) {
        $lines = $matches[0];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 10) { // Likely a question if it's longer
                // Simple parsing - you might need to adjust this based on your .doc format
                $parts = preg_split('/[\t,;|]+/', $line);
                
                if (count($parts) >= 6) {
                    $question = [
                        'question' => trim($parts[0]),
                        'option_a' => isset($parts[1]) ? trim($parts[1]) : '',
                        'option_b' => isset($parts[2]) ? trim($parts[2]) : '',
                        'option_c' => isset($parts[3]) ? trim($parts[3]) : '',
                        'option_d' => isset($parts[4]) ? trim($parts[4]) : '',
                        'correct_answer' => isset($parts[5]) ? strtoupper(trim($parts[5])) : '',
                        'instruction' => isset($parts[6]) ? trim($parts[6]) : '',
                        'explanation' => isset($parts[7]) ? trim($parts[7]) : ''
                    ];
                    
                    // Only add if we have at least a question and options
                    if (!empty($question['question']) && !empty($question['option_a'])) {
                        $questions[] = $question;
                    }
                }
            }
        }
    }
    
    return $questions;
}

private function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}

// FIX 5: Add actual upload method (separate from validation)
public function savebatchquestions() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("topic_id", "Topic", "required|numeric");
    $this->form_validation->set_rules("questions_data", "Questions Data", "required");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => validation_errors()
        ));
        return;
    }
    
    // Get posted data
    $course_id = $this->input->post("course_id");
    $topic_id = $this->input->post("topic_id");
    $questions_data = $this->input->post("questions_data");
    
    if (empty($questions_data)) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'No questions data received'
        ));
        return;
    }
    
    // Decode questions data if it's a JSON string
    if (is_string($questions_data)) {
        $questions_data = json_decode($questions_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Invalid questions data format'
            ));
            return;
        }
    }
    
    // Add course and topic IDs to each question
    $processed_data = array();
    foreach ($questions_data as $question) {
        $question['course_id'] = $course_id;
        $question['topic_id'] = $topic_id;
        $processed_data[] = $question;
    }
    
    // Add questions to database
    $result = $this->welcomemodel->addbatchquestions($processed_data);
    
    echo json_encode($result);
}

public function downloadtemplate($type = 'csv') {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    $filename = "questions_template." . $type;
    
    if ($type === 'csv') {
        $this->downloadCSVTemplate($filename);
    } elseif ($type === 'excel') {
        $this->downloadExcelTemplate($filename);
    } else {
        show_error("Invalid template type");
    }
}

private function downloadCSVTemplate($filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    $headers = array(
        'Question',
        'Option A', 
        'Option B',
        'Option C',
        'Option D',
        'Correct Answer',
        'Instruction (Optional)',
        'Explanation (Optional)'
    );
    
    fputcsv($output, $headers);
    
    // Write sample data
    $sample_data = array(
        array(
            'What is 2 + 2?',
            '3',
            '4',
            '5',
            '6',
            'B',
            'Choose the correct answer.',
            '2 + 2 equals 4, which is option B.'
        ),
        array(
            'What is the capital of France?',
            'London',
            'Berlin',
            'Paris',
            'Madrid',
            'C',
            '',
            'Paris is the capital of France.'
        )
    );
    
    foreach ($sample_data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

private function downloadExcelTemplate($filename) {
    // Load PHPExcel library
    $this->load->library('PHPExcel');
    
    $objPHPExcel = new PHPExcel();
    
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Question Bank System")
                                 ->setLastModifiedBy("Question Bank System")
                                 ->setTitle("Questions Template")
                                 ->setSubject("Questions Import Template")
                                 ->setDescription("Template for batch uploading questions");
    
    // Add headers
    $headers = array(
        'Question',
        'Option A', 
        'Option B',
        'Option C',
        'Option D',
        'Correct Answer',
        'Instruction (Optional)',
        'Explanation (Optional)'
    );
    
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle('Questions Template');
    
    // Write headers
    $column = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($column . '1', $header);
        $sheet->getStyle($column . '1')->getFont()->setBold(true);
        $column++;
    }
    
    // Write sample data
    $sample_data = array(
        array('What is 2 + 2?', '3', '4', '5', '6', 'B', 'Choose the correct answer.', '2 + 2 equals 4, which is option B.'),
        array('What is the capital of France?', 'London', 'Berlin', 'Paris', 'Madrid', 'C', '', 'Paris is the capital of France.')
    );
    
    $row = 2;
    foreach ($sample_data as $data) {
        $column = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($column . $row, $value);
            $column++;
        }
        $row++;
    }
    
    // Auto-size columns
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Set header for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}

public function getdepartmentsforschool($school_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $departments = $this->welcomemodel->getdepartmentsbyschool($school_id);
    echo json_encode(array(
        'status' => 'success',
        'departments' => $departments
    ));
}

public function getcoursesfordepartment($school_id, $dept_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $courses = $this->welcomemodel->getcoursesbyfilters($school_id, $dept_id);
    echo json_encode(array(
        'status' => 'success',
        'courses' => $courses
    ));
}

public function gettopicsforcourse($course_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $topics = $this->welcomemodel->gettopicslistbycourse($course_id);
    echo json_encode(array(
        'status' => 'success',
        'topics' => $topics
    ));
}

 // ========== NOTES CONTROLLER METHODS ==========
    
  // ========== UPDATED NOTES CONTROLLER METHODS ==========

public function opennotes() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get filter parameters
    $school_id = $this->input->get('school_id');
    $dept_id = $this->input->get('dept_id');
    $course_id = $this->input->get('course_id');
    $topic_id = $this->input->get('topic_id');
    
    // Get notes data for the table with filters
    $db = $this->welcomemodel->getnotesview($school_id, $dept_id, $course_id, $topic_id);
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["note_count"] = $db["noteCount"];
    
    // Get dropdown data
    $data["schools"] = $this->welcomemodel->getschoolsfordropdown();
    $data["departments"] = $this->welcomemodel->getdepartmentsfordropdown($school_id);
    $data["courses"] = $this->welcomemodel->getcoursesfordropdown($school_id, $dept_id);
    
    // Get topics for the selected course
    $topics = array();
    if ($course_id) {
        $topics = $this->welcomemodel->gettopicsbycoursefornotes($course_id);
    }
    $data["topics"] = $topics;
    
    // Store filter values for repopulating form
    $data["filter_school_id"] = $school_id;
    $data["filter_dept_id"] = $dept_id;
    $data["filter_course_id"] = $course_id;
    $data["filter_topic_id"] = $topic_id;
    
    $this->load->view("uploads/notes", $data);
}

// AJAX: Add Note (from WYSIWYG editor)
public function addnote_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("title", "Title", "required|trim|min_length[3]|max_length[255]");
    $this->form_validation->set_rules("course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("note_content", "Note Content", "required|trim|min_length[10]");
    $this->form_validation->set_rules("topic_id", "Topic", "numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $title = $this->input->post("title");
        $course_id = $this->input->post("course_id");
        $topic_id = $this->input->post("topic_id");
        $note_content = $this->input->post("note_content", false);
        
        // Check for duplicate title
        if ($this->welcomemodel->checknotetitle($title, $course_id)) {
            echo json_encode(array(
                'status' => 'warning',
                'message' => 'A note with this title already exists for this course!'
            ));
            return;
        }
        
        $note_data = array(
            'title' => $title,
            'course_id' => $course_id,
            'topic_id' => $topic_id,
            'content' => $note_content
        );
        
        $result = $this->welcomemodel->addnote($note_data);
        echo json_encode($result);
    }
}

// AJAX: Add Note from File Upload
public function addnotefile_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("file_title", "Title", "required|trim|min_length[3]|max_length[255]");
    $this->form_validation->set_rules("file_course_id", "Course", "required|numeric");
    $this->form_validation->set_rules("file_topic_id", "Topic", "numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
        return;
    }
    
    // Check if file was uploaded
    if (empty($_FILES['note_file']['name'])) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Please select a file to upload'
        ));
        return;
    }
    
    // Configure upload - ONLY TXT, DOC, DOCX
    $config['upload_path'] = './uploads/notes/';
    $config['allowed_types'] = 'txt|doc|docx';
    $config['max_size'] = 10240; // 10MB
    $config['encrypt_name'] = TRUE;
    
    // Create upload directory if it doesn't exist
    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, true);
    }
    
    $this->load->library('upload', $config);
    
    if (!$this->upload->do_upload('note_file')) {
        echo json_encode(array(
            'status' => 'error',
            'message' => $this->upload->display_errors()
        ));
        return;
    }
    
    $upload_data = $this->upload->data();
    $file_path = $upload_data['full_path'];
    $file_extension = strtolower(pathinfo($upload_data['original_name'], PATHINFO_EXTENSION));
    $original_filename = $upload_data['original_name'];
    
    try {
        // Read and convert file content
        $file_content = $this->readAndConvertFile($file_path, $file_extension, $original_filename);
        
        if (empty($file_content)) {
            unlink($file_path);
            echo json_encode(array(
                'status' => 'error',
                'message' => 'File is empty or could not be read'
            ));
            return;
        }
        
        $title = $this->input->post("file_title");
        $course_id = $this->input->post("file_course_id");
        $topic_id = $this->input->post("file_topic_id");
        
        // Check for duplicate title
        if ($this->welcomemodel->checknotetitle($title, $course_id)) {
            unlink($file_path);
            echo json_encode(array(
                'status' => 'warning',
                'message' => 'A note with this title already exists for this course!'
            ));
            return;
        }
        
        $note_data = array(
            'title' => $title,
            'course_id' => $course_id,
            'topic_id' => $topic_id,
            'content' => $file_content,
            'file_type' => $file_extension,
            'original_filename' => $original_filename
        );
        
        // Add note from file content
        $result = $this->welcomemodel->addnotefromfile($note_data);
        
        // Clean up uploaded file
        unlink($file_path);
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        // Clean up temp file if exists
        if (isset($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }
        
        echo json_encode(array(
            'status' => 'error',
            'message' => $e->getMessage()
        ));
    }
}

private function readAndConvertFile($file_path, $file_extension, $original_filename) {
    $content = '';
    
    switch ($file_extension) {
        case 'txt':
            $content = file_get_contents($file_path);
            // Convert plain text to HTML
            $content = $this->convertTextToHTML($content);
            break;
            
        case 'doc':
            // For DOC files - use antiword if available, otherwise basic extraction
            $content = $this->extractTextFromDOC($file_path);
            $content = $this->convertTextToHTML($content);
            break;
            
        case 'docx':
            // For DOCX files - extract text and convert to HTML
            $content = $this->extractTextFromDOCX($file_path);
            $content = $this->convertTextToHTML($content);
            break;
            
        default:
            throw new Exception("Unsupported file format: $file_extension");
    }
    
    // Add file info as a header
    $file_info = '<div class="file-info-header alert alert-info">';
    $file_info .= '<strong>Uploaded from file:</strong> ' . htmlspecialchars($original_filename);
    $file_info .= ' (' . strtoupper($file_extension) . ' format)';
    $file_info .= '<br><small>Uploaded on: ' . date('Y-m-d H:i:s') . '</small>';
    $file_info .= '</div><hr>';
    
    return $file_info . $content;
}

private function convertTextToHTML($text) {
    // Convert plain text to HTML with basic formatting
    $html = '<div class="note-content">';
    
    // Convert line breaks
    $text = nl2br(htmlspecialchars($text));
    
    // Convert multiple newlines to paragraphs
    $paragraphs = preg_split('/\n\s*\n/', $text);
    
    foreach ($paragraphs as $paragraph) {
        $paragraph = trim($paragraph);
        if (!empty($paragraph)) {
            // Check if it looks like a heading
            if (strlen($paragraph) < 100 && !preg_match('/[.!?]$/', $paragraph)) {
                $html .= '<h4>' . $paragraph . '</h4>';
            } else {
                $html .= '<p>' . $paragraph . '</p>';
            }
        }
    }
    
    $html .= '</div>';
    return $html;
}

private function extractTextFromDOC($file_path) {
    // Try to use antiword if available on the server
    if (function_exists('shell_exec')) {
        // Check if antiword is installed
        $antiword_check = shell_exec('which antiword 2>/dev/null');
        if ($antiword_check) {
            $content = shell_exec('antiword "' . $file_path . '" 2>/dev/null');
            if (!empty($content)) {
                return $content;
            }
        }
    }
    
    // Fallback: try to extract text from binary DOC file
    $content = file_get_contents($file_path);
    
    // Simple text extraction for .doc files (very basic)
    $text = '';
    $in_text = false;
    $text_length = strlen($content);
    
    for ($i = 0; $i < $text_length; $i++) {
        $char = $content[$i];
        // Look for printable ASCII characters
        if (ord($char) >= 32 && ord($char) <= 126) {
            $text .= $char;
            $in_text = true;
        } elseif (ord($char) == 10 || ord($char) == 13) {
            if ($in_text) {
                $text .= "\n";
                $in_text = false;
            }
        }
    }
    
    if (empty($text)) {
        $text = "Unable to extract text from DOC file. Please convert to DOCX or TXT format for better results.";
    }
    
    return $text;
}

private function extractTextFromDOCX($file_path) {
    $content = '';
    
    $zip = new ZipArchive;
    if ($zip->open($file_path) === TRUE) {
        // Read the main document XML
        if (($index = $zip->locateName("word/document.xml")) !== FALSE) {
            $xml = $zip->getFromIndex($index);
            
            // Remove namespaces for easier parsing
            $xml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xml);
            
            // Extract text from paragraphs
            preg_match_all('/<w:p[^>]*>(.*?)<\/w:p>/is', $xml, $paragraphs);
            
            foreach ($paragraphs[1] as $para) {
                // Extract text runs
                preg_match_all('/<w:t[^>]*>([^<]+)<\/w:t>/i', $para, $text_runs);
                $paragraph_text = '';
                
                foreach ($text_runs[1] as $text) {
                    $paragraph_text .= $text;
                }
                
                $paragraph_text = trim($paragraph_text);
                if (!empty($paragraph_text)) {
                    $content .= $paragraph_text . "\n\n";
                }
            }
        }
        
        // Try to extract from header files too
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/word\/header\d+\.xml/', $filename)) {
                $xml = $zip->getFromIndex($i);
                $xml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xml);
                preg_match_all('/<w:t[^>]*>([^<]+)<\/w:t>/i', $xml, $matches);
                foreach ($matches[1] as $text) {
                    $content .= trim($text) . "\n";
                }
            }
        }
        
        $zip->close();
    }
    
    if (empty($content)) {
        $content = "Unable to extract text from DOCX file. Please try saving as TXT format.";
    }
    
    return $content;
}

// AJAX: Update Note
public function updatenote_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("note_id", "Note ID", "required|numeric");
    $this->form_validation->set_rules("title", "Title", "required|trim|min_length[3]|max_length[255]");
    $this->form_validation->set_rules("note_content", "Note Content", "required|trim|min_length[10]");
    $this->form_validation->set_rules("topic_id", "Topic", "numeric");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $note_id = $this->input->post("note_id");
        $title = $this->input->post("title");
        $topic_id = $this->input->post("topic_id");
        $note_content = $this->input->post("note_content", false);
        
        // Get current note to check course_id
        $current_note = $this->welcomemodel->getnotebyid($note_id);
        if (!$current_note) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Note not found!'
            ));
            return;
        }
        
        // Check for duplicate title (excluding current note)
        if ($this->welcomemodel->checknotetitle($title, $current_note->crse_id, $note_id)) {
            echo json_encode(array(
                'status' => 'warning',
                'message' => 'Another note with this title already exists for this course!'
            ));
            return;
        }
        
        $note_data = array(
            'title' => $title,
            'topic_id' => $topic_id,
            'content' => $note_content
        );
        
        $result = $this->welcomemodel->updatenote($note_id, $note_data);
        echo json_encode($result);
    }
}

// AJAX: Get topics for a course
public function gettopicsforcourse_ajax($course_id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $topics = $this->welcomemodel->gettopicsbycoursefornotes($course_id);
    echo json_encode(array(
        'status' => 'success',
        'topics' => $topics
    ));
}
// ========== NEWS/UPDATES CONTROLLER METHODS ==========

public function opennews() {
    if ($this->session->userdata("admin_pass") == "") {
        redirect("welcome/");
    }
    
    // Load general store information
    $data = $this->storeinfo->getStoreInfo();
    
    // Get filter parameters
    $type = $this->input->get('type');
    $status = $this->input->get('status');
    
    // Get news data for the table with filters
    $db = $this->welcomemodel->getnewsview($type, $status);
    $data["dbhead"] = $db["head"];
    $data["dbbody"] = $db["body"];
    $data["news_count"] = $db["news_count"];
    
    // Get dropdown data
    $data["news_types"] = $this->welcomemodel->getnewstypes();
    $data["news_statuses"] = $this->welcomemodel->getnewsstatuses();
    
    // Store filter values for repopulating form
    $data["filter_type"] = $type;
    $data["filter_status"] = $status;
    
    $this->load->view("uploads/news_updates", $data);
}

// AJAX: Add News/Update
public function addnews_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("title", "Title", "required|trim|min_length[5]|max_length[255]");
    $this->form_validation->set_rules("content", "Content", "required|trim|min_length[10]");
    $this->form_validation->set_rules("type", "Type", "required|in_list[news,update,info,announcement]");
    $this->form_validation->set_rules("status", "Status", "required|in_list[draft,published,archived]");
    $this->form_validation->set_rules("excerpt", "Excerpt", "trim|max_length[500]");
    $this->form_validation->set_rules("meta_keywords", "Meta Keywords", "trim");
    $this->form_validation->set_rules("meta_description", "Meta Description", "trim|max_length[160]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        // Handle file upload
        $upload_data = $this->handle_featured_image_upload();
        
        $news_data = array(
            'title' => $this->input->post("title"),
            'content' => $this->input->post("content", false),
            'excerpt' => $this->input->post("excerpt"),
            'type' => $this->input->post("type"),
            'status' => $this->input->post("status"),
            'publish_date' => $this->input->post("publish_date"),
            'expiry_date' => $this->input->post("expiry_date"),
            'is_featured' => $this->input->post("is_featured") ? 1 : 0,
            'meta_keywords' => $this->input->post("meta_keywords"),
            'meta_description' => $this->input->post("meta_description"),
            'author_id' => $this->session->userdata("admin_id") // Assuming you store admin_id in session
        );
        
        if ($upload_data && !$upload_data['error']) {
            $news_data['featured_image'] = $upload_data['file_name'];
        }
        
        $result = $this->welcomemodel->addnews($news_data);
        echo json_encode($result);
    }
}

// AJAX: Get News by ID
public function getnews_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $news = $this->welcomemodel->getnewsbyid($id);
    if ($news) {
        echo json_encode(array(
            'status' => 'success',
            'news' => $news
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'News/Update not found!'
        ));
    }
}

// AJAX: Update News
public function updatenews_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules("news_id", "News ID", "required|numeric");
    $this->form_validation->set_rules("title", "Title", "required|trim|min_length[5]|max_length[255]");
    $this->form_validation->set_rules("content", "Content", "required|trim|min_length[10]");
    $this->form_validation->set_rules("type", "Type", "required|in_list[news,update,info,announcement]");
    $this->form_validation->set_rules("status", "Status", "required|in_list[draft,published,archived]");
    
    if ($this->form_validation->run() == FALSE) {
        echo json_encode(array(
            'status' => 'validation_error',
            'message' => validation_errors()
        ));
    } else {
        $news_id = $this->input->post("news_id");
        
        // Handle file upload
        $upload_data = $this->handle_featured_image_upload();
        
        $news_data = array(
            'title' => $this->input->post("title"),
            'content' => $this->input->post("content", false),
            'excerpt' => $this->input->post("excerpt"),
            'type' => $this->input->post("type"),
            'status' => $this->input->post("status"),
            'publish_date' => $this->input->post("publish_date"),
            'expiry_date' => $this->input->post("expiry_date"),
            'is_featured' => $this->input->post("is_featured") ? 1 : 0,
            'meta_keywords' => $this->input->post("meta_keywords"),
            'meta_description' => $this->input->post("meta_description")
        );
        
        if ($upload_data && !$upload_data['error']) {
            $news_data['featured_image'] = $upload_data['file_name'];
        }
        
        $result = $this->welcomemodel->updatenews($news_id, $news_data);
        echo json_encode($result);
    }
}

// AJAX: Delete News
public function deletenews_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $id = $this->input->post("id");
    if (!empty($id)) {
        $result = $this->welcomemodel->deletenews($id);
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid news ID!'
        ));
    }
}

// AJAX: Preview News
public function previewnews_ajax($id) {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $news = $this->welcomemodel->getnewsbyid($id);
    if ($news) {
        echo json_encode(array(
            'status' => 'success',
            'news' => $news
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'News not found!'
        ));
    }
}

// Public: View News Detail
public function news_detail($slug) {
    $data = $this->storeinfo->getStoreInfo();
    $news = $this->welcomemodel->getnewsbyslug($slug);
    
    if (!$news) {
        show_404();
    }
    
    // Increment views
    $this->welcomemodel->incrementviews($news->id);
    
    $data['news'] = $news;
    $data['related_news'] = $this->welcomemodel->getrecentnews(5, $news->type);
    
    $this->load->view("public/news_detail", $data);
}

// Public: News Archive
public function news_archive($type = null, $page = 1) {
    $data = $this->storeinfo->getStoreInfo();
    
    // Pagination
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    $data['news_list'] = $this->welcomemodel->getnewsbytype($type, $limit, $offset);
    $data['current_type'] = $type;
    $data['news_types'] = $this->welcomemodel->getnewstypes();
    
    // Pagination config
    $total_news = $this->welcomemodel->countnewsbytype($type, 'published');
    $data['total_pages'] = ceil($total_news / $limit);
    $data['current_page'] = $page;
    
    $this->load->view("public/news_archive", $data);
}

// Helper function for file upload
private function handle_featured_image_upload() {
    if (isset($_FILES['featured_image']['name']) && !empty($_FILES['featured_image']['name'])) {
        $config['upload_path'] = './uploads/news/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = FALSE;
        
        // Create upload directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        
        $this->load->library('upload', $config);
        
        if ($this->upload->do_upload('featured_image')) {
            return $this->upload->data();
        } else {
            return array('error' => $this->upload->display_errors());
        }
    }
    
    return null;
}

// AJAX: Remove featured image
public function removefeaturedimage_ajax() {
    if ($this->session->userdata("admin_pass") == "") {
        echo json_encode(array('status' => 'error', 'message' => 'Session expired!'));
        return;
    }
    
    $news_id = $this->input->post('news_id');
    $news = $this->welcomemodel->getnewsbyid($news_id);
    
    if ($news && $news->featured_image) {
        // Delete the image file
        $image_path = './uploads/news/' . $news->featured_image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Update database
        $this->db->where('id', $news_id);
        $this->db->update('news_updates', array('featured_image' => null));
        
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Featured image removed successfully!'
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'News or image not found!'
        ));
    }
}





















    
    
    public function updatestoreinfo() {
        if ($this->session->userdata("admin_pass") == "") {
            redirect("welcome/");
        }
    
        // Set validation rules for existing fields
        $this->form_validation->set_rules("name", "Name", "required|trim|min_length[3]|max_length[100]");
        $this->form_validation->set_rules("information", "About Store Information", "required|trim|min_length[1]|max_length[500]");
        $this->form_validation->set_rules("welcome_text", "Welcome Text", "required|trim|min_length[1]|max_length[200]");
        $this->form_validation->set_rules("welcome_quote", "Welcome Quote", "required|trim|min_length[1]|max_length[100]");
        $this->form_validation->set_rules("address", "Address", "required|trim|min_length[1]|max_length[100]");
        $this->form_validation->set_rules("telephone", "Telephone", "required|trim|min_length[1]");
        $this->form_validation->set_rules("email", "Email", "required|trim|min_length[1]|valid_email");
        $this->form_validation->set_rules("open_hours", "Opening Hours", "required|trim|min_length[1]");
        $this->form_validation->set_rules("colour", "Theme Colour", "required|trim|min_length[1]");
        $this->form_validation->set_rules("googlemap", "Google Map Location", "required|trim");
        $this->form_validation->set_rules("whatsapp", "Whatsapp Number", "trim");
        $this->form_validation->set_rules("instagram", "Instagram Link", "trim");
        $this->form_validation->set_rules("youtube", "Youtube Link", "trim");
        $this->form_validation->set_rules("facebook", "Facebook Link", "trim");
    
        // Validation rules for dynamic fields (Core Values, Services, Special Offer)
        for ($i = 1; $i <= 6; $i++) {
            $this->form_validation->set_rules("value_title_" . $i, "Core Value Title " . $i, "required|trim|min_length[1]");
            $this->form_validation->set_rules("value_text_" . $i, "Core Value Text " . $i, "required|trim|min_length[1]");
        }
        for ($i = 1; $i <= 3; $i++) {
            $this->form_validation->set_rules("service" . $i, "Service " . $i, "required|trim|min_length[1]");
        }
        $this->form_validation->set_rules("spe_desc", "Special Offer Description", "required|trim|min_length[1]");
        $this->form_validation->set_rules("spe_link", "Special Offer Link", "required|trim|valid_url");
        $this->form_validation->set_rules("spe_ends_at", "Special Offer End Date", "required|trim");
    
        // --- NEW: Validation rules for Meta Keywords and Paystack Keys ---
        $this->form_validation->set_rules("meta_key_1", "Meta Keyword 1", "required|trim");
        $this->form_validation->set_rules("meta_key_2", "Meta Keyword 2", "required|trim");
        $this->form_validation->set_rules("meta_key_3", "Meta Keyword 3", "required|trim");
        $this->form_validation->set_rules("acct_name", "Account Name", "required|trim");
        $this->form_validation->set_rules("acct_num", "Account Number", "required|trim");
        $this->form_validation->set_rules("acct_bank", "Account Bank", "required|trim");
        $this->form_validation->set_rules("public_key", "Paystack Public Key", "trim");
        $this->form_validation->set_rules("private_key", "Paystack Private Key", "trim");
        $this->form_validation->set_rules("app_password", "Gmail App Password", "trim");
    
        // Upload config
        $config['upload_path'] = './images/';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
        $this->load->library('upload', $config);
    
        $file_uploaded = (isset($_FILES['userfile']['name']) && $_FILES['userfile']['error'] !== UPLOAD_ERR_NO_FILE);
    
        if ($this->form_validation->run() == FALSE) {
            $data = $this->storeinfo->getStoreInfo();
            $data["paystack_public_key"] = $this->secrets->getPaystackPublicKey();
            $data["paystack_secret_key"] = $this->secrets->getPaystackSecretKey();
            $data["app_password"] = $this->secrets->getAppPassword();
            
            ;
        
            $this->load->view("updates/storeFeatures", $data);
        } else {
            // Validation passed
            $val = array();
            $val["id"] = $this->input->post("id");
            $val["name"] = $this->input->post("name");
            $val["email"] = $this->input->post("email");
            $val["information"] = $this->input->post("information");
            $val["welcome_text"] = $this->input->post("welcome_text");
            $val["welcome_quote"] = $this->input->post("welcome_quote");
            $val["address"] = $this->input->post("address");
            $val["googlemap"] = $this->input->post("googlemap");
            $val["telephone"] = $this->input->post("telephone");
            $val["facebook"] = $this->input->post("facebook");
            $val["whatsapp"] = $this->input->post("whatsapp");
            $val["youtube"] = $this->input->post("youtube");
            $val["instagram"] = $this->input->post("instagram");
            $val["open_hours"] = $this->input->post("open_hours");
            $val["colour"] = $this->input->post("colour");
            $val["app_password"] = $this->input->post("app_password");
            //$val["app_password"] = $this->encryption->encrypt($this->input->post("app_password"));
    
            // --- Handle Core Values as JSON ---
            $core_values_data = [];
            for ($i = 1; $i <= 6; $i++) {
                $core_values_data['value_title_' . $i] = $this->input->post("value_title_" . $i);
                $core_values_data['value_text_' . $i] = $this->input->post("value_text_" . $i);
            }
            $val["core_values"] = json_encode($core_values_data);
    
            // --- Handle Services as JSON ---
            $services_data = [];
            for ($i = 1; $i <= 3; $i++) {
                $services_data['service' . $i] = $this->input->post("service" . $i);
            }
            $val["services"] = json_encode($services_data);
    
            // --- Handle Special Offer as JSON ---
            $special_offer_data = [
                'spe_desc' => $this->input->post("spe_desc"),
                'spe_link' => $this->input->post("spe_link"),
                'spe_ends_at' => $this->input->post("spe_ends_at")
            ];
            $val["special_offer"] = json_encode($special_offer_data);
    
            // --- NEW: Handle Meta Keywords as JSON ---
            $meta_keywords_data = [
                'meta_key_1' => $this->input->post("meta_key_1"),
                'meta_key_2' => $this->input->post("meta_key_2"),
                'meta_key_3' => $this->input->post("meta_key_3")
            ];
            $val["meta_keywords"] = json_encode($meta_keywords_data);
            

            $acct_details = [
                'acct_name' => $this->input->post("acct_name"),
                'acct_num' => $this->input->post("acct_num"),
                'acct_bank' => $this->input->post("acct_bank")
            ];
            $val["acct_details"] = json_encode($acct_details);
    

            // --- NEW: Handle Paystack Keys as JSON ---
            $paystack_keys_data = [
                'public_key' =>$this->input->post("public_key"),
                'private_key' => $this->input->post("private_key")
            ];
            // $paystack_keys_data = [
            //     'public_key' => $this->encryption->encrypt($this->input->post("public_key")),
            //     'private_key' => $this->encryption->encrypt($this->input->post("private_key"))
            // ];
            $val["paystack_keys"] = json_encode($paystack_keys_data);
    
            if ($file_uploaded) {
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $data = $this->storeinfo->getStoreInfo();
                    $data['error'] = $error;
                    
                    ;                
                    $this->load->view('updates/storeFeatures', $data);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $val['logo'] = $upload_data["file_name"];
                }
            }
    
            $result = $this->storeinfo->updateStoreInfo($val);
    
            if ($result) {
                $this->session->set_flashdata('success_message', 'Store Information Updated Successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Error Updating Store Information. Please try again.');
            }
    
            redirect('welcome/updatestoreinfo');
        }
    }    

    
    public function logout() {
        $this->session->unset_userdata("admin_pass");
        redirect("welcome");
    }

}

