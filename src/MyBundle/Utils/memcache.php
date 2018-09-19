<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 15-feb-17
 * Time: 12:19 p.m.
 */

namespace MyBundle\Utils;

class memcache
{
    var $host = "10.1.32.3";
    var $port = 11211;
    public function addMemcache($user) {
         $memcache = memcache_connect($this->host, $this->port);
         $memcache->add("$user","$user",false,0);
//        $mem = new Memcache;
//        $mem->connect($this->host, $this->port) or die ("Could not connect");
    }
    public function deleteMemcache($user) {
        $memcache = memcache_connect($this->host, $this->port);
        $memcache->delete("$user");
    }

}