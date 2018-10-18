<?php

namespace App\Http\Controllers;

use Backpack\PageManager\app\Models\Page;

class PageController extends Controller
{
    public function index($slug)
    {
        $page = Page::findBySlug($slug);

        if (!$page)
        {
            return 'Pagina no Encontrada <a href="'. url('/') .'">Home</a>.';
        }

        $this->data['title'] = $page->title;
        $this->data['content'] = $page->content;
        $this->data['page'] = $page->withFakes();

        return view('pages.'.$page->template, $this->data);
    }
}