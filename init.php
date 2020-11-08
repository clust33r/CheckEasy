<?php
class Chk {
    protected $url;
    protected $verif;
    protected $cookies;
    protected $proxy;
    protected $proxyusr;
    protected $proxypss;

    public function __construct($m, $dataget = false)
    {
        if($dataget === false) {
            $this->url = $m;
        } else {
            $this->url = $m."?".http_build_query($dataget);
        }
    }

    public function addProxy($ip, $pass = null) {
        if($pass != null) {
            $p = explode(":", $pass);
            $this->proxyusr = $p[0];
            $this->proxypss = $p[1];
        }

        $this->proxy = $ip;
    }

    /*public function enableVerify() {
        $this->verif = true;
    }
    
    public function enableCookies() {
        $this->cookies = true;
    }*/

    public function addFunctions($arr) {
        if(in_array("cookies", $arr)) {
            $this->cookies = true;
        } elseif(in_array("verify", $arr)) {
            $this->verif = true;
        }
    }

    public function deleteCookies() {
        if(file_exists("/cookies.txt")) {
            unlink("/cookies.txt");
        }
    }

    public function getString($string, $start, $end) {
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str;
    }

    public function chk($type, $header = false, $postf = null) {
        if($this->url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            if($header != false) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            } 
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 0);
            if($this->verif == true) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            }
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            if($this->cookies === true) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookies.txt');
                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookies.txt');
            }
            if($this->proxy) {
                curl_setopt($ch, CURLOPT_PROXY, "http://".$this->proxy);
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
            } 
            if($this->proxypss) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxyusr.":".$this->proxypss);
            }
            if($type = "post" === true || $postf != null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postf);
            }
            $page = curl_exec($ch);
            return $page;
        }
    }
    
}
?>