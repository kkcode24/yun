<?php
header("content-type:text/html;charset=utf-8");
/*
* 获取分享列表
*/
class TextsSpider{
    private $pages=500;//分页数
    private $start=60;//每页个数
    /**
    * 发送请求
    */
    public function sendRequest($url,$data = null,$header=null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($header)){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
     /**
    * 生成分页接口的url
    */
    public function makeUrl($rootUk){
        $urls=array();
        for($i=0;$i<=$this->pages;$i++){
            $start=$this->start*$i;
            $url="https://pan.baidu.com/pcloud/feed/getsharelist?&auth_type=1&request_location=share_home&start={$start}&limit={$this->start}&query_uk={$rootUk}";
            $urls[]=$url;
        }
        return $urls;
    }

     /**
    * json 转a连接，可下载
    */

     public function makeDown($json){
        $arr = array();
        $str = '';
        $filelist = $json->records;
        foreach ($filelist as $key => $value) {
            $str = "<a href='http://pan.baidu.com/share/link?shareid={$value->shareid}&uk={$value->uk}'>{$value->title}</a>";
            array_push($arr, $str);
        }
        return $arr;
     }

}
$textsSpider=new TextsSpider();
$header=array(
    'Referer:http://www.baidu.com'
);
$str=$textsSpider->sendRequest("https://pan.baidu.com/pcloud/feed/getsharelist?&auth_type=1&request_location=share_home&start=60&limit=60&query_uk=505148842",null,$header);
$json = json_decode($str);
// print_r($json);
$arr = $textsSpider->makeDown($json);
foreach ($arr as $value) {
    print_r($value."<br>");
}






// $urls=$textsSpider->makeUrl(3560277524);
// print_r($urls);

// 每个分享文件的下载页面url是这样的：http://pan.baidu.com/share/link?shareid={$shareId}&uk={$uk} ，只需要用户编号和分享id就可以拼出下载url。

?>