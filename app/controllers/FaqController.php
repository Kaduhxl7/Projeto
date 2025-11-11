<?php
require_once __DIR__ . '/../config/bootstrap.php';

class FaqController {
    
    public function index() {
        $page_title = __('faq.title');
        $page_description = __('faq.description');
        
        // FAQ estático organizado por categorias
        $faqs = [
            'account' => [
                [
                    'question' => __('faq.account.create_account.question'),
                    'answer' => __('faq.account.create_account.answer')
                ],
                [
                    'question' => __('faq.account.login_problem.question'),
                    'answer' => __('faq.account.login_problem.answer')
                ],
                [
                    'question' => __('faq.account.forgot_password.question'),
                    'answer' => __('faq.account.forgot_password.answer')
                ]
            ],
            'products' => [
                [
                    'question' => __('faq.products.how_to_buy.question'),
                    'answer' => __('faq.products.how_to_buy.answer')
                ],
                [
                    'question' => __('faq.products.product_condition.question'),
                    'answer' => __('faq.products.product_condition.answer')
                ],
                [
                    'question' => __('faq.products.favorites.question'),
                    'answer' => __('faq.products.favorites.answer')
                ]
            ],
            'platform' => [
                [
                    'question' => __('faq.platform.what_is.question'),
                    'answer' => __('faq.platform.what_is.answer')
                ],
                [
                    'question' => __('faq.platform.sustainable.question'),
                    'answer' => __('faq.platform.sustainable.answer')
                ],
                [
                    'question' => __('faq.platform.languages.question'),
                    'answer' => __('faq.platform.languages.answer')
                ]
            ]
        ];
        
        $data = [
            'faqs' => $faqs,
            'page_title' => $page_title,
            'page_description' => $page_description
        ];
        
        include __DIR__ . '/../views/faq.php';
    }
}
?>