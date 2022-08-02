<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

use LdapRecord\Models\Model;

class User extends Model
{
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];
}