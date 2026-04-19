<?php

namespace App\Services;

use App\Http\Controllers\GlobalController;

class ApplicationService
{
    protected $globalController;

    public function __construct(GlobalController $globalController)
    {
        $this->globalController = $globalController;
    }

    public function initializeData($currentPath)
    {
        $data = new \stdClass();
        $userAccessRole = null;

        $data_user = $this->globalController->user_detail();
        $menu = $this->globalController->show_menu($data_user['data']['id']);

        $matchedPage = $this->findMatchingPage($menu['data'], $currentPath);

        if ($matchedPage) {
            $data->page = $matchedPage['page'];
            $data->parent = $matchedPage['parent'];
            $userAccessRole = json_decode($matchedPage['page']['akses'], true);
        }

        $data->menu = $menu['data'];
        $data->accesses = $userAccessRole;

        return $data;
    }

    private function findMatchingPage(array $menuItems, string $currentPath, $parent = null)
    {
        foreach ($menuItems as $item) {
            // Match current item
            if (str_starts_with($currentPath, $item['path'])) {
                return ['page' => $item, 'parent' => $parent];
            }

            // If has children, recurse
            if (isset($item['children']) && is_array($item['children'])) {
                $result = $this->findMatchingPage($item['children'], $currentPath, $item);
                if ($result) {
                    return $result;
                }
            }
        }

        return null;
    }
}
