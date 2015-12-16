<?php
namespace air;
/**
 * 
 * @author wukezhan<wukezhan@gmail.com>
 * 2014-10-18 23:55
 * @todo multipart/payload upload
 */

class sock
{
    const METHOD_PUT = 'PUT';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const PROTOCOL_HTTP_1_1 = 'HTTP/1.1';
    const TYPE_JSON = 'json';
    const TYPE_X_FORM_URLENCODED = 'x-www-form-urlencoded';
    protected static $_socks = [];
    protected $_sock_path;
    protected $_sock_errno;
    protected $_sock_error;
    protected $_response = [];
    public function __construct($sock_path='')
    {
        $this->set_sock_path($sock_path);
    }
    public function set_sock_path($path)
    {
        $this->_sock_path = $path;
        return $this;
    }

    /**
     * @param $host
     * @param int $port
     * @return mixed
     * @throws exception
     */
    public static function open($host, $port=80)
    {
        $hp = $host. ':'. $port;
        if(!isset(self::$_socks[$hp]) || !is_resource(self::$_socks[$hp]['sock'])){
            $error = $errno = null;
            self::$_socks[$hp] = array(
                'sock' => pfsockopen($host, $port, $errno, $error, 5),
                'host' => $host,
                'port' => $port,
            );
            if($errno){
                throw new exception($error, $errno);
            }
        }
        return self::$_socks[$hp];
    }
    public function parse_url($url)
    {
        $arr = parse_url($url);
        if(!isset($arr['host'])){
            if(!$this->_sock_path){
                throw new exception('Error url: check your url or set the sock_path');
            }
            $arr['host'] = $this->_sock_path;
            $arr['port'] = -1;
        }
        if(isset($arr['scheme']) && 'https'==$arr['scheme'] && !isset($arr['port'])){
            $arr['port'] = 443;
        }
        if(!isset($arr['port'])){
            $arr['port'] = 80;
        }
        $arr['path'] = isset($arr['path'])? $arr['path']: '/';
        $arr['query'] = isset($arr['query'])? $arr['query']: '';

        return $arr;
    }

    /**
     * @param $sock
     * @param int $body_length 消息体长度，-1：不限，0：只读取消息头部不读消息体，>0：读取指定长度
     * @return mixed
     */
    public function parse_response($sock, $body_length=-1)
    {
        $header = $buf = '';
        $blen= 0;
        $read_length = 1024;
        $transfer_encoding = '';
        $chunk_size = 0;
        while(is_resource($sock['sock']) && !feof($sock['sock'])){
            if('chunked' == $transfer_encoding){
                if(!$chunk_size){
                    $s = fgets($sock['sock']);
                    $chunk_size = hexdec(trim($s));
                }
                if($chunk_size>0){
                    $s = fread($sock['sock'], $chunk_size);
                    $buf .= $s;
                    $chunk_size = 0;
                }
            }else{
                $s = fgets($sock['sock'], $read_length);
                $buf .= $s;
            }
            if(!$header && "\r\n"==$s){
                $header = $buf;
                $buf = '';
                if(preg_match('/Transfer-Encoding:(\s+)chunked/i', $header)){
                    $transfer_encoding = 'chunked';
                }
                if($body_length==0){
                    $buf = '';
                    break;
                }else if($body_length>0 && $body_length<$read_length){
                    $read_length = $body_length;
                }
            }else if($header && $body_length>0){
                $blen += $read_length;
            }
        }
        fclose($sock['sock']);
        $this->_response = [
            'header' => $header,
            'body' => $buf,
        ];
        return $this->_response['body'];
    }

    public function request($url, $data=array(), $method=self::METHOD_GET, $data_type=null, $body_length=-1)
    {
        $protocol = self::PROTOCOL_HTTP_1_1;
        $url_arr = $this->parse_url($url);
        $uri = $url_arr['path'].($url_arr['query']?'?'.$url_arr['query']:'');
        if($method == self::METHOD_POST){
            if(is_null($data_type)){
                $data_type = self::TYPE_X_FORM_URLENCODED;
            }
            switch($data_type){
                case self::TYPE_JSON:
                    $data = json_encode($data);
                    break;
                case self::TYPE_X_FORM_URLENCODED:
                    $data = http_build_query($data);
                    break;

                default:
                    //供发送其他自定义内容类型，如xml，等
                    break;
            }
        }else{
            $uri .= ($url_arr['query']?'&':'?').http_build_query($data);
            $data = '';
        }
        $req_header  = "{$method} {$uri} {$protocol}\r\n";
        $req_header .= "Accept: */*\r\n";
        $req_header .= "User-Agent: Air\\Sock\r\n";
        $req_header .= "Host: {$url_arr['host']}\r\n";
        if(!is_null($data_type)){
            $req_header .= "Content-Type: application/{$data_type}\r\n";
        }
        if(self::METHOD_GET != $method){
            $data = $data?$data: '';
            $data_size = $data?strlen($data): 0;
            $req_header .= "Content-Length: {$data_size}\r\n";
        }
        $req_header .= "Connection: Close\r\n\r\n";
        $sock = self::open($url_arr['host'], $url_arr['port']);
        fwrite($sock['sock'], $req_header);
        fwrite($sock['sock'], $data);
        return $this->parse_response($sock, $body_length);
    }
    public function get($url, $params=array(), $body_length=-1)
    {
        return $this->request($url, $params, self::METHOD_GET, null, $body_length);
    }
    public function post($url, $data, $type=self::TYPE_X_FORM_URLENCODED)
    {
        return $this->request($url, $data, self::METHOD_POST, $type);
    }
    public function delete($url, $params=array())
    {
        return $this->request($url, $params, self::METHOD_DELETE);
    }

