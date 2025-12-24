<?php
require_once 'BaseController.php';

class PrivacyPolicyController extends BaseController {
    
    public function index($params) {
        $resources = [
            'styles' => [
                '/styles/pages/privacy-policy/privacy-policy.css'
            ]
        ];
        
        return render_view('pages/privacy-policy', [
            'title' => 'Согласие на обработку персональных данных',
            'resources' => $resources
        ]);
    }
}