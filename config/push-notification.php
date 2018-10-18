<?php

return array(

    'ios'     => array(
        'environment' =>'production',
        'certificate' => storage_path('cer.pem'),
        'passPhrase'  =>'',
        'service'     =>'apns'
    ),
    'android' => array(
        'environment' =>'production',
        'apiKey'      =>'AIzaSyALZDfIt-NMdTwApoX5a6SwmEYqpMtoKgQ',
        'service'     =>'gcm'
    )

);