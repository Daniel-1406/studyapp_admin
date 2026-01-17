<?php

class Admin extends CI_Model {

    function adminlogin() {
        if($this->input->post("password") == null){
            return "wrong";
        }
        $q = $this->db->where("username", $this->input->post("username"))->where("password", md5($this->input->post("password")))->get("admin_pass");
        if ($q->num_rows() > 0) {
            $this->session->set_userdata("admin_pass", $this->input->post("username"));
            return $this->input->post("username");
        } else {
            return "wrong";
        }
    }
    function getVisitorCount() {

        $visitorFilePath = FCPATH . 'images/visitors.txt';

        if (!file_exists($visitorFilePath)) {
            return 0;
        }
    
        $visitors = file($visitorFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        return count($visitors);
    }


    function getmessages(){

        $query = $this->db->query("select * from messages");
        $head = "<th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date Sent</th><th>REPLY</th><th>DELETE</th>";
        $body = "";
        $x=0;

        foreach ($query->result() as $row) {
            $timestamp=strtotime($row->date);
            $formattedDate=date('M d, Y H:i', $timestamp);
            $form_open = form_open('welcome/delete');
            $form_hidden = "";
            $form_delete2 = "<a class='btn btn-danger btn-sm' href='deletethismessage/$row->id'><i class='fas fa-trash'> </i></a>";
            $form_edit2 = "<a class='btn btn-info btn-sm' href='replythismessage/$row->id'><i class='fas fa-reply'></i></a>";
            $form_close = form_close();
            $body.="<tr><td>" . $row->name . "</td><td>" . $row->email . "</td><td>" . $row->text. "</td><td>" . $row->message. "</td><td>" . $formattedDate. "</td><td>" . $form_open . "" . $form_edit2 . "" . $form_close . "</td><td>" . $form_open . "" . $form_delete2 . "" . $form_close . "</td></tr>";
        $x++;
        }
        $db_content["head"] = $head;
        $db_content["body"] = $body;
        $db_content["message_count"]=$x;
        return $db_content;

    }
    function getmessagedata($id) {
        $query = $this->db->query("select * from messages where id=$id");
        $db_content = array();
        foreach ($query->result() as $row) {
            $db_content["name"] = $row->name;
            $db_content["email"] = $row->email;
            $db_content["text"] = $row->text;
            $db_content["message"] = $row->message;
            $db_content["date"] = $row->date;
            $db_content["id"] = $id;
        }



        return $db_content;
    }
    function deletemessage($id) {
        if ($this->db->query("update messages set deleted='t' where id=$id")) {
            return '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Success!</h5>
                  Contact Message Deleted Successfully ...
                </div>';
        } else {
            return '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-ban"></i> Error!</h5>
                  Error Deleting Contact Message, Try Again ...
                </div>';
        }
    }
    function getrequests(){

        $query = $this->db->query("select * from requests");
        $head = "<th>First Name</th><th>Last Name</th><th>Phone</th><th>Email</th><th>Category</th><th>Date Sent</th><th>Request Message</th><th>REPLY</th><th>DELETE</th>";
        $body = "";
        $x=0;

        foreach ($query->result() as $row) {
            $timestamp=strtotime($row->time_sent);
            $formattedDate=date('M d,Y H:i', $timestamp);
            $form_open = form_open('welcome/delete');
            $form_hidden = "";
            $form_delete2 = "<a class='btn btn-danger btn-sm' href='deletethisstudent/$row->id'><i class='fas fa-trash'> </i>Delete</a>";
            $form_edit2 = "<a class='btn btn-info btn-sm' href='replythisrequest/$row->id'><i class='fas fa-reply'></i> Reply</a>";
            $form_close = form_close();
            $body.="<tr><td>" . $row->fname . "</td><td>" . $row->lname . "</td><td>" . $row->phone. "</td><td>" . $row->email. "</td><td>" . $row->request_category. "</td><td>" . $formattedDate . "</td><td>" . $row->request . "</td><td>" . $form_open . "" . $form_edit2 . "" . $form_close . "</td><td>" . $form_open . "" . $form_delete2 . "" . $form_close . "</td></tr>";
        $x++;
        }
        $db_content["head"] = $head;
        $db_content["body"] = $body;
        $db_content["message_count"]=$x;
        return $db_content;

    }

    function countmenu() {
        $q = $this->db->query("select * from menu where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countrecords() {
        $q = $this->db->query("select * from records where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countaudio() {
        $q = $this->db->query("select * from audio where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countvideo() {
        $q = $this->db->query("select * from videos where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countcarousel() {
        $q = $this->db->query("select * from carousels where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countminicarousel() {
        $q = $this->db->query("select * from mini_carousel where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countmemories() {
        $q = $this->db->query("select * from gallery where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countstories() {
        $q = $this->db->query("select * from testimonies where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countpages() {
        $q = $this->db->query("select * from custompage where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countprograms() {
        $q = $this->db->query("select * from services where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countrequests() {
        $q = $this->db->query("select * from requests");
        $data= $q->num_rows();
        return $data;
    }
    function countcontact() {
        $q = $this->db->query("select * from messages");
        $data= $q->num_rows();
        return $data;
    }
    function countexhortations() {
        $q = $this->db->query("select * from exhortations where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countevents() {
        $q = $this->db->query("select * from events where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countfaqs() {
        $q = $this->db->query("select * from church_faq where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countprayers() {
        $q = $this->db->query("select * from prayer_resources where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }
    function countcareers() {
        $q = $this->db->query("select * from careers where deleted='f'");
        $data= $q->num_rows();
        return $data;
    }



    
    

    
    

    
    
    


}

?>
