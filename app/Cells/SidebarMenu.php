<?php

namespace App\Cells;

use App\Models\MenuModel;

class SidebarMenu
{
    public function render()
    {
        $menuModel = new MenuModel();

        // Fetch top-level menus, ordered
        $menus = $menuModel->where('parent_id', null)
            ->orderBy('order', 'ASC')
            ->findAll();

        // Fetch children for each
        foreach ($menus as $menu) {
            $menu->children = $menuModel->where('parent_id', $menu->id)
                ->orderBy('order', 'ASC')
                ->findAll();
        }

        return view('App\Cells\sidebar_menu', ['menus' => $menus]);
    }
}
