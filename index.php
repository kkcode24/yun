<?php
/*
* 获取订阅者
*/
class UkSpider{
    private $pages;//分页数
    private $start=24;//每页个数
    public function __construct($pages=100){
        $this->pages=$pages;
    }
    /**
    * 生成接口的url
    */
    public function makeUrl($rootUk){
        $urls=array();
        for($i=0;$i<=$this->pages;$i++){
            $start=$this->start*$i;
            $url="https://pan.baidu.com/pcloud/friend/getfollowlist?query_uk={$rootUk}&limit=24&start={$start}";
            $urls[]=$url;
        }
        return $urls;
    }
    /**
    * 根据URL获取订阅用户id
    */
    public function getFollowsByUrl($url){
        $result=$this->sendRequest($url);
        $arr=json_decode($result,true);
        if(empty($arr)||!isset($arr['follow_list'])){
            return;
        }
        $ret=array();
        foreach($arr['follow_list'] as $fan){
            $ret[]=$fan['follow_uk'];
        }
        
        return $ret;
    }
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
}

$ukSpider=new UkSpider();
$urls=$ukSpider->makeUrl(2156937555);
//循环分页url
foreach($urls as $url){
    echo "loading:".$url."\r\n";
    //随机睡眠7到11秒
    $second=rand(2,4);
    echo "sleep...{$second}s\r\n";
    sleep($second);
    //发起请求
    $followList=$ukSpider->getFollowsByUrl($url);
    //如果已经没有数据了，要停掉请求
    if(empty($followList)){
        break;
    }
    print_r($followList);
}



?>