<?php

namespace App\WidgetsBuilder\Widgets;

use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Helpers\RepeaterField;

class CommunityWidget extends WidgetBase
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
        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'contact_page_contact_info_01',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'com_text',
                    'label' => __('Text')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Url')
                ],
            ]
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title = purify_html($settings['title']);

        $repeater_data = $settings['contact_page_contact_info_01'];
        $community_markup = '';

        foreach ($repeater_data['com_text_'] as $key => $com_text) {
            $com_text = purify_html($com_text);
            $url = $repeater_data['url_'][$key];
            if($url==''){
                $url = route('user.register');
            }
            $community_markup.= <<<SOCIALICON
            <li class="lists">
                <li class="list"><a href="{$url}">{$com_text}</a></li>
            </li>

SOCIALICON;
    }
   
   return <<<HTML
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="footer-widget widget">
                <h6 class="widget-title">{$title}</h6>
                <div class="footer-inner">
                    <ul class="footer-link-list">
                        {$community_markup}
                    </ul>
                </div>
            </div>
        </div>
HTML;
    }

    public function widget_title()
    {
        return __('Community');
    }

}