<?php namespace Addcn\Model\Request;

/**
 *
 * @author admin
 *
 */
class CurlNormal
{

    private $curl;

    private $method;

    private $https;

    private $header = array();

    private $proxy;

    public function __construct($method = 'get', $https = false)
    {

        $this->method = $method;

        $this->https = $https;

    }

    public function setHeader(array $header = array())
    {

        $this->header = $header;

        return $this;

    }

    public function setProxy($ip)
    {

        $this->proxy = $ip;

        return $this;

    }

    public function setMethod($method)
    {

        $this->method = $method;

        return $this;

    }

    /**
     * 请求头部数组
     * @return multitype:string
     */
    private function getHeader()
    {

        $header = $this->header;
        if ($this->method == 'post') {
            $header = empty($header) ? array('Content-Type:application/x-www-form-urlencoded;charset=utf-8') : $header;
        } else {

        }

        return $header;

    }

    /**
     * send request
     * @param string $url
     * @param array $param
     * @throws \Exception
     * @return mixed
     */
    public function send($url, $param = array())
    {

        $header = $this->getHeader();

        $ch = $this->getCurl();

        // 设置URL和相应的选项

        $options = array(
            CURLOPT_URL        => $url,
            CURLOPT_HTTPHEADER => $header,
        );

        $method = strtoupper($this->method);

        if ($method == 'POST') {

            $options[CURLOPT_POSTFIELDS] = is_array($param) ? '?' . http_build_query($param) : $param;
            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
            $options[CURLOPT_POST] = 1;

        } else if ($method == 'GET') {
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $options[CURLOPT_CUSTOMREQUEST] = 'GET';
            $options[CURLOPT_URL] .= empty($param) ? '' : (is_array($param) ? '?' . http_build_query($param) : $param);
            //$options[CURLOPT_POSTFIELDS] = is_array($param) ? '?' . http_build_query($param) : $param;
        } else {
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            $options[CURLOPT_POSTFIELDS] = is_array($param) ? '?' . http_build_query($param) : $param;
        }

        if (!empty($this->proxy)) {
            $options[CURLOPT_PROXY] = $this->proxy;
            $options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
            $options[CURLOPT_HTTPPROXYTUNNEL] = true;
        }

        $res = curl_setopt_array($ch, $options);

        // 获取返回数据
        $data = curl_exec($ch);

        $err = curl_errno($ch);
        $errmsg = curl_error($ch);

        if ($data === false) {
            throw new \Exception($errmsg, $err);
        }

        return $data;

    }

    public function __destruct()
    {

        // 关闭cURL资源，并且释放系统资源
        $this->close();

    }

    /**
     * 关闭cURL资源，并且释放系统资源
     */
    public function close()
    {

        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
        $this->curl = null;

    }

    /**
     * 獲取curl資源
     */
    private function getCurl()
    {

        if (is_resource($this->curl)) {
            return $this->curl;
        }

        $ch = curl_init();



        // $dir = ADDCN_DIR . '/apps/index/data/';
        // $cookie_jar = tempnam($dir,'cookie'); 

        // 设置URL和相应的选项
        $options = array(
            CURLOPT_HEADER         => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => 1,
            //            CURLOPT_AUTOREFERER => true,
            // CURLOPT_COOKIEJAR => $cookie_jar,
            // CURLOPT_COOKIEFILE => $cookie_jar,
        );

        if ($this->https) {
            //$options[CURLOPT_SSL_VERIFYPEER] = true;
            //$options[CURLOPT_SSL_VERIFYHOST] = true;
            //$options[CURLOPT_SSLVERSION] = 6;
        }

        curl_setopt_array($ch, $options);

        $this->curl = $ch;

        return $this->curl;

    }

}
