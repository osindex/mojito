<?php
return [
    'admin_route_path' => env('ADMIN_ROUTE_PATH', 'admin'),
    'access_route' => ['permission.all-user-permission', 'menu.my', 'admin-user.me'],

    'providers' => [
        'admin' => [
            'model' => \Moell\Mojito\Models\AdminUser::class,
            'login_fields' => [
                'email',
            ],
            'conditions' => [
                //['status', '=', 1]
            ],
        ],
    ],
];
