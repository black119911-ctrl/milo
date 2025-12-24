<?php

class MenuManager
{
    protected $menuItems;

    public function __construct()
    {
        $this->menuItems = [
            ['href' => '/', 'title' => 'Главная'],
            ['href' => '/catalog', 'title' => 'Каталог'],
            ['href' => '/partners', 'title' => 'Каталог наших партнёров'],
            ['href' => '/checkout', 'title' => 'Оформить заказ'],
            ['href' => '/sertificates', 'title' => 'Сертификаты'],
            ['href' => 'https://t.me/milovan4ik_bot', 'title' => 'Служба поддержки', 'target' => '_blank'],
            ['href' => '/research', 'title' => 'Исследования'],
            ['href' => '/about', 'title' => 'Обо мне'],
            ['href' => '/contacts', 'title' => 'Контакты'],
            ['href' => '/reviews', 'title' => 'Отзывы'],
        ];
    }

    public function getMenuItems()
    {
        return $this->menuItems;
    }
}