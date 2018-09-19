<?php

namespace MyBundle\Utils;

class data
{
    public function getData() {

        $option = (!empty($_GET['i']) ? $_GET['i'] : 0);
        $lines = file("http://proxy.pri.jovenclub.cu/squish/?i=$option" );
        return $lines;
    }

    var $bool = 0;
    public function readFile ( $bar, $user ) {

        $array = array();
        $threeGb = 3000;
        if (file_exists($bar)) {
            $myfile = fopen($bar, "r");
            while (!feof($myfile)) {
                $char_concatenated = $numeric = $letter = "";
                $line = fgets($myfile);
                $line = trim($line);
                $strlen = strlen( $line );

                for( $i = 0; $i <= $strlen; $i++ ) {
                    $char = substr( $line, $i, 1 );
                    if( $char == "\t" || $char == ' ') {
                        array_push($array, $char_concatenated);
                        break;
                    }
                    $char_concatenated .= $char;
                    if ($char_concatenated == $user) {
                        $char1 = substr($line, $i+1, $strlen - $i);
                        $char2 = trim(preg_replace('/\t+/', '', $char1));
                        $char3 = explode('/', $char2);

                        if ($char3[0] == '24h') {
                            $real_kuota = -1;
                            return $real_kuota;
                        }

                        for ($i = 0; $i <= strlen($char3[0]); $i++) {
                            $sec = substr($char3[0], $i, 1);
                            if (is_numeric($sec)) {
                                $numeric .= $sec;
                            }
                            else {
                                $letter .= $sec;
                            }
                        }
                        if ($letter == 'Gb') {
                            $real_kuota = $numeric * 1000;
                        }
                        elseif ($letter == 'Mb') {
                            $real_kuota = $numeric.'-Mb';
                        }
                        return $real_kuota;
                    }
                }
            }
            fclose($myfile);
        }
        else {
            die("El fichero no esta en la direccion especificada");
        }
        if (!in_array($user, $array)) {
            return $threeGb;
        }
    }
}