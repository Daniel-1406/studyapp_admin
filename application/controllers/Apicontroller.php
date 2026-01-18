<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apicontroller extends CI_Controller {

    // ========== API CONTROLLER METHODS ==========

/**
 * API Endpoint: Get questions credentials
 * URL: /api/questions/credentials
 * Method: GET
 * Parameters (optional):
 * - school_id: Filter by school
 * - dept_id: Filter by department
 * - course_id: Filter by course
 * - topic_id: Filter by topic
 */
public function api_questions_credentials() {
    // Set headers for JSON response
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    
    // Get filters from request
    $filters = array();
    
    if ($this->input->get('school_id')) {
        $filters['school_id'] = (int)$this->input->get('school_id');
    }
    
    if ($this->input->get('dept_id')) {
        $filters['dept_id'] = (int)$this->input->get('dept_id');
    }
    
    if ($this->input->get('course_id')) {
        $filters['course_id'] = (int)$this->input->get('course_id');
    }
    
    if ($this->input->get('topic_id')) {
        $filters['topic_id'] = (int)$this->input->get('topic_id');
    }
    
    try {
        // Get data from model
        $credentials = $this->apimodel->get_questions_credentials($filters);
        
        // Transform data to match your required structure
        $result = array();
        foreach ($credentials as $cred) {
            $result[] = array(
                'sch_id' => (int)$cred['sch_id'],
                'dept_id' => (int)$cred['dept_id'],
                'semester' => $cred['semester'],
                'coursecode' => $cred['course_code'],
                'coursetitle' => $cred['course_title'],
                'topic' => $cred['topic_name'],
                // Additional information if needed
                'school_name' => $cred['school_name'],
                'department_name' => $cred['department_name'],
                'course_id' => (int)$cred['course_id'],
                'topic_id' => (int)$cred['topic_id']
            );
        }
        
        // Remove duplicates and organize data
        $organized_data = $this->_organize_credentials_data($result);
        
        // Return success response
        $response = array(
            'status' => 'success',
            'message' => 'Credentials retrieved successfully',
            'data' => $organized_data,
            'total' => count($organized_data),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        // Return error response
        $response = array(
            'status' => 'error',
            'message' => 'Failed to retrieve credentials: ' . $e->getMessage(),
            'data' => array(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        http_response_code(500);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

/**
 * API Endpoint: Get questions data
 * URL: /api/questions/data
 * Method: GET
 * Parameters (optional):
 * - course_id: Filter by course
 * - topic_id: Filter by topic
 * - school_id: Filter by school
 * - dept_id: Filter by department
 * - semester: Filter by semester
 * - year: Filter by year
 * - search: Search term
 * - page: Page number (for pagination)
 * - limit: Items per page (default: 20)
 */
public function api_questions_data() {
    // Set headers for JSON response
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    
    // Get filters from request
    $filters = array();
    
    if ($this->input->get('course_id')) {
        $filters['course_id'] = (int)$this->input->get('course_id');
    }
    
    if ($this->input->get('topic_id')) {
        $filters['topic_id'] = (int)$this->input->get('topic_id');
    }
    
    if ($this->input->get('school_id')) {
        $filters['school_id'] = (int)$this->input->get('school_id');
    }
    
    if ($this->input->get('dept_id')) {
        $filters['dept_id'] = (int)$this->input->get('dept_id');
    }
    
    if ($this->input->get('semester')) {
        $filters['semester'] = $this->input->get('semester');
    }
    
    if ($this->input->get('year')) {
        $filters['year'] = (int)$this->input->get('year');
    }
    
    if ($this->input->get('search')) {
        $filters['search'] = $this->input->get('search');
    }
    
    // Get pagination parameters
    $page = (int)$this->input->get('page');
    $limit = (int)$this->input->get('limit');
    
    // Set defaults
    if ($page < 1) $page = 1;
    if ($limit < 1) $limit = 20;
    if ($limit > 100) $limit = 100; // Max limit for security
    
    $offset = ($page - 1) * $limit;
    
    try {
        // Get data from model
        $questions = $this->apimodel->get_questions_data($filters, $limit, $offset);
        $total_questions = $this->apimodel->count_questions_data($filters);
        
        // Transform data to match your required structure
        $result = array();
        foreach ($questions as $question) {
            $result[] = array(
                'id' => (int)$question['id'],
                'qst' => $question['qst'],
                'option_a' => $question['option_a'],
                'option_b' => $question['option_b'],
                'option_c' => $question['option_c'] ?? '',
                'option_d' => $question['option_d'] ?? '',
                'ans' => $question['ans'],
                'explanation' => $question['explanation'] ?? '',
                'topic_id' => (int)$question['topic_id'],
                'course_id' => (int)$question['course_id'],
                'year' => (int)$question['year'],
                'instruction' => $question['instruction'] ?? '',
                'created_at' => $question['date_added'],
                // Additional context information
                'course_code' => $question['course_code'],
                'course_title' => $question['course_title'],
                'topic_name' => $question['topic_name'],
                'semester' => $question['semester'],
                'school_name' => $question['school_name'],
                'department_name' => $question['department_name']
            );
        }
        
        // Get available years and semesters for filtering
        $available_years = $this->apimodel->get_question_years();
        $available_semesters = $this->apimodel->get_semesters();
        
        // Calculate pagination info
        $total_pages = ceil($total_questions / $limit);
        
        // Return success response
        $response = array(
            'status' => 'success',
            'message' => 'Questions data retrieved successfully',
            'data' => $result,
            'pagination' => array(
                'current_page' => $page,
                'per_page' => $limit,
                'total_items' => $total_questions,
                'total_pages' => $total_pages,
                'has_next_page' => $page < $total_pages,
                'has_previous_page' => $page > 1
            ),
            'filters' => array(
                'available_years' => array_column($available_years, 'year'),
                'available_semesters' => array_column($available_semesters, 'semester')
            ),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        // Return error response
        $response = array(
            'status' => 'error',
            'message' => 'Failed to retrieve questions data: ' . $e->getMessage(),
            'data' => array(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        http_response_code(500);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

/**
 * API Endpoint: Get single question by ID
 * URL: /api/questions/{id}
 * Method: GET
 */
public function api_question_by_id($id) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    
    try {
        // Get single question
        $this->db->select('*');
        $this->db->from('qsts');
        $this->db->where('id', $id);
        $this->db->where('deleted', 0);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $question = $query->row_array();
            
            // Get course and topic info
            $this->db->select('c.code as course_code, c.title as course_title, t.name as topic_name');
            $this->db->from('courses c');
            $this->db->join('topics t', 't.crse_id = c.id', 'left');
            $this->db->where('c.id', $question['crse_id']);
            $this->db->where('t.id', $question['topic_id']);
            $info_query = $this->db->get();
            $info = $info_query->row_array();
            
            $response = array(
                'status' => 'success',
                'data' => array(
                    'id' => (int)$question['id'],
                    'qst' => $question['qst'],
                    'option_a' => $question['option_a'],
                    'option_b' => $question['option_b'],
                    'option_c' => $question['option_c'] ?? '',
                    'option_d' => $question['option_d'] ?? '',
                    'ans' => $question['ans'],
                    'explanation' => $question['explanation'] ?? '',
                    'topic_id' => (int)$question['topic_id'],
                    'course_id' => (int)$question['crse_id'],
                    'year' => (int)$question['year'],
                    'instruction' => $question['instruction'] ?? '',
                    'course_code' => $info['course_code'] ?? '',
                    'course_title' => $info['course_title'] ?? '',
                    'topic_name' => $info['topic_name'] ?? ''
                ),
                'timestamp' => date('Y-m-d H:i:s')
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Question not found',
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            );
            http_response_code(404);
        }
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to retrieve question: ' . $e->getMessage(),
            'data' => null,
            'timestamp' => date('Y-m-d H:i:s')
        );
        http_response_code(500);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

/**
 * Helper function to organize credentials data
 */
private function _organize_credentials_data($data) {
    $organized = array();
    
    foreach ($data as $item) {
        // Create a unique key for each course-topic combination
        $key = $item['course_id'] . '_' . $item['topic_id'];
        
        if (!isset($organized[$key])) {
            $organized[$key] = array(
                'sch_id' => $item['sch_id'],
                'dept_id' => $item['dept_id'],
                'semester' => $item['semester'],
                'coursecode' => $item['coursecode'],
                'coursetitle' => $item['coursetitle'],
                'topic' => $item['topic'],
                'school_name' => $item['school_name'] ?? '',
                'department_name' => $item['department_name'] ?? '',
                'course_id' => $item['course_id'],
                'topic_id' => $item['topic_id']
            );
        }
    }
    
    return array_values($organized);
}

/**
 * API Endpoint: Get all available filters (schools, departments, years, semesters)
 * URL: /api/questions/filters
 * Method: GET
 */
public function api_questions_filters() {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    
    try {
        // Get schools
        $this->db->select('id, name');
        $this->db->from('schools');
        $this->db->where('deleted', 0);
        $this->db->order_by('name');
        $schools_query = $this->db->get();
        $schools = $schools_query->result_array();
        
        // Get departments
        $this->db->select('id, name, sch_id');
        $this->db->from('departments');
        $this->db->where('deleted', 0);
        $this->db->order_by('name');
        $depts_query = $this->db->get();
        $departments = $depts_query->result_array();
        
        // Get years
        $years = $this->apimodel->get_question_years();
        
        // Get semesters
        $semesters = $this->apimodel->get_semesters();
        
        $response = array(
            'status' => 'success',
            'data' => array(
                'schools' => $schools,
                'departments' => $departments,
                'years' => array_column($years, 'year'),
                'semesters' => array_column($semesters, 'semester')
            ),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to retrieve filters: ' . $e->getMessage(),
            'data' => array(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        http_response_code(500);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

 
    
   

}