    public function proxy($host, $port, $remove_prefix='')
    {
        $protocol = $_SERVER['SERVER_PROTOCOL'];
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        //代理可能并不是部署在根目录下，所以可能需要去掉指定的URL前缀
        if($remove_prefix){
            $path = preg_replace("#^{$remove_prefix}#", '', $path);
        }
        //构造协议头
        $req_header = '';
        $req_header .= "{$method} {$path} {$protocol}\r\n";
        foreach($_SERVER as $k=>$v){
            if(strpos($k, 'HTTP_')===0){
                $k = str_replace('_', '-', substr(strtolower($k), 5));
                if(in_array($k, array('connection', 'proxy-connection'))){
                    //此处是浏览器与web server间约定连接状态所用
                    //PHP层应直接忽略，否则将无法及时返回
                    $req_header .= "connection: close\r\n";
                }else{
                    $req_header .= "{$k}: {$v}\r\n";
                }
            }
        }
        $req_header .= "\r\n";
        $sock = self::open($host, $port);
        fwrite($sock['sock'], $req_header);
        $if = fopen('php://input', 'r');
        while(!feof($if)){
            fwrite($sock['sock'], fread($if, 1024));
        }
        fclose($if);

        $transfer_encoding = $header = $buf = '';
        $read_length = 1024;
        $chunk_size = 0;
        while(!feof($sock['sock'])){
            if(!$header){
                $s = fgets($sock['sock'], $read_length);
                $buf .= $s;
            }else{
                if('chunked' == $transfer_encoding){
                    if(!$chunk_size){
                        $s = fgets($sock['sock']);
                        $chunk_size = hexdec(trim($s));
                    }
                    if($chunk_size){
                        echo fread($sock['sock'], $chunk_size);
                        $chunk_size = 0;
                    }
                }else{
                    echo fread($sock['sock'], $read_length);
                }
            }
            if(!$header && "\r\n"==$s){
                $header = $buf;
                $buf = '';
                if(preg_match('/Transfer-Encoding:(\s+)chunked/i', $header)){
                    $transfer_encoding = 'chunked';
                };
                $headers = explode("\r\n", $header);
                foreach($headers as $h){
                    header($h);
                }
            }
        }
        fclose($sock['sock']);
    }
    public function parse_response_header()
    {
        $this->_response['cookies'] = [];
        $this->_response['headers'] = [];
        $headers = &$this->_response['headers'];
        $tmp = explode("\r\n", $this->_response['header']);
        $status = explode(" ", $tmp[0], 3);
        $headers = [
            'protocol' => $status[0],
            'http_code' => $status[1],
            'http_message' => $status[2],
        ];
        unset($tmp[0]);
        foreach($tmp as $k=>$line){
            if(!$line){
                continue;
            }
            list ($key, $value) = explode(': ', $line);
            $key = strtolower($key);
            if(!isset($headers[$k])){
                $headers[$key] = $value;
            }else{
                $headers[$key] = (Array)$headers[$key];
                array_push($headers[$key], $value);
            }
            if('set-cookie'==strtolower($key)){
                if(preg_match('/(?P<key>[^=]+)=(?P<value>[^;]+)/', $value, $matches)){
                    $this->_response['cookies'][$matches['key']] = urldecode($matches['value']);
                }
            }
        }
        return $this;
    }
    public function get_response_header($key=null)
    {
        if(isset($this->_response['header']) && is_string($this->_response['header'])){
            $this->parse_response_header();
            $this->_response['header'] = null;
        }

        if($this->_response['headers']){
            if(is_null($key)){
                return $this->_response['headers'];
            }else{
                return isset($this->_response['headers'][$key])?$this->_response['headers'][$key]: null;
            }
        }

        return null;
    }
}