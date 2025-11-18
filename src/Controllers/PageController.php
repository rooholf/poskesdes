<?php
namespace App\Controllers;

use App\Core\View;

class PageController {
    public function render(string $page): void {
        if ($page === 'home') { View::render('home'); return; }
        if ($page === 'schedule') { View::render('schedule'); return; }
        if ($page === 'articles') { View::render('articles'); return; }
        if ($page === 'article') { View::render('article_detail'); return; }
        if ($page === 'profile') { View::render('profile'); return; }
        if (substr($page,0,6) === 'admin_') { View::render('admin/' . $page); return; }
        View::render('home');
    }
}