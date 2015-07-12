<?php
namespace application\core;
class View
{
    function generate($content_view, $template_view, $data = null){
        include 'application/views/'.$template_view;
        return $data;
    }
}