<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 12-ene-17
 * Time: 4:43 p.m.
 */

namespace MyBundle\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Ldap\LdapClient;
use Memcache;

class ldap
{

    var $server = '10.1.32.3';
    var $port = 389;
    var $dn = 'uid=kolab-service,ou=Special Users,dc=pri,dc=jovenclub,dc=cu';
    var $dn_user = 'ou=People,dc=pri,dc=jovenclub,dc=cu';
    var $user = 'uid=kolab-service';
    var $pass = 'Kolabpri2016*';

    function userInfo($user){


        error_reporting(0);
        $ldap = new LdapClient($this->server, $this->port, 3, false, false);
        $ldap->bind($this->dn, $this->pass);

        $a=$ldap->find('dc=pri,dc=jovenclub,dc=cu', "(|(uid=$user)(ou=People))");
        $user_fullName = $a[1]["cn"][0];
        $user_usuario = $a[1]["uid"][0];
        $user_email = $a[1]['mail'][0];
        $user_centroTrabajo = $a[1]["o"][0];
        $user_cargo = $a[1]["title"][0];
        $user_telefono = $a[1]["telephonenumber"][0];

        if ($user_fullName=='')
            $user_fullName='';
        elseif ($user_usuario=='')
            $user_usuario='';
        elseif ($user_email=='')
            $user_email='';
        elseif ($user_centroTrabajo=='')
            $user_centroTrabajo='';
        elseif ($user_cargo=='')
            $user_cargo='';
        elseif ($user_telefono=='')
            $user_telefono='';
        $data = [
            "user_name" => "$user_fullName",
            "user_usuario" => "$user_usuario",
            "user_email" => "$user_email",
            "user_centro_trabajo" => "$user_centroTrabajo",
            "user_telefono" => "$user_telefono",
            "user_cargo" => "$user_cargo"
        ];
        return $data;
    }

    function changePass($user, $oldPassword, $newPassword)
    {
        $con = ldap_connect($this->server);
        ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($con, LDAP_OPT_REFERRALS, 0);
        $bind = (ldap_bind($con, "uid=$user,".$this->dn_user,$oldPassword));

        if (!$bind)
        {
            return false;
        }
        else
        {
            $user_search = ldap_search($con, $this->dn_user, "(|(uid=$user))");
            $user_get = ldap_get_entries($con, $user_search);
            $user_entry = ldap_first_entry($con, $user_search);
            $user_dn = ldap_get_dn($con, $user_entry);
            $user_pass = $user_get[0]["userpassword"][0];
            $modifs = [
                [
                    "attrib"  => "userpassword",
                    "modtype" => LDAP_MODIFY_BATCH_REMOVE,
                    "values"  => [$user_pass],
                ],
                [
                    "attrib"  => "userpassword",
                    "modtype" => LDAP_MODIFY_BATCH_ADD,
                    "values"  => [$newPassword],
                ],
            ];
            ldap_modify_batch($con, $user_dn, $modifs);
            return true;
        }
    }
}