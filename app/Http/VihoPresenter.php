<?php

namespace App\Http;

use Illuminate\Http\Request;
use Nwidart\Menus\Presenters\Presenter;

class VihoPresenter extends Presenter
{
    public function getOpenTagWrapper()
    {
        return '<ul class="nav-menu custom-scrollbar">'.PHP_EOL;
    }

    public function getCloseTagWrapper()
    {
        return '</ul>'.PHP_EOL;
    }

    public function getMenuWithoutDropdownWrapper($item)
    {
        $active = $item->isActive() ? ' active' : '';

        $url = $item->getUrl();

        return '<li'.$active.'><a class="nav-link menu-title link-nav" href="'.$url.'" '.$item->getAttributes().'>'
            .$this->formatIcon($item->icon)
            .'<span>'.$item->title.'</span></a></li>'.PHP_EOL;
    }

    public function getMenuWithDropDownWrapper($item)
    {
        $active = $item->hasActiveOnChild() ? ' active' : '';

        $html = '<li class="dropdown'.$active.'"><a class="nav-link menu-title" href="javascript:void(0)" '.$item->getAttributes().'>'
            .$this->formatIcon($item->icon)
            .'<span>'.$item->title.'</span></a>';

        $html .= '<ul class="nav-submenu menu-content">'.PHP_EOL;
        foreach ($item->getChilds() as $child) {
            $childActive = $child->isActive() ? ' class="active"' : '';
            $childUrl = $child->getUrl();
            $html .= '<li><a'.$childActive.' href="'.$childUrl.'" '.$child->getAttributes().'>'.$child->title.'</a></li>'.PHP_EOL;
        }
        $html .= '</ul></li>'.PHP_EOL;

        return $html;
    }

    public function getDividerWrapper()
    {
        return '';
    }

    public function getHeaderWrapper($item)
    {
        return '<li class="sidebar-main-title"><div><h6>'.$item->title.'</h6></div></li>'.PHP_EOL;
    }

    protected function formatIcon($icon)
    {
        if (empty($icon)) {
            return '';
        }

        if (strpos($icon, '<svg') !== false) {
            return $icon;
        }

        return '<i class="'.$icon.'"></i>';
    }
}
