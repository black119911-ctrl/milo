<?php
require_once 'BaseController.php';

class AboutController extends BaseController {
    public function index($params) {
    
        $showBasket = $this->authenticate($params);

        $resources = [
            'styles' => ['/styles/pages/about/about.css']
        ];

        return render_view('pages/about', [
            'resources' => $resources,
            'showBasket' => $showBasket
        ]); 
    }
}