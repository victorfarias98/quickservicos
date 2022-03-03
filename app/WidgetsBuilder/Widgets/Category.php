<?php

namespace App\WidgetsBuilder\Widgets;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use App\Category as CategoryModel;

class Category extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title_text = purify_html($settings['title']);
        $categories = CategoryModel::whereHas('services')->select('id','name','slug')->take(5)->inRandomOrder()->get();
        $route = route('service.list.category');
        
        $category_markup = '';

       foreach ($categories as $cat){
       $category_name = $cat->name;
       $category_slug = $cat->slug;
       $category_markup.= <<<CATEGORY
    <li class="list"><a href="{$route}/{$category_slug}">{$category_name}</a></li>
CATEGORY;

}
   
   return <<<HTML
   <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="footer-widget widget">
            <h6 class="widget-title">{$title_text}</h6>
            <div class="footer-inner">
                <ul class="footer-link-list">
                    {$category_markup}
                </ul>
            </div>
        </div>
    </div>
HTML;
    }

    public function widget_title()
    {
        return __('Category');
    }

}