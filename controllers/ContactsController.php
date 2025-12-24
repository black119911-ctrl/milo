<?php
require_once 'BaseController.php';

class ContactsController extends BaseController {
    
    public function index($params) {
        $resources = [
            'styles' => [
                '/styles/pages/contacts/contacts.css'
            ]
        ];
        
        return render_view('pages/contacts', [
            'title' => 'Контакты',
            'resources' => $resources
        ]);
    }
}