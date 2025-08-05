<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\View\View;

class DynamicPageController extends Controller {
    /**
     * Show dynamic page
     *
     * @param $page_title
     * @return View
     */
    public function showDynamicPage($page_title): View {
        //* Find the dynamic page by slug (slug is generated from the page title)
        $page = DynamicPage::where('page_slug', $page_title)->firstOrFail();

        //! Pass the dynamic page content to the view
        return view('frontend.dynamic-page-show', compact('page'));
    }
}
