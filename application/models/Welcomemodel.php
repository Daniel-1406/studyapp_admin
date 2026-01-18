<?php

class Welcomemodel extends CI_Model {

    
    function getschoolsview() {
        $query = $this->db->query("SELECT * FROM schools WHERE deleted='f' ORDER BY id DESC");
        $head = "<th>N/A</th><th>SCHOOLS</th><th>EDIT</th><th>DELETE</th>";
        $body = "";
        $db_content["head"] = "";
        $db_content["body"] = "";
        $db_content["productCount"] = 0;

        if($query->num_rows() > 0){
            $x = 1;
            foreach ($query->result() as $row) {
                $edit_btn = "<button type='button' class='btn btn-info btn-sm edit-school' 
                            data-id='$row->id' data-name='$row->name'>
                            <i class='fas fa-pencil-alt'></i> Edit
                            </button>";
                
                $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-school' 
                              data-id='$row->id' data-name='$row->name'>
                              <i class='fas fa-trash'></i> Delete
                              </button>";
                
                $body .= "<tr>
                            <td>$x</td>
                            <td>$row->name</td>
                            <td>$edit_btn</td>
                            <td>$delete_btn</td>
                          </tr>";
                $x++;
            }
            $db_content["head"] = $head;
            $db_content["body"] = $body;
            $db_content["productCount"] = $x - 1;
        }
        
        return $db_content;
    }
    
    function addschool($school_name) {
        $data["name"] = $school_name;
        
        if ($this->db->insert("schools", $data)) {
            $school_id = $this->db->insert_id();
            return array(
                'status' => 'success',
                'message' => 'School added successfully!',
                'school_id' => $school_id,
                'school_name' => $school_name
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error adding school!'
            );
        }
    }
    
    function getschoolbyid($id) {
        $query = $this->db->get_where('schools', array('id' => $id, 'deleted' => 'f'));
        return $query->row();
    }
    
    function updateschool($id, $school_name) {
        $data = array('name' => $school_name);
        $this->db->where('id', $id);
        
        if ($this->db->update('schools', $data)) {
            return array(
                'status' => 'success',
                'message' => 'School updated successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error updating school!'
            );
        }
    }
    
    function deleteschool($id) {
        // Soft delete by setting deleted flag
        $data = array('deleted' => 't');
        $this->db->where('id', $id);
        
        if ($this->db->update('schools', $data)) {
            return array(
                'status' => 'success',
                'message' => 'School deleted successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error deleting school!'
            );
        }
    }
    function getschools() {
        return $this->db->query("SELECT * FROM schools WHERE deleted='f' ORDER BY name ASC")->result();
    }
    // Make sure these model methods exist in your model
function getschoolsfordropdown() {
    $this->db->select('id, name');
    $this->db->where('deleted', 'f');
    $this->db->order_by('name', 'ASC');
    $query = $this->db->get('schools');
    
    $schools = array();
    foreach ($query->result() as $row) {
        $schools[$row->id] = $row->name;
    }
    return $schools;
}

function getdepartmentsfordropdown($school_id = null) {
    $this->db->select('id, name');
    $this->db->where('deleted', 'f');
    
    if ($school_id) {
        $this->db->where('sch_id', $school_id);
    }
    
    $this->db->order_by('name', 'ASC');
    $query = $this->db->get('departments');
    
    $departments = array();
    foreach ($query->result() as $row) {
        $departments[$row->id] = $row->name;
    }
    return $departments;
}

function getsemesters() {
    return array(
        '1' => 'Semester 1',
        '2' => 'Semester 2',
        '3' => 'Semester 3',
        '4' => 'Semester 4',
        '5' => 'Semester 5',
        '6' => 'Semester 6',
        '7' => 'Semester 7',
        '8' => 'Semester 8'
    );
}
    // ========== DEPARTMENTS FUNCTIONS ==========
    
