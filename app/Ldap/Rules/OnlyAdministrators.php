<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\ActiveDirectory\Group;

class OnlyAdministrators extends Rule
{
    public function isValid()
    {
        $administrators = Group::find('cn=read-only-admin,dc=example,dc=com');

        return $this->user->groups()->recursive()->exists($administrators);
    }
}