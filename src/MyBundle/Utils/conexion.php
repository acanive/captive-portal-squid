<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 07-feb-17
 * Time: 10:01 a.m.
 */
namespace MyBundle\Utils;

use Symfony\Component\BrowserKit\Response;

class conexion
{
    private $hostDB = '10.1.32.3';
    private $userDB = 'pcautive';
    private $passwordDB = 'tedf9xdeuJEs6Rdc';
    private $nameDB = 'policyd';
    public function conexion_db($user) {
        $user_search_member = $user.'@pri.jovenclub.cu';
        $user_search_track = 'Sender:'.$user.'@pri.jovenclub.cu';
        $date = new\DateTime('now');
        $mounth_year = $date->format('Y-m');

        $mysql = mysqli_connect($this->hostDB, $this->userDB, $this->passwordDB, $this->nameDB);
        if (!$mysql) {

            return 0;

        }
        else {

        $saliente = mysqli_query($mysql, "SELECT * FROM accounting WHERE ID=1");
        $row_saliente = $saliente->fetch_object();
        $limit_email_saliente = $row_saliente->MessageCountLimit;

        $servicio = mysqli_query($mysql, "SELECT * FROM accounting WHERE ID=2");
        $row_servicio = $servicio->fetch_object();
        $limit_email_servicio = $row_servicio->MessageCountLimit;

        $exists = mysqli_query($mysql, "SELECT * FROM policy_group_members WHERE Member='$user_search_member'");
        $email_send = mysqli_query($mysql, "SELECT * FROM accounting_tracking WHERE TrackKey='$user_search_track' AND PeriodKey='$mounth_year'");
        $row_email_send = $email_send->fetch_object();
        $cant_email_send = $row_email_send->MessageCount;

        if ($exists->num_rows > 0) {
            $email_kuota_percent = $cant_email_send/$limit_email_servicio*100;
            $array = [
                'percent' => $email_kuota_percent,
                'cant_email_send' => $cant_email_send
            ];
//            return $array;
        }
        else {
            $email_kuota_percent = $cant_email_send/$limit_email_saliente*100;
            $array = [
              'percent' => $email_kuota_percent,
              'cant_email_send' => $cant_email_send
            ];
//            return $array;
        }
        return $array;
      }
    }
}