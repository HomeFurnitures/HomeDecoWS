<?php

return [    
    //Fail requests
    'invalidJson' => 'Request data must be a valid JSON!',
    'nullRequest' => 'Request data is  empty!',
    'notLogged' => 'You are not logged in!',
    'notAdmin' => 'You do not have permission for this action!',
    'failLogIn' => 'Something went wrong, probably bad credentials!',
    'failLogOut' => 'Failed to log out! O_o',
    
    //Success requests
    'successRegister' => 'Registration completed successfully!',
    'successOrder' => 'Order completed successfully!',    
    'successLogOut' => 'Logged out successfully',
    'successLogIn' => 'Logged in successfully',
    
    //Response keys
    'token' => 'x-my-token',
    'message' => 'Message',
];