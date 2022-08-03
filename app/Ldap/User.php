<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Models\Concerns\CanAuthenticate;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable
{
    use CanAuthenticate;

    public static $objectClasses = ['top','person', 'organizationalperson', 'inetOrgPerson'];

    protected $guidKey = 'uid';
}