    function getdepartmentsview() {
        $query = $this->db->query("
            SELECT d.*, s.name as school_name 
            FROM departments d 
            LEFT JOIN schools s ON d.sch_id = s.id 
            WHERE d.deleted='f' 
            ORDER BY d.id DESC
        ");
        
        $head = "<th>N/A</th><th>DEPARTMENT</th><th>SCHOOL</th><th>EDIT</th><th>DELETE</th>";
        $body = "";
        $db_content["head"] = "";
        $db_content["body"] = "";
        $db_content["departmentCount"] = 0;

        if($query->num_rows() > 0){
            $x = 1;
            foreach ($query->result() as $row) {
                $edit_btn = "<button type='button' class='btn btn-info btn-sm edit-department' 
                            data-id='$row->id' data-name='$row->name' data-sch_id='$row->sch_id'>
                            <i class='fas fa-pencil-alt'></i> Edit
                            </button>";
                
                $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-department' 
                              data-id='$row->id' data-name='$row->name'>
                              <i class='fas fa-trash'></i> Delete
                              </button>";
                
                $body .= "<tr>
                            <td>$x</td>
                            <td>$row->name</td>
                            <td>$row->school_name</td>
                            <td>$edit_btn</td>
                            <td>$delete_btn</td>
                          </tr>";
                $x++;
            }
            $db_content["head"] = $head;
            $db_content["body"] = $body;
            $db_content["departmentCount"] = $x - 1;
        }
        
        return $db_content;
    }
    
    function getdepartments() {
        return $this->db->query("
            SELECT d.*, s.name as school_name 
            FROM departments d 
            LEFT JOIN schools s ON d.sch_id = s.id 
            WHERE d.deleted='f' 
            ORDER BY d.id DESC
        ")->result();
    }
    
    function adddepartment($department_name, $school_id) {
        $data = array(
            "name" => $department_name,
            "sch_id" => $school_id
        );
        
        if ($this->db->insert("departments", $data)) {
            $dept_id = $this->db->insert_id();
            return array(
                'status' => 'success',
                'message' => 'Department added successfully!',
                'dept_id' => $dept_id,
                'dept_name' => $department_name
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error adding department!'
            );
        }
    }
    
    function getdepartmentbyid($id) {
        $query = $this->db->get_where('departments', array('id' => $id, 'deleted' => 'f'));
        return $query->row();
    }
    
    function updatedepartment($id, $department_name, $school_id) {
        $data = array(
            'name' => $department_name,
            'sch_id' => $school_id
        );
        $this->db->where('id', $id);
        
        if ($this->db->update('departments', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Department updated successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error updating department!'
            );
        }
    }
    
    function deletedepartment($id) {
        $data = array('deleted' => 't');
        $this->db->where('id', $id);
        
        if ($this->db->update('departments', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Department deleted successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error deleting department!'
            );
        }
    }
  
    
    // function getdepartmentsbyschool($school_id) {
    //     $query = $this->db->query("
    //         SELECT id, name 
    //         FROM departments 
    //         WHERE sch_id = ? AND deleted = 'f' 
    //         ORDER BY name ASC
    //     ", array($school_id));
        
    //     return $query->result();
    // }
    
    // ========== COURSES FUNCTIONS ==========
    
    function getcoursesview() {
        $query = $this->db->query("
            SELECT c.*, s.name as school_name, d.name as department_name 
            FROM courses c 
            LEFT JOIN schools s ON c.sch_id = s.id 
            LEFT JOIN departments d ON c.dept_id = d.id 
            WHERE c.deleted='f' 
            ORDER BY c.sch_id, c.dept_id, c.semester, c.code
        ");
        
        $head = "<th>N/A</th><th>CODE</th><th>TITLE</th><th>SEMESTER</th><th>SCHOOL</th><th>DEPARTMENT</th><th>EDIT</th><th>DELETE</th>";
        $body = "";
        $db_content["head"] = "";
        $db_content["body"] = "";
        $db_content["courseCount"] = 0;
    
        if($query->num_rows() > 0){
            $x = 1;
            foreach ($query->result() as $row) {
                $edit_btn = "<button type='button' class='btn btn-info btn-sm edit-course' 
                            data-id='$row->id' 
                            data-code='$row->code'
                            data-title='$row->title'
                            data-semester='$row->semester'
                            data-sch_id='$row->sch_id'
                            data-dept_id='$row->dept_id'>
                            <i class='fas fa-pencil-alt'></i> Edit
                            </button>";
                
                $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-course' 
                              data-id='$row->id' 
                              data-title='$row->title'>
                              <i class='fas fa-trash'></i> Delete
                              </button>";
                
                $body .= "<tr>
                            <td>$x</td>
                            <td><strong>$row->code</strong></td>
                            <td>$row->title</td>
                            <td><span class='badge bg-primary'>$row->semester</span></td>
                            <td>$row->school_name</td>
                            <td>$row->department_name</td>
                            <td>$edit_btn</td>
                            <td>$delete_btn</td>
                          </tr>";
                $x++;
            }
            $db_content["head"] = $head;
            $db_content["body"] = $body;
            $db_content["courseCount"] = $x - 1;
        }
        
        return $db_content;
    }
    
    function getcourses() {
        return $this->db->query("
            SELECT c.*, s.name as school_name, d.name as department_name 
            FROM courses c 
            LEFT JOIN schools s ON c.sch_id = s.id 
            LEFT JOIN departments d ON c.dept_id = d.id 
            WHERE c.deleted='f' 
            ORDER BY c.id DESC
        ")->result();
    }
    
    function addcourse($course_data) {
        $data = array(
            "code" => $course_data['code'],
            "title" => $course_data['title'],
            "semester" => $course_data['semester'],
            "sch_id" => $course_data['sch_id'],
            "dept_id" => $course_data['dept_id']
        );
        
        if ($this->db->insert("courses", $data)) {
            $course_id = $this->db->insert_id();
            return array(
                'status' => 'success',
                'message' => 'Course added successfully!',
                'course_id' => $course_id,
                'course_title' => $course_data['title']
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error adding course!'
            );
        }
    }
    
    function getcoursebyid($id) {
        $query = $this->db->get_where('courses', array('id' => $id, 'deleted' => 'f'));
        return $query->row();
    }
    
    function updatecourse($id, $course_data) {
        $data = array(
            'code' => $course_data['code'],
            'title' => $course_data['title'],
            'semester' => $course_data['semester'],
            'sch_id' => $course_data['sch_id'],
            'dept_id' => $course_data['dept_id']
        );
        $this->db->where('id', $id);
        
        if ($this->db->update('courses', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Course updated successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error updating course!'
            );
        }
    }
    
    function deletecourse($id) {
        $data = array('deleted' => 't');
        $this->db->where('id', $id);
        
        if ($this->db->update('courses', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Course deleted successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error deleting course!'
            );
        }
    }
    
    function checkcoursecode($code, $exclude_id = null) {
        $this->db->where('code', $code);
        $this->db->where('deleted', 'f');
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('courses');
        return $query->num_rows() > 0;
    }
    
  

    function getcoursesfordropdown($school_id = null, $dept_id = null) {
        $this->db->select('c.id, c.code, c.title, s.name as school_name, d.name as dept_name');
        $this->db->from('courses c');
        $this->db->join('schools s', 'c.sch_id = s.id', 'left');
        $this->db->join('departments d', 'c.dept_id = d.id', 'left');
        $this->db->where('c.deleted', 'f');
        
        if ($school_id) {
            $this->db->where('c.sch_id', $school_id);
        }
        
        if ($dept_id) {
            $this->db->where('c.dept_id', $dept_id);
        }
        
        $this->db->order_by('s.name, d.name, c.code, c.title', 'ASC');
        $query = $this->db->get();
        
        $courses = array();
        foreach ($query->result() as $row) {
            $courses[$row->id] = $row->code . ' - ' . $row->title . ' (' . $row->school_name . ' - ' . $row->dept_name . ')';
        }
        return $courses;
    }
    
    function getcoursesbyfilters($school_id = null, $dept_id = null) {
        $this->db->select('c.id, c.code, c.title, s.name as school_name, d.name as dept_name');
        $this->db->from('courses c');
        $this->db->join('schools s', 'c.sch_id = s.id', 'left');
        $this->db->join('departments d', 'c.dept_id = d.id', 'left');
        $this->db->where('c.deleted', 'f');
        
        if ($school_id) {
            $this->db->where('c.sch_id', $school_id);
        }
        
        if ($dept_id) {
            $this->db->where('c.dept_id', $dept_id);
        }
        
        $this->db->order_by('s.name, d.name, c.code, c.title', 'ASC');
        return $this->db->get()->result();
    }
    
    // ========== TOPICS FUNCTIONS ==========
    
    function gettopicsview($school_id = null, $dept_id = null, $course_id = null) {
        $this->db->select('t.*, c.code as course_code, c.title as course_title, 
                          s.name as school_name, d.name as dept_name');
        $this->db->from('topics t');
        $this->db->join('courses c', 't.crse_id = c.id', 'left');
        $this->db->join('schools s', 'c.sch_id = s.id', 'left');
        $this->db->join('departments d', 'c.dept_id = d.id', 'left');
        $this->db->where('t.deleted', 'f');
        
        // Apply filters if provided
        if ($school_id) {
            $this->db->where('c.sch_id', $school_id);
        }
        
        if ($dept_id) {
            $this->db->where('c.dept_id', $dept_id);
        }
        
        if ($course_id) {
            $this->db->where('t.crse_id', $course_id);
        }
        
        $this->db->order_by('s.name, d.name, c.code, t.name', 'ASC');
        $query = $this->db->get();
        
        $head = "<th>N/A</th><th>TOPIC NAME</th><th>COURSE</th><th>SCHOOL</th><th>DEPARTMENT</th><th>EDIT</th><th>DELETE</th>";
        $body = "";
        $db_content["head"] = "";
        $db_content["body"] = "";
        $db_content["topicCount"] = 0;

        if($query->num_rows() > 0){
            $x = 1;
            foreach ($query->result() as $row) {
                $edit_btn = "<button type='button' class='btn btn-info btn-sm edit-topic' 
                            data-id='$row->id' 
                            data-name='$row->name'
                            data-crse_id='$row->crse_id'
                            data-school_id='" . ($row->school_name ? '1' : '') . "'
                            data-dept_id='" . ($row->dept_name ? '1' : '') . "'>
                            <i class='fas fa-pencil-alt'></i> Edit
                            </button>";
                
                $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-topic' 
                              data-id='$row->id' 
                              data-name='$row->name'>
                              <i class='fas fa-trash'></i> Delete
                              </button>";
                
                $course_info = $row->course_code ? $row->course_code . ' - ' . $row->course_title : 'N/A';
                
                $body .= "<tr>
                            <td>$x</td>
                            <td>$row->name</td>
                            <td>$course_info</td>
                            <td>$row->school_name</td>
                            <td>$row->dept_name</td>
                            <td>$edit_btn</td>
                            <td>$delete_btn</td>
                          </tr>";
                $x++;
            }
            $db_content["head"] = $head;
            $db_content["body"] = $body;
            $db_content["topicCount"] = $x - 1;
        }
        
        return $db_content;
    }
    
    function gettopics() {
        return $this->db->query("
            SELECT t.*, c.code as course_code, c.title as course_title, 
                   s.name as school_name, d.name as dept_name 
            FROM topics t 
            LEFT JOIN courses c ON t.crse_id = c.id 
            LEFT JOIN schools s ON c.sch_id = s.id 
            LEFT JOIN departments d ON c.dept_id = d.id 
            WHERE t.deleted='f' 
            ORDER BY t.id DESC
        ")->result();
    }
    
    function addtopic($topic_name, $course_id) {
        $data = array(
            "name" => $topic_name,
            "crse_id" => $course_id
        );
        
        if ($this->db->insert("topics", $data)) {
            $topic_id = $this->db->insert_id();
            return array(
                'status' => 'success',
                'message' => 'Topic added successfully!',
                'topic_id' => $topic_id,
                'topic_name' => $topic_name
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error adding topic!'
            );
        }
    }
    
    function gettopicbyid($id) {
        $query = $this->db->get_where('topics', array('id' => $id, 'deleted' => 'f'));
        return $query->row();
    }
    
    function updatetopic($id, $topic_name, $course_id) {
        $data = array(
            'name' => $topic_name,
            'crse_id' => $course_id
        );
        $this->db->where('id', $id);
        
        if ($this->db->update('topics', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Topic updated successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error updating topic!'
            );
        }
    }
    
    function deletetopic($id) {
        $data = array('deleted' => 't');
        $this->db->where('id', $id);
        
        if ($this->db->update('topics', $data)) {
            return array(
                'status' => 'success',
                'message' => 'Topic deleted successfully!'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Error deleting topic!'
            );
        }
    }
    
    function checktopicname($topic_name, $course_id, $exclude_id = null) {
        $this->db->where('name', $topic_name);
        $this->db->where('crse_id', $course_id);
        $this->db->where('deleted', 'f');
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('topics');
        return $query->num_rows() > 0;
    }
    
    function gettopicdetails($topic_id) {
        $this->db->select('t.*, c.code as course_code, c.title as course_title, 
                          c.sch_id, c.dept_id, s.name as school_name, d.name as dept_name');
        $this->db->from('topics t');
        $this->db->join('courses c', 't.crse_id = c.id', 'left');
        $this->db->join('schools s', 'c.sch_id = s.id', 'left');
        $this->db->join('departments d', 'c.dept_id = d.id', 'left');
        $this->db->where('t.id', $topic_id);
        $this->db->where('t.deleted', 'f');
        
        return $this->db->get()->row();
    }

      // ========== QUESTIONS FUNCTIONS ==========
 // ========== QUESTIONS VIEW FUNCTIONS ==========

function getquestionsview($school_id = null, $dept_id = null, $course_id = null, $topic_id = null, $year = null, $answer = null) {
    $this->db->select('q.*, c.code as course_code, c.title as course_title, 
                      t.name as topic_name, s.name as school_name, d.name as dept_name');
    $this->db->from('questions q');
    $this->db->join('courses c', 'q.crse_id = c.id', 'left');
    $this->db->join('topics t', 'q.topic_id = t.id', 'left');
    $this->db->join('schools s', 'c.sch_id = s.id', 'left');
    $this->db->join('departments d', 'c.dept_id = d.id', 'left');
    $this->db->where('q.deleted', 'f');
    
    // Apply filters if provided
    if ($school_id) {
        $this->db->where('c.sch_id', $school_id);
    }
    
    if ($dept_id) {
        $this->db->where('c.dept_id', $dept_id);
    }
    
    if ($course_id) {
        $this->db->where('q.crse_id', $course_id);
    }
    
    if ($topic_id) {
        $this->db->where('q.topic_id', $topic_id);
    }
    
    if ($year === 'null') {
        $this->db->where('q.year IS NULL', null, false);
    } elseif ($year && $year !== 'null') {
        $this->db->where('q.year', $year);
    }
    
    if ($answer) {
        $this->db->where('q.ans', strtolower($answer));
    }
    
    // FIXED: Changed from incorrect syntax to proper ORDER BY
    $this->db->order_by('s.name ASC, d.name ASC, c.code ASC, t.name ASC, q.year DESC, q.id ASC');
    $query = $this->db->get();
    
    $head = "<th>N/A</th><th>QUESTION</th><th>COURSE</th><th>TOPIC</th><th>SCHOOL</th><th>DEPARTMENT</th><th>YEAR</th><th>ANSWER</th><th>EDIT</th><th>DELETE</th>";
    $body = "";
    $db_content["head"] = "";
    $db_content["body"] = "";
    $db_content["questionCount"] = 0;

    if($query->num_rows() > 0){
        $x = 1;
        foreach ($query->result() as $row) {
            // Initialize variables to prevent undefined errors
            $truncated_question = "";
            $answer_label = "";
            $edit_btn = "";
            $delete_btn = "";
            
            // Truncate question text for display
            $plain_text = strip_tags($row->qst);
            $truncated_question = mb_substr($plain_text, 0, 80, 'UTF-8');
            if (strlen($plain_text) > 80) {
                $truncated_question .= '...';
            }
            
            // Answer badge
            switch(strtoupper($row->ans)) {
                case 'A':
                    $answer_label = '<span class="badge bg-success">A</span>';
                    break;
                case 'B':
                    $answer_label = '<span class="badge bg-primary">B</span>';
                    break;
                case 'C':
                    $answer_label = '<span class="badge bg-warning">C</span>';
                    break;
                case 'D':
                    $answer_label = '<span class="badge bg-danger">D</span>';
                    break;
                default:
                    $answer_label = '<span class="badge bg-secondary">N/A</span>';
            }
            
            // Year display
            $year_display = $row->year ? $row->year : '<span class="text-muted">N/A</span>';
            
            // Action buttons
            $edit_btn = "<button type='button' class='btn btn-warning btn-sm edit-question' 
                        data-id='$row->id'>
                        <i class='fas fa-edit'></i>
                        </button>";
            
            $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-question' 
                          data-id='$row->id' 
                          data-question='$truncated_question'>
                          <i class='fas fa-trash'></i>
                          </button>";
            
            $body .= "<tr>
                        <td>$x</td>
                        <td><strong>$truncated_question</strong></td>
                        <td><strong>$row->course_code</strong><br><small>$row->course_title</small></td>
                        <td>" . ($row->topic_name ? $row->topic_name : '<span class="text-muted">N/A</span>') . "</td>
                        <td>$row->school_name</td>
                        <td>$row->dept_name</td>
                        <td>$year_display</td>
                        <td>$answer_label</td>
                        <td>$edit_btn</td>
                        <td>$delete_btn</td>
                      </tr>";
            $x++;
        }
        $db_content["head"] = $head;
        $db_content["body"] = $body;
        $db_content["questionCount"] = $x - 1;
    } else {
        // Initialize variables even when no results to prevent errors
        $db_content["head"] = $head;
        $db_content["body"] = "<tr><td colspan='10' class='text-center'>No questions found</td></tr>";
        $db_content["questionCount"] = 0;
    }
    
    return $db_content;
}
    
function getquestions() {
    return $this->db->query("
        SELECT q.*, c.code as course_code, c.title as course_title, 
               t.name as topic_name, s.name as school_name, d.name as dept_name 
        FROM questions q 
        LEFT JOIN courses c ON q.crse_id = c.id 
        LEFT JOIN topics t ON q.topic_id = t.id 
        LEFT JOIN schools s ON c.sch_id = s.id 
        LEFT JOIN departments d ON c.dept_id = d.id 
        WHERE q.deleted='f' 
        ORDER BY q.id DESC
    ")->result();
}

function addquestion($question_data) {
    // Sanitize HTML content
    $question_data['qst'] = $this->sanitize_html($question_data['qst']);
    $question_data['option_a'] = $this->sanitize_html($question_data['option_a']);
    $question_data['option_b'] = $this->sanitize_html($question_data['option_b']);
    $question_data['option_c'] = $this->sanitize_html($question_data['option_c']);
    $question_data['option_d'] = $this->sanitize_html($question_data['option_d']);
    $question_data['instruction'] = isset($question_data['instruction']) ? $this->sanitize_html($question_data['instruction']) : '';
    $question_data['explanation'] = isset($question_data['explanation']) ? $this->sanitize_html($question_data['explanation']) : '';
    
    // Add date added
    $question_data['date_added'] = date('Y-m-d H:i:s');
    
    if ($this->db->insert("questions", $question_data)) {
        $question_id = $this->db->insert_id();
        return array(
            'status' => 'success',
            'message' => 'Question added successfully!',
            'question_id' => $question_id
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error adding question!'
        );
    }
}

function getquestionbyid($id) {
    $this->db->select('q.*, c.code as course_code, c.title as course_title, 
                      t.name as topic_name, c.sch_id, c.dept_id,
                      s.name as school_name, d.name as dept_name');
    $this->db->from('questions q');
    $this->db->join('courses c', 'q.crse_id = c.id', 'left');
    $this->db->join('topics t', 'q.topic_id = t.id', 'left');
    $this->db->join('schools s', 'c.sch_id = s.id', 'left');
    $this->db->join('departments d', 'c.dept_id = d.id', 'left');
    $this->db->where('q.id', $id);
    $this->db->where('q.deleted', 'f');
    
    return $this->db->get()->row();
}

function updatequestion($id, $question_data) {
    // Sanitize HTML content
    $question_data['qst'] = $this->sanitize_html($question_data['qst']);
    $question_data['option_a'] = $this->sanitize_html($question_data['option_a']);
    $question_data['option_b'] = $this->sanitize_html($question_data['option_b']);
    $question_data['option_c'] = $this->sanitize_html($question_data['option_c']);
    $question_data['option_d'] = $this->sanitize_html($question_data['option_d']);
    $question_data['instruction'] = isset($question_data['instruction']) ? $this->sanitize_html($question_data['instruction']) : '';
    $question_data['explanation'] = isset($question_data['explanation']) ? $this->sanitize_html($question_data['explanation']) : '';
    
    $this->db->where('id', $id);
    
    if ($this->db->update('questions', $question_data)) {
        return array(
            'status' => 'success',
            'message' => 'Question updated successfully!'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error updating question!'
        );
    }
}

function deletequestion($id) {
    $data = array('deleted' => 't');
    $this->db->where('id', $id);
    
    if ($this->db->update('questions', $data)) {
        return array(
            'status' => 'success',
            'message' => 'Question deleted successfully!'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error deleting question!'
        );
    }
}

function gettopicsbycourse($course_id) {
    $this->db->select('id, name');
    $this->db->where('crse_id', $course_id);
    $this->db->where('deleted', 'f');
    $this->db->order_by('name', 'ASC');
    $query = $this->db->get('topics');
    
    $topics = array();
    foreach ($query->result() as $row) {
        $topics[$row->id] = $row->name;
    }
    return $topics;
}

function gettopicslistbycourse($course_id) {
    $this->db->select('id, name');
    $this->db->where('crse_id', $course_id);
    $this->db->where('deleted', 'f');
    $this->db->order_by('name', 'ASC');
    return $this->db->get('topics')->result();
}

function getdepartmentsbyschool($school_id) {
    $this->db->select('id, name');
    $this->db->where('sch_id', $school_id);
    $this->db->where('deleted', 'f');
    $this->db->order_by('name', 'ASC');
    $query = $this->db->get('departments');
    
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return array(); // Return empty array if no departments
    }
}

function sanitize_html($html) {
    if (empty($html)) return '';
    
    // Basic HTML sanitization - allow safe tags for WYSIWYG content
    $allowed_tags = '<h1><h2><h3><h4><h5><h6><p><br><div><span><strong><b><em><i><u>';
    $allowed_tags .= '<ul><ol><li><table><tr><td><th><thead><tbody><tfoot>';
    $allowed_tags .= '<a><img><iframe><video><audio><source>';
    $allowed_tags .= '<sup><sub><code><pre><blockquote><hr>';
    $allowed_tags .= '<math><mi><mo><mn><msup><msub><mfrac><msqrt><mroot><mrow><mtable>';
    $allowed_tags .= '<mtr><mtd><munder><mover><mtext><menclose><merror><mpadded><mphantom>';
    $allowed_tags .= '<mspace><ms><maction><maligngroup><malignmark><mlabeledtr><mlongdiv>';
    
    // Clean HTML
    $html = strip_tags($html, $allowed_tags);
    
    // Remove dangerous attributes but keep safe ones
    $html = preg_replace('/ on\w+="[^"]*"/i', '', $html);
    $html = preg_replace('/ javascript:/i', '', $html);
    $html = preg_replace('/ data:/i', '', $html);
    
    return trim($html);
}

function getansweroptions() {
    return array(
        'a' => 'Option A',
        'b' => 'Option B',
        'c' => 'Option C',
        'd' => 'Option D'
    );
}

    // ========== BATCH UPLOAD FUNCTIONS ==========
    
    function addbatchquestions($questions_data) {
        // Start transaction
        $this->db->trans_start();
        
        $success_count = 0;
        $failed_count = 0;
        $failed_records = array();
        
        foreach ($questions_data as $index => $question) {
            try {
                // Check if question already exists for this course/topic/year
                $existing_conditions = array(
                    'crse_id' => $question['course_id'],
                    'topic_id' => $question['topic_id'],
                    'qst' => $question['question']
                );
                
                // Add year to conditions if provided
                if (!empty($question['year'])) {
                    $existing_conditions['year'] = $question['year'];
                }
                
                $existing = $this->db->get_where('questions', $existing_conditions)->row();
                
                if ($existing) {
                    throw new Exception("Question already exists" . (!empty($question['year']) ? " for year " . $question['year'] : ""));
                }
                
                // Sanitize and prepare data
                $sanitized_data = array(
                    'crse_id' => $question['course_id'],
                    'topic_id' => $question['topic_id'],
                    'qst' => $this->sanitize_html($question['question']),
                    'option_a' => $this->sanitize_html($question['option_a']),
                    'option_b' => $this->sanitize_html($question['option_b']),
                    'option_c' => $this->sanitize_html($question['option_c']),
                    'option_d' => $this->sanitize_html($question['option_d']),
                    'ans' => strtoupper(trim($question['correct_answer'])), // Changed to uppercase for consistency
                    'instruction' => isset($question['instruction']) ? $this->sanitize_html($question['instruction']) : '',
                    'explanation' => isset($question['explanation']) ? $this->sanitize_html($question['explanation']) : '',
                    'date_added' => date('Y-m-d H:i:s')
                );
                
                // Add year if provided and valid
                if (!empty($question['year']) && is_numeric($question['year'])) {
                    $year = intval($question['year']);
                    if ($year >= 1900 && $year <= 2100) {
                        $sanitized_data['year'] = $year;
                    }
                }
                
                // Insert question
                $this->db->insert("questions", $sanitized_data);
                $insert_id = $this->db->insert_id();
                
                if ($insert_id) {
                    $success_count++;
                } else {
                    throw new Exception("Database insertion failed");
                }
                
            } catch (Exception $e) {
                $failed_count++;
                $failed_records[] = array(
                    'row' => $index + 1,
                    'question' => substr($question['question'], 0, 100) . '...',
                    'error' => $e->getMessage()
                );
            }
        }
        
        // Complete transaction
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'status' => 'error',
                'message' => 'Transaction failed during batch upload',
                'success_count' => 0,
                'failed_count' => count($questions_data),
                'failed_records' => $failed_records
            );
        }
        
        return array(
            'status' => 'success',
            'message' => 'Batch upload completed successfully',
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'failed_records' => $failed_records
        );
    }

   function validatebatchdata($questions_data) {
    $errors = array();
    $validated_data = array();
    
    // Get current year for validation range
    $current_year = date('Y');
    
    foreach ($questions_data as $index => $row) {
        $row_num = $index + 1;
        $row_errors = array();
        
        // Check required fields
        $required_fields = ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'];
        foreach ($required_fields as $field) {
            if (!isset($row[$field]) || trim($row[$field]) === '') {
                $row_errors[] = "Missing required field: {$field}";
            }
        }
        
        // Validate answer
        if (isset($row['correct_answer']) && trim($row['correct_answer']) !== '') {
            $answer = strtoupper(trim($row['correct_answer']));
            if (!in_array($answer, ['A', 'B', 'C', 'D'])) {
                $row_errors[] = "Invalid answer. Must be A, B, C, or D. Got: {$answer}";
            }
        }
        
        // Validate year if provided
        if (isset($row['year']) && trim($row['year']) !== '') {
            $year = trim($row['year']);
            
            // Check if it's a valid year
            if (!preg_match('/^\d{4}$/', $year)) {
                $row_errors[] = "Invalid year format. Must be 4 digits (e.g., 2024)";
            } else {
                $year_int = intval($year);
                // Check if year is within reasonable range (e.g., 1900-2100)
                if ($year_int < 1900 || $year_int > 2100) {
                    $row_errors[] = "Year must be between 1900 and 2100. Got: {$year}";
                }
            }
        }
        
        // Check for double commas in question text
        if (isset($row['question']) && strpos($row['question'], ',,') !== false) {
            $row_errors[] = "Question text contains double commas (,,) which are reserved as field separators";
        }
        
        if (empty($row_errors)) {
            $validated_data[] = $row;
        } else {
            $errors[$row_num] = $row_errors;
        }
    }
    
    return array(
        'validated_data' => $validated_data,
        'errors' => $errors,
        'total_rows' => count($questions_data),
        'valid_rows' => count($validated_data),
        'invalid_rows' => count($errors)
    );
}
// Add this function to your model
function getyearsfordropdown($start_year = null, $end_year = null) {
    // Set default range if not provided
    if ($start_year === null) {
        $start_year = 2010;
    }
    if ($end_year === null) {
        $end_year = date('Y') + 1; // Include next year
    }
    
    $years = array();
    for ($year = $end_year; $year >= $start_year; $year--) {
        $years[$year] = $year;
    }
    
    return $years;
}
    function processuploadedfile($file_path) {
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        $questions_data = array();
        
        if ($extension === 'csv') {
            $questions_data = $this->processCSV($file_path);
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            $questions_data = $this->processExcel($file_path);
        } else {
            throw new Exception("Unsupported file format. Please upload CSV or Excel files.");
        }
        
        return $questions_data;
    }
    
    private function processCSV($file_path) {
        $questions = array();
        
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ",");
            
            // Normalize headers (trim, lowercase, replace spaces with underscores)
            $normalized_headers = array_map(function($header) {
                return trim(strtolower(str_replace(' ', '_', $header)));
            }, $headers);
            
            $row_count = 1;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row_count++;
                
                if (count($data) != count($normalized_headers)) {
                    continue; // Skip malformed rows
                }
                
                $row_data = array_combine($normalized_headers, $data);
                $questions[] = $row_data;
            }
            fclose($handle);
        }
        
        return $questions;
    }
    
    private function processExcel($file_path) {
        // Load PHPExcel library (you need to install it)
        $this->load->library('PHPExcel');
        
        $objPHPExcel = PHPExcel_IOFactory::load($file_path);
        $sheet = $objPHPExcel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        $questions = array();
        $headers = array();
        
        // Get headers from first row
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $sheet->getCell($col . '1')->getValue();
            $headers[] = trim(strtolower(str_replace(' ', '_', $cellValue)));
        }
        
        // Process data rows
        for ($row = 2; $row <= $highestRow; $row++) {
            $row_data = array();
            
            $colIndex = 0;
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                $row_data[$headers[$colIndex]] = $cellValue;
                $colIndex++;
            }
            
            // Check if row has any data
            if (!empty(array_filter($row_data))) {
                $questions[] = $row_data;
            }
        }
        
        return $questions;
    }
          // ========== NOTES FUNCTIONS ==========
    
   // ========== UPDATED NOTES FUNCTIONS ==========

function getnotesview($school_id = null, $dept_id = null, $course_id = null, $topic_id = null) {
    $this->db->select('n.*, c.code as course_code, c.title as course_title, 
                      t.name as topic_name, s.name as school_name, d.name as dept_name');
    $this->db->from('notes n');
    $this->db->join('courses c', 'n.crse_id = c.id', 'left');
    $this->db->join('topics t', 'n.topic_id = t.id', 'left');
    $this->db->join('schools s', 'c.sch_id = s.id', 'left');
    $this->db->join('departments d', 'c.dept_id = d.id', 'left');
    $this->db->where('n.deleted', 'f');
    
    // Apply filters if provided
    if ($school_id) {
        $this->db->where('c.sch_id', $school_id);
    }
    
    if ($dept_id) {
        $this->db->where('c.dept_id', $dept_id);
    }
    
    if ($course_id) {
        $this->db->where('n.crse_id', $course_id);
    }
    
    if ($topic_id) {
        $this->db->where('n.topic_id', $topic_id);
    }
    
    $this->db->order_by('s.name, d.name, c.code, t.name, n.title', 'ASC');
    $query = $this->db->get();
    
    $head = "<th>N/A</th><th>TITLE</th><th>COURSE</th><th>TOPIC</th><th>SCHOOL</th><th>DEPARTMENT</th><th>TYPE</th><th>PREVIEW</th><th>EDIT</th><th>DELETE</th>";
    $body = "";
    $db_content["head"] = "";
    $db_content["body"] = "";
    $db_content["noteCount"] = 0;

    if($query->num_rows() > 0){
        $x = 1;
        foreach ($query->result() as $row) {
            // Truncate note content for preview
            $plain_text = strip_tags($row->data);
            $preview = mb_substr($plain_text, 0, 80, 'UTF-8');
            if (strlen($plain_text) > 80) {
                $preview .= '...';
            }
            
            // File type badge
            $type_badge = '';
            switch($row->file_type) {
                case 'txt': $type_badge = '<span class="badge bg-secondary">TXT</span>'; break;
                case 'doc': $type_badge = '<span class="badge bg-primary">DOC</span>'; break;
                case 'docx': $type_badge = '<span class="badge bg-primary">DOCX</span>'; break;
                case 'pdf': $type_badge = '<span class="badge bg-danger">PDF</span>'; break;
                case 'editor': $type_badge = '<span class="badge bg-success">Editor</span>'; break;
                default: $type_badge = '<span class="badge bg-info">' . strtoupper($row->file_type) . '</span>';
            }
            
            $preview_btn = "<button type='button' class='btn btn-info btn-sm preview-note' 
                           data-id='$row->id'>
                           <i class='fas fa-eye'></i> 
                           </button>";
            
            $edit_btn = "<button type='button' class='btn btn-warning btn-sm edit-note' 
                        data-id='$row->id'>
                        <i class='fas fa-edit'></i>
                        </button>";
            
            $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-note' 
                          data-id='$row->id' 
                          data-title='$row->title'>
                          <i class='fas fa-trash'></i>
                          </button>";
            
            $body .= "<tr>
                        <td>$x</td>
                        <td><strong>$row->title</strong></td>
                        <td><strong>$row->course_code</strong><br><small>$row->course_title</small></td>
                        <td>" . ($row->topic_name ? $row->topic_name : '<span class="text-muted">N/A</span>') . "</td>
                        <td>$row->school_name</td>
                        <td>$row->dept_name</td>
                        <td>$type_badge</td>
                        <td>$preview_btn</td>
                        <td>$edit_btn</td>
                        <td>$delete_btn</td>
                      </tr>";
            $x++;
        }
        $db_content["head"] = $head;
        $db_content["body"] = $body;
        $db_content["noteCount"] = $x - 1;
    }
    
    return $db_content;
}

function addnote($note_data) {
    // Prepare data array
    $data = array(
        "title" => $this->sanitize_html($note_data['title']),
        "crse_id" => $note_data['course_id'],
        "topic_id" => !empty($note_data['topic_id']) ? $note_data['topic_id'] : null,
        "data" => $this->sanitize_html($note_data['content']),
        "file_type" => 'editor',
        "original_filename" => null,
        "date_added" => date('Y-m-d H:i:s')
    );
    
    if ($this->db->insert("notes", $data)) {
        $note_id = $this->db->insert_id();
        return array(
            'status' => 'success',
            'message' => 'Note added successfully!',
            'note_id' => $note_id
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error adding note!'
        );
    }
}

function addnotefromfile($note_data) {
    // Prepare data array
    $data = array(
        "title" => $this->sanitize_html($note_data['title']),
        "crse_id" => $note_data['course_id'],
        "topic_id" => !empty($note_data['topic_id']) ? $note_data['topic_id'] : null,
        "data" => $this->sanitize_html($note_data['content']),
        "file_type" => $note_data['file_type'],
        "original_filename" => $note_data['original_filename'],
        "date_added" => date('Y-m-d H:i:s')
    );
    
    if ($this->db->insert("notes", $data)) {
        $note_id = $this->db->insert_id();
        return array(
            'status' => 'success',
            'message' => 'Note uploaded from file successfully!',
            'note_id' => $note_id
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error uploading note from file!'
        );
    }
}

function getnotebyid($id) {
    $this->db->select('n.*, c.code as course_code, c.title as course_title, 
                      t.name as topic_name, c.sch_id, c.dept_id,
                      s.name as school_name, d.name as dept_name');
    $this->db->from('notes n');
    $this->db->join('courses c', 'n.crse_id = c.id', 'left');
    $this->db->join('topics t', 'n.topic_id = t.id', 'left');
    $this->db->join('schools s', 'c.sch_id = s.id', 'left');
    $this->db->join('departments d', 'c.dept_id = d.id', 'left');
    $this->db->where('n.id', $id);
    $this->db->where('n.deleted', 'f');
    
    return $this->db->get()->row();
}

function updatenote($id, $note_data) {
    $data = array(
        'title' => $this->sanitize_html($note_data['title']),
        'topic_id' => !empty($note_data['topic_id']) ? $note_data['topic_id'] : null,
        'data' => $this->sanitize_html($note_data['content']),
        'file_type' => isset($note_data['file_type']) ? $note_data['file_type'] : 'editor'
    );
    
    $this->db->where('id', $id);
    
    if ($this->db->update('notes', $data)) {
        return array(
            'status' => 'success',
            'message' => 'Note updated successfully!'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error updating note!'
        );
    }
}

// Add this new function to get topics for a course
function gettopicsbycoursefornotes($course_id) {
    $this->db->select('id, name');
    $this->db->where('crse_id', $course_id);
    $this->db->where('deleted', 'f');
    $this->db->order_by('name', 'ASC');
    return $this->db->get('topics')->result_array();
}

// Add this function to check for duplicate titles
function checknotetitle($title, $course_id, $exclude_id = null) {
    $this->db->where('title', $title);
    $this->db->where('crse_id', $course_id);
    $this->db->where('deleted', 'f');
    
    if ($exclude_id) {
        $this->db->where('id !=', $exclude_id);
    }
    
    $query = $this->db->get('notes');
    return $query->num_rows() > 0;
}

// ========== NEWS/UPDATES FUNCTIONS ==========

function getnewsview($type = null, $status = null) {
    $this->db->select('n.*');
    $this->db->from('news_updates n');
    $this->db->where('n.deleted', 'f');
    
    // Apply filters if provided
    if ($type) {
        $this->db->where('n.type', $type);
    }
    
    if ($status) {
        $this->db->where('n.status', $status);
    }
    
    $this->db->order_by('n.is_featured DESC, n.publish_date DESC, n.created_at DESC');
    $query = $this->db->get();
    
    $head = "<th>N/A</th><th>TITLE</th><th>TYPE</th><th>STATUS</th><th>PUBLISH DATE</th><th>VIEWS</th><th>FEATURED</th><th>ACTIONS</th>";
    $body = "";
    $db_content["head"] = "";
    $db_content["body"] = "";
    $db_content["news_count"] = 0;

    if($query->num_rows() > 0){
        $x = 1;
        foreach ($query->result() as $row) {
            // Status badge
            $status_badge = '';
            switch($row->status) {
                case 'draft': $status_badge = '<span class="badge bg-secondary">Draft</span>'; break;
                case 'published': $status_badge = '<span class="badge bg-success">Published</span>'; break;
                case 'archived': $status_badge = '<span class="badge bg-warning">Archived</span>'; break;
            }
            
            // Type badge
            $type_badge = '';
            switch($row->type) {
                case 'news': $type_badge = '<span class="badge bg-info">News</span>'; break;
                case 'update': $type_badge = '<span class="badge bg-primary">Update</span>'; break;
                case 'info': $type_badge = '<span class="badge bg-success">Info</span>'; break;
                case 'announcement': $type_badge = '<span class="badge bg-danger">Announcement</span>'; break;
            }
            
            // Featured badge
            $featured_badge = $row->is_featured ? '<span class="badge bg-warning"><i class="fas fa-star"></i> Featured</span>' : '';
            
            // Truncate title
            $truncated_title = strip_tags($row->title);
            if (strlen($truncated_title) > 50) {
                $truncated_title = substr($truncated_title, 0, 50) . '...';
            }
            
            // Action buttons
            $preview_btn = "<button type='button' class='btn btn-info btn-sm preview-news' 
                          data-id='$row->id'>
                          <i class='fas fa-eye'></i>
                          </button>";
            
            $edit_btn = "<button type='button' class='btn btn-warning btn-sm edit-news' 
                        data-id='$row->id'>
                        <i class='fas fa-edit'></i>
                        </button>";
            
            $delete_btn = "<button type='button' class='btn btn-danger btn-sm delete-news' 
                          data-id='$row->id' 
                          data-title='$row->title'>
                          <i class='fas fa-trash'></i>
                          </button>";
            
            // Format date
            $publish_date = $row->publish_date ? date('M d, Y', strtotime($row->publish_date)) : 'Not set';
            
            $body .= "<tr>
                        <td>$x</td>
                        <td><strong>$truncated_title</strong></td>
                        <td>$type_badge</td>
                        <td>$status_badge</td>
                        <td>$publish_date</td>
                        <td><span class='badge bg-secondary'>$row->views</span></td>
                        <td>$featured_badge</td>
                        <td>
                            <div class='btn-group'>
                                $preview_btn
                                $edit_btn
                                $delete_btn
                            </div>
                        </td>
                      </tr>";
            $x++;
        }
        $db_content["head"] = $head;
        $db_content["body"] = $body;
        $db_content["news_count"] = $x - 1;
    }
    
    return $db_content;
}

function getnewstypes() {
    return array(
        'news' => 'News',
        'update' => 'Update',
        'info' => 'General Info',
        'announcement' => 'Announcement'
    );
}

function getnewsstatuses() {
    return array(
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived'
    );
}

function addnews($news_data) {
    // Generate slug from title
    $slug = $this->generate_slug($news_data['title']);
    
    // Prepare data array
    $data = array(
        "title" => $this->sanitize_html($news_data['title']),
        "slug" => $slug,
        "content" => $this->sanitize_html($news_data['content'], true),
        "excerpt" => $this->sanitize_html($news_data['excerpt']),
        "type" => $news_data['type'],
        "status" => $news_data['status'],
        "publish_date" => $news_data['publish_date'] ? date('Y-m-d H:i:s', strtotime($news_data['publish_date'])) : null,
        "expiry_date" => $news_data['expiry_date'] ? date('Y-m-d H:i:s', strtotime($news_data['expiry_date'])) : null,
        "is_featured" => isset($news_data['is_featured']) ? ($news_data['is_featured'] ? 1 : 0) : 0,
        "meta_keywords" => $this->sanitize_html($news_data['meta_keywords']),
        "meta_description" => $this->sanitize_html($news_data['meta_description']),
        "author_id" => isset($news_data['author_id']) ? $news_data['author_id'] : 1,
        "created_at" => date('Y-m-d H:i:s')
    );
    
    // Handle featured image
    if (isset($news_data['featured_image']) && !empty($news_data['featured_image'])) {
        $data['featured_image'] = $news_data['featured_image'];
    }
    
    if ($this->db->insert("news_updates", $data)) {
        $news_id = $this->db->insert_id();
        return array(
            'status' => 'success',
            'message' => 'News/Update added successfully!',
            'news_id' => $news_id
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error adding news/update!'
        );
    }
}

function getnewsbyid($id) {
    $query = $this->db->get_where('news_updates', array('id' => $id, 'deleted' => 'f'));
    return $query->row();
}

function getnewsbyslug($slug) {
    $query = $this->db->get_where('news_updates', array('slug' => $slug, 'deleted' => 'f', 'status' => 'published'));
    return $query->row();
}

function updatenews($id, $news_data) {
    // Check if title changed, regenerate slug if needed
    $current = $this->getnewsbyid($id);
    if ($current->title != $news_data['title']) {
        $slug = $this->generate_slug($news_data['title']);
    } else {
        $slug = $current->slug;
    }
    
    $data = array(
        "title" => $this->sanitize_html($news_data['title']),
        "slug" => $slug,
        "content" => $this->sanitize_html($news_data['content'], true),
        "excerpt" => $this->sanitize_html($news_data['excerpt']),
        "type" => $news_data['type'],
        "status" => $news_data['status'],
        "publish_date" => $news_data['publish_date'] ? date('Y-m-d H:i:s', strtotime($news_data['publish_date'])) : null,
        "expiry_date" => $news_data['expiry_date'] ? date('Y-m-d H:i:s', strtotime($news_data['expiry_date'])) : null,
        "is_featured" => isset($news_data['is_featured']) ? ($news_data['is_featured'] ? 1 : 0) : 0,
        "meta_keywords" => $this->sanitize_html($news_data['meta_keywords']),
        "meta_description" => $this->sanitize_html($news_data['meta_description']),
        "updated_at" => date('Y-m-d H:i:s')
    );
    
    // Handle featured image if provided
    if (isset($news_data['featured_image']) && $news_data['featured_image'] !== '') {
        // Delete old image if exists
        if ($current->featured_image && file_exists('./uploads/news/' . $current->featured_image)) {
            unlink('./uploads/news/' . $current->featured_image);
        }
        $data['featured_image'] = $news_data['featured_image'];
    }
    
    $this->db->where('id', $id);
    
    if ($this->db->update('news_updates', $data)) {
        return array(
            'status' => 'success',
            'message' => 'News/Update updated successfully!'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error updating news/update!'
        );
    }
}

function deletenews($id) {
    // Soft delete
    $data = array('deleted' => 't');
    $this->db->where('id', $id);
    
    if ($this->db->update('news_updates', $data)) {
        return array(
            'status' => 'success',
            'message' => 'News/Update deleted successfully!'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error deleting news/update!'
        );
    }
}

function getfeaturednews($limit = 3) {
    $this->db->select('id, title, slug, excerpt, featured_image, publish_date, type');
    $this->db->where('deleted', 'f');
    $this->db->where('status', 'published');
    $this->db->where('is_featured', 1);
    $this->db->where('(publish_date IS NULL OR publish_date <= NOW())');
    $this->db->where('(expiry_date IS NULL OR expiry_date >= NOW())');
    $this->db->order_by('publish_date DESC, created_at DESC');
    $this->db->limit($limit);
    
    return $this->db->get('news_updates')->result();
}

function getrecentnews($limit = 5, $type = null) {
    $this->db->select('id, title, slug, excerpt, featured_image, publish_date, type, views');
    $this->db->where('deleted', 'f');
    $this->db->where('status', 'published');
    $this->db->where('(publish_date IS NULL OR publish_date <= NOW())');
    $this->db->where('(expiry_date IS NULL OR expiry_date >= NOW())');
    
    if ($type) {
        $this->db->where('type', $type);
    }
    
    $this->db->order_by('publish_date DESC, created_at DESC');
    $this->db->limit($limit);
    
    return $this->db->get('news_updates')->result();
}

function incrementviews($id) {
    $this->db->where('id', $id);
    $this->db->set('views', 'views+1', FALSE);
    $this->db->update('news_updates');
}

function searchnews($keyword, $type = null, $limit = 10) {
    $this->db->select('id, title, slug, excerpt, publish_date, type');
    $this->db->from('news_updates');
    $this->db->where('deleted', 'f');
    $this->db->where('status', 'published');
    $this->db->where('(publish_date IS NULL OR publish_date <= NOW())');
    $this->db->where('(expiry_date IS NULL OR expiry_date >= NOW())');
    
    $this->db->group_start();
    $this->db->like('title', $keyword);
    $this->db->or_like('content', $keyword);
    $this->db->or_like('excerpt', $keyword);
    $this->db->group_end();
    
    if ($type) {
        $this->db->where('type', $type);
    }
    
    $this->db->order_by('publish_date DESC, created_at DESC');
    $this->db->limit($limit);
    
    return $this->db->get()->result();
}

function getnewsbytype($type, $limit = null, $offset = 0) {
    $this->db->where('deleted', 'f');
    $this->db->where('status', 'published');
    $this->db->where('type', $type);
    $this->db->where('(publish_date IS NULL OR publish_date <= NOW())');
    $this->db->where('(expiry_date IS NULL OR expiry_date >= NOW())');
    $this->db->order_by('publish_date DESC, created_at DESC');
    
    if ($limit) {
        $this->db->limit($limit, $offset);
    }
    
    return $this->db->get('news_updates')->result();
}

function countnewsbytype($type = null, $status = null) {
    $this->db->where('deleted', 'f');
    
    if ($type) {
        $this->db->where('type', $type);
    }
    
    if ($status) {
        $this->db->where('status', $status);
    }
    
    return $this->db->count_all_results('news_updates');
}

// Helper function to generate slug
private function generate_slug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Check if slug exists
    $counter = 1;
    $original_slug = $slug;
    
    while ($this->slug_exists($slug)) {
        $slug = $original_slug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

private function slug_exists($slug) {
    $this->db->where('slug', $slug);
    $this->db->where('deleted', 'f');
    return $this->db->count_all_results('news_updates') > 0;
}

// Enhanced HTML sanitization for news content
function sanitize_html_news($html, $allow_iframe = false) {
    // Start with allowed tags for WYSIWYG content
    $allowed_tags = '<h1><h2><h3><h4><h5><h6><p><br><div><span><strong><b><em><i><u><blockquote><hr><pre><code>';
    $allowed_tags .= '<ul><ol><li><table><tr><td><th><thead><tbody><tfoot><caption><col><colgroup>';
    $allowed_tags .= '<a><img><figure><figcaption>';
    $allowed_tags .= '<sup><sub><small><mark><del><ins><abbr><cite><q><time><address>';
    
    // Add iframe if allowed (for embedded content)
    if ($allow_iframe) {
        $allowed_tags .= '<iframe>';
    }
    
    // Clean HTML
    $html = strip_tags($html, $allowed_tags);
    
    // Clean dangerous attributes
    $html = preg_replace('/ on\w+="[^"]*"/i', '', $html);
    $html = preg_replace('/ javascript:/i', '', $html);
    $html = preg_replace('/ data:/i', '', $html);
    
    // Clean style attributes (optional)
    $html = preg_replace('/ style="[^"]*"/i', '', $html);
    
    // Clean iframe src to allow only specific domains
    if ($allow_iframe) {
        $html = preg_replace_callback('/<iframe[^>]*src="([^"]*)"[^>]*>/i', function($matches) {
            $allowed_domains = ['youtube.com', 'youtu.be', 'vimeo.com', 'google.com', 'maps.google.com'];
            $src = $matches[1];
            $allowed = false;
            
            foreach ($allowed_domains as $domain) {
                if (strpos($src, $domain) !== false) {
                    $allowed = true;
                    break;
                }
            }
            
            if ($allowed) {
                return $matches[0];
            } else {
                return '<div class="alert alert-warning">Embedded content from this domain is not allowed.</div>';
            }
        }, $html);
    }
    
    return trim($html);
}


    

















    public function getStoreInfo() {
        // Explicitly select all columns except for the sensitive ones.
        $this->db->select('id, name, information, address, telephone, whatsapp, instagram, facebook, youtube, googlemap, email, logo, welcome_text, welcome_quote, open_hours, colour, core_values, services, special_offer, meta_keywords, acct_details');
    
        $query = $this->db->get_where('about', array('id' => 1));
    
        $record = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $record = $row;
        } else {
            $record = [
                "id" => "",
                "name" => "",
                "information" => "",
                "address" => "",
                "telephone" => "",
                "whatsapp" => "",
                "instagram" => "",
                "facebook" => "",
                "youtube" => "",
                "googlemap" => "",
                "email" => "",
                "logo" => "",
                "welcome_text" => "",
                "welcome_quote" => "",
                "open_hours" => "",
                "colour" => "",
                "core_values" => "{}",
                "services" => "{}",
                "special_offer" => "{}",
                "meta_keywords" => "{}",
                "acct_details" => "{}"
            ];
        }
        return $record;
    }

    

    public function updateStoreInfo($storeInfo = array()) {
        // Ensure the ID is present and valid
        if (empty($storeInfo['id'])) {
            log_message('error', 'Attempted to update store info without an ID.');
            return false;
        }

        $this->db->where('id', $storeInfo['id']);
        if ($this->db->update("about", $storeInfo)) {
            return true; // Success
        } else {
            // Log the database error if needed for debugging
            log_message('error', 'Database error updating store info: ' . $this->db->error()['message']);
            return false; // Failure
        }
    }
}

?>
