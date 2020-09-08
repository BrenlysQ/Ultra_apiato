<?php

$router->post('h/HotelAvailability', [
    'uses' => 'ControllerHotusa@getHotelAvailability',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/HotelInformation', [
    'uses' => 'ControllerHotusa@getHotelInformation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/Observation', [
    'uses' => 'ControllerHotusa@getObservation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/BookingCancellationInformation', [
    'uses' => 'ControllerHotusa@getBookingCancellationInformation',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('h/cancellation_fees', [
    'uses' => 'ControllerHotusa@cancellation_fees',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('h/BundledInformation', [
    'uses' => 'ControllerHotusa@getBundledInformation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/bookinformation', [
    'uses' => 'ControllerHotusa@bookinformation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('h/GetProvinces', [
    'uses' => 'ControllerHotusa@getProvince',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('h/GetCountries', [
    'uses' => 'ControllerHotusa@getCountry',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/GetDirectories', [
    'uses' => 'ControllerHotusa@getDirectory',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/GetHotelList', [
    'uses' => 'ControllerHotusa@getHotelList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/Reservation', [
    'uses' => 'ControllerHotusa@getReservation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/ConfirmBooking', [
    'uses' => 'ControllerHotusa@confirmReservation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/CancelBooking', [
    'uses' => 'ControllerHotusa@cancelBooking',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/cancel_reservation', [
    'uses' => 'ControllerHotusa@cancel_reservation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/bookingInfo', [
    'uses' => 'ControllerHotusa@getBookingInfo',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/CardInformation', [
    'uses' => 'ControllerHotusa@cardInformation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('h/BookingBonusInfo', [
    'uses' => 'ControllerHotusa@getBookingBonusInfo',
    'middleware' => [
        'auth:api',
    ],
]);
