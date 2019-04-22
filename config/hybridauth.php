<?php
use Cake\Core\Configure;

return [
    'HybridAuth' => [
        'providers' => [
            'Facebook' => [
                'enabled' => true,
                'keys' => [
                    'id' => '513037572471782',
                    'secret' => 'd445e131e52298697c36d6e57736dbf5'
                ],
                'scope' => 'email, public_profile'
            ]
        ]
    ],
];

?>