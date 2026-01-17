<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load the model that handles database queries for products
        $this->load->model('productmodel');
    }

    public function index() {
        // Set the content type to XML
        $this->output->set_content_type('application/xml');

        // Fetch product data from the database using your model
        $products = $this->productmodel->get_all_products_for_sitemap();

        // Pass the data to a view file to generate the XML
        $data['products'] = $products;
        $this->load->view('sitemap_xml', $data);
    }
}