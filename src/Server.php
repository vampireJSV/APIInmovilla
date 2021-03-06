<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 11:15
 */

namespace Creativados\Inmovilla;

class Server
{
    const CACHE_DIR = 'cache/inmoApi';
    const URL = "https://apiweb.inmovilla.com/apiweb/apiweb.php";
    const USER_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";
    const MAGIC_STRING = 'lostipos';
    const SECONDS_IN_MINUTE = 60;
    const HEADERS = [
        "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
        "Keep-Alive: 300",
        "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
        "Accept-Language: en-us,en;q=0.5",
        "Pragma: "
    ];
    const LANGUAGE_SPAIN = 1;
    const LANGUAGE_ENGLISH = 2;
    const LANGUAGE_GERMAN = 3;
    const LANGUAGE_FRENCH = 4;
    const LANGUAGE_DUTCH = 5;
    const LANGUAGE_NORWEGIAN = 6;
    const LANGUAGE_RUSSIAN = 7;
    const LANGUAGE_PORTUGUESE = 8;
    const LANGUAGE_SWEDISH = 10;
    const LANGUAGE_FINNISH = 11;
    const LANGUAGE_CHINESE = 12;
    const LANGUAGE_CATALAN = 13;
    const LANGUAGE_ITALIAN = 14;
    private $cookie = null;
    private $agency = 0;
    private $pass = "";
    private $language = self::LANGUAGE_SPAIN;
    private $stack_call = [];
    private $cache_dir = '';
    private $cache_time_life = 0;
    private $calls_count = 0;


    /**
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Server constructor.
     * @param int $agency
     * @param string $pass
     * @param int $cache_time_life
     */
    public function __construct($agency, $pass, $cache_time_life = 60, $cache_dir = self::CACHE_DIR)
    {
        $this->agency = $agency;
        $this->pass = $pass;
        $this->cache_time_life = $cache_time_life;
        if ($this->cache_time_life) {
            $this->cache_dir = $cache_dir . '/' . date('Y-m-d');
            if (!file_exists($this->cache_dir)) {
                mkdir($this->cache_dir);
            }
            $this->cache_dir = $this->cache_dir . '/' . (int)(((date("G") * self::SECONDS_IN_MINUTE) + (int)date("i")) / $this->cache_time_life);
            if (!file_exists($this->cache_dir)) {
                mkdir($this->cache_dir);
            }
        }
    }

    public function process($tipo, $posinicial = 1, $numelementos = 1, $where = "", $orden = "")
    {
        $this->reset_stack_call();
        $this->add_stack_call($tipo, $posinicial, $numelementos, $where, $orden);
        return $this->getData();
    }

    public function add_stack_call($tipo, $posinicial = 1, $numelementos = 1, $where = "", $orden = "")
    {
        $this->stack_call[] = $tipo;
        $this->stack_call[] = $posinicial;
        $this->stack_call[] = $numelementos;
        $this->stack_call[] = $where;
        $this->stack_call[] = $orden;
    }

    public function getData()
    {
        $server_gobals = array_keys($GLOBALS);
        $cache = null;
        $output = [];

        $string = implode(";", [
            $this->agency,
            $this->pass,
            $this->language,
            self::MAGIC_STRING,
            implode(";", $this->stack_call)
        ]);
        if (count($this->stack_call)) {
            $cache_file = md5($string) . '.json';

            if ($this->cache_time_life && file_exists($this->cache_dir . '/' . $cache_file)) {
                $output = json_decode(file_get_contents($this->cache_dir . '/' . $cache_file), true);
            } else {
                $datas = $this->call(rawurlencode($string));
                $output = json_decode($datas, true);
                if ($this->cache_time_life) {
                    file_put_contents($this->cache_dir . '/' . $cache_file, $datas);
                }
            }

            $this->reset_stack_call();
        }

        return $output;
    }

    private function call($cadena)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (strlen($cadena) > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, "param=" . $cadena . '&json=1&ia=' .$_SERVER["REMOTE_ADDR"] .'&ib=' .$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        $this->calls_count++;
        return curl_exec($ch);
    }

    private function reset_stack_call()
    {
        $this->stack_call = [];
    }
}
