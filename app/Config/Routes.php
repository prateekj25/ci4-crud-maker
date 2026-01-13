<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

$routes->group('admin', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->resource('roles', ['controller' => 'Admin\RoleController']);
    $routes->resource('permissions', ['controller' => 'Admin\PermissionController']);
    $routes->resource('menus', ['controller' => 'Admin\MenuController']);
    $routes->resource('modules', ['controller' => 'Admin\ModuleController']);
    $routes->resource('users', ['controller' => 'Admin\UserController']);
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile', 'Admin\ProfileController::update');
});
