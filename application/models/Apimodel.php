<?php

class Apimodel extends CI_Model {

    // ========== API MODEL METHODS ==========

/**
 * Get questions credentials (school, department, course, topic info)
 * @param array $filters - Optional filters (school_id, dept_id, course_id, topic_id)
 * @return array
 */
public function get_questions_credentials($filters = array()) {
    $this->db->select('
        s.id as sch_id,
        s.name as school_name,
        d.id as dept_id,
        d.name as department_name,
        c.id as course_id,
        c.semester,
        c.code as course_code,
        c.title as course_title,
        t.id as topic_id,
        t.name as topic_name
    ');
    
    $this->db->from('schools s');
    $this->db->join('departments d', 'd.sch_id = s.id AND d.deleted = 0', 'left');
    $this->db->join('courses c', 'c.sch_id = s.id AND c.dept_id = d.id AND c.deleted = 0', 'left');
    $this->db->join('topics t', 't.crse_id = c.id AND t.deleted = 0', 'left');
    
    // Apply filters
    if (!empty($filters['school_id'])) {
        $this->db->where('s.id', $filters['school_id']);
    }
    
    if (!empty($filters['dept_id'])) {
        $this->db->where('d.id', $filters['dept_id']);
    }
    
    if (!empty($filters['course_id'])) {
        $this->db->where('c.id', $filters['course_id']);
    }
    
    if (!empty($filters['topic_id'])) {
        $this->db->where('t.id', $filters['topic_id']);
    }
    
    // Only show records that are not deleted
    $this->db->where('s.deleted', 0);
    
    // Order by hierarchy
    $this->db->order_by('s.name, d.name, c.code, t.name');
    
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    
    return array();
}

/**
 * Get questions data with filters
 * @param array $filters - Filters for questions (course_id, topic_id, year, etc.)
 * @param int $limit - Limit for pagination
 * @param int $offset - Offset for pagination
 * @return array
 */
public function get_questions_data($filters = array(), $limit = null, $offset = 0) {
    $this->db->select('
        q.id,
        q.qst,
        q.option_a,
        q.option_b,
        q.option_c,
        q.option_d,
        q.ans,
        q.explanation,
        q.topic_id,
        q.crse_id as course_id,
        q.year,
        q.instruction,
        q.date_added,
        c.code as course_code,
        c.title as course_title,
        c.semester,
        t.name as topic_name,
        s.id as sch_id,
        s.name as school_name,
        d.id as dept_id,
        d.name as department_name
    ');
    
    $this->db->from('questions q');
    $this->db->join('courses c', 'c.id = q.crse_id AND c.deleted = 0', 'left');
    $this->db->join('topics t', 't.id = q.topic_id AND t.deleted = 0', 'left');
    $this->db->join('schools s', 's.id = c.sch_id AND s.deleted = 0', 'left');
    $this->db->join('departments d', 'd.id = c.dept_id AND d.deleted = 0', 'left');
    
    // Apply filters
    if (!empty($filters['course_id'])) {
        $this->db->where('q.crse_id', $filters['course_id']);
    }
    
    if (!empty($filters['topic_id'])) {
        $this->db->where('q.topic_id', $filters['topic_id']);
    }
    
    if (!empty($filters['school_id'])) {
        $this->db->where('c.sch_id', $filters['school_id']);
    }
    
    if (!empty($filters['dept_id'])) {
        $this->db->where('c.dept_id', $filters['dept_id']);
    }
    
    if (!empty($filters['semester'])) {
        $this->db->where('c.semester', $filters['semester']);
    }
    
    if (!empty($filters['year'])) {
        $this->db->where('q.year', $filters['year']);
    }
    
    if (!empty($filters['search'])) {
        $this->db->group_start();
        $this->db->like('q.qst', $filters['search']);
        $this->db->or_like('q.option_a', $filters['search']);
        $this->db->or_like('q.option_b', $filters['search']);
        $this->db->or_like('q.option_c', $filters['search']);
        $this->db->or_like('q.option_d', $filters['search']);
        $this->db->or_like('q.explanation', $filters['search']);
        $this->db->group_end();
    }
    
    // Only show non-deleted questions
    $this->db->where('q.deleted', 0);
    
    // Order by
    $this->db->order_by('q.date_added', 'DESC');
    
    // Apply pagination if limit is set
    if ($limit !== null) {
        $this->db->limit($limit, $offset);
    }
    
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    
    return array();
}

/**
 * Count total questions for pagination
 * @param array $filters - Same filters as get_questions_data
 * @return int
 */
public function count_questions_data($filters = array()) {
    $this->db->from('questions q');
    $this->db->join('courses c', 'c.id = q.crse_id AND c.deleted = 0', 'left');
    $this->db->join('topics t', 't.id = q.topic_id AND t.deleted = 0', 'left');
    
    // Apply filters
    if (!empty($filters['course_id'])) {
        $this->db->where('q.crse_id', $filters['course_id']);
    }
    
    if (!empty($filters['topic_id'])) {
        $this->db->where('q.topic_id', $filters['topic_id']);
    }
    
    if (!empty($filters['school_id'])) {
        $this->db->where('c.sch_id', $filters['school_id']);
    }
    
    if (!empty($filters['dept_id'])) {
        $this->db->where('c.dept_id', $filters['dept_id']);
    }
    
    if (!empty($filters['semester'])) {
        $this->db->where('c.semester', $filters['semester']);
    }
    
    if (!empty($filters['year'])) {
        $this->db->where('q.year', $filters['year']);
    }
    
    if (!empty($filters['search'])) {
        $this->db->group_start();
        $this->db->like('q.qst', $filters['search']);
        $this->db->or_like('q.option_a', $filters['search']);
        $this->db->or_like('q.option_b', $filters['search']);
        $this->db->or_like('q.option_c', $filters['search']);
        $this->db->or_like('q.option_d', $filters['search']);
        $this->db->or_like('q.explanation', $filters['search']);
        $this->db->group_end();
    }
    
    $this->db->where('q.deleted', 0);
    
    return $this->db->count_all_results();
}

/**
 * Get distinct years from questions
 * @return array
 */
public function get_question_years() {
    $this->db->select('DISTINCT(year)');
    $this->db->from('questions');
    $this->db->where('deleted', 0);
    $this->db->where('year IS NOT NULL');
    $this->db->order_by('year', 'DESC');
    
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Get distinct semesters
 * @return array
 */
public function get_semesters() {
    $this->db->select('DISTINCT(semester)');
    $this->db->from('courses');
    $this->db->where('deleted', 0);
    $this->db->where('semester IS NOT NULL');
    $this->db->order_by('semester');
    
    $query = $this->db->get();
    return $query->result_array();
}

    
}

?>
