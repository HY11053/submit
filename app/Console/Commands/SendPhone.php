<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AdminModel\Phonesource;
use App\AdminModel\Webinfo;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use QL\QueryList;
use Zhuzhichao\IpLocationZh\Ip;
class SendPhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:phones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->Phonesubmit();
    }


    /**电话提交
     * @param Request $request
     */
    public function Phonesubmit(Request $request)
    {
        $userAngets=[
            "Mozilla/5.0 (iPhone 84; CPU iPhone OS 10_3_3 like Mac OS X) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.0 MQQBrowser/7.8.0 Mobile/14G60 Safari/8536.25 MttCustomUA/2 QBWebViewType/1 WKType/1",
            "Mozilla/5.0 (Linux; Android 7.0; STF-AL10 Build/HUAWEISTF-AL10; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043508 Safari/537.36 V1_AND_SQ_7.2.0_730_YYB_D QQ/7.2.0.3270 NetType/4G WebP/0.3.0 Pixel/1080",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_3 like Mac OS X) AppleWebKit/603.3.8 (KHTML, like Gecko) Mobile/14G60 MicroMessenger/6.5.18 NetType/WIFI Language/en",
            "Mozilla/5.0 (Linux; Android 5.1.1; vivo Xplay5A Build/LMY47V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.116 Mobile Safari/537.36 T7/9.3 baiduboxapp/9.3.0.10 (Baidu; P1 5.1.1)",
            "Mozilla/5.0 (Linux; U; Android 7.0; zh-cn; STF-AL00 Build/HUAWEISTF-AL00) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/37.0.0.0 MQQBrowser/7.9 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; Android 6.0; LEX626 Build/HEXCNFN5902606111S) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/35.0.1916.138 Mobile Safari/537.36 T7/7.4 baiduboxapp/8.3.1 (Baidu; P1 6.0)",
            "Mozilla/5.0 (iPhone 92; CPU iPhone OS 10_3_2 like Mac OS X) AppleWebKit/603.2.4 (KHTML, like Gecko) Version/10.0 MQQBrowser/7.7.2 Mobile/14F89 Safari/8536.25 MttCustomUA/2 QBWebViewType/1 WKType/1",
            "Mozilla/5.0 (Linux; U; Android 7.0; zh-CN; ZUK Z2121 Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.6.8.952 Mobile Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Mobile/15A372 MicroMessenger/6.5.17 NetType/WIFI Language/zh_HK",
            "Mozilla/5.0 (Linux; U; Android 6.0.1; zh-CN; SM-C7000 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.6.2.948 Mobile Safari/537.36",
            "MQQBrowser/5.3/Mozilla/5.0 (Linux; Android 6.0; TCL 580 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Mobile Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 10_2 like Mac OS X) AppleWebKit/602.3.12 (KHTML, like Gecko) Mobile/14C92 MicroMessenger/6.5.16 NetType/WIFI Language/zh_CN",
            "Mozilla/5.0 (Linux; U; Android 5.1.1; zh-cn; MI 4S Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.146 Mobile Safari/537.36 XiaoMi/MiuiBrowser/9.1.3",
            "Mozilla/5.0 (Linux; U; Android 7.0; zh-CN; SM-G9550 Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.7.0.953 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; Android 5.1; m3 note Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.116 Mobile Safari/537.36 T7/9.3 baiduboxapp/9.3.0.10 (Baidu; P1 5.1)"
        ];
        $url="http://m.ganxijsq.com/phone/crosscomplate?callback=success_jsonpCallback&phoneno=18715261528&host=http://m.ganxi360.net/&name=".urlencode('梁李良')."&note=".urlencode('单身倒计时看')."&_=1527141417976";
        $progxy=str_replace(PHP_EOL,'',file_get_contents("http://d.jghttp.golangapi.com/getip?num=1&type=1&pro=&city=0&yys=0&port=1&pack=240&ts=0&ys=0&cs=0&lb=1&sb=0&pb=4&mr=0&regions="));
        $ip=str_replace(substr($progxy,-5),'',$progxy);
        $location=Ip::find($ip);
        while(!isset($location[2]) || $location[2]=='A')
        {
            $progxy=str_replace(PHP_EOL,'',file_get_contents(""));
            $ip=str_replace(substr($progxy,-5),'',$progxy);
            $location=Ip::find($ip);
        }
        $phone=Phonesource::where('city',$location[2])->inRandomOrder()->first();
        $newphone=$phone->phone.rand(1000,9999);
        $webinfo=Webinfo::inRandomOrder()->first();
        $name=User::inRandomOrder()->first();
        //$url="https://www.kmway.com/jm/msgsubmit?URL=https://m.kmway.com/project/shfw/gx/741292.shtml&URLTitle=".urlencode($webinfo->title)."&ProjectID=741292&Name=".urlencode("$name->name")."&Tel={$newphone}&Message=&jsonpCallback=jsonpCallback&_=".time().rand(100,999);
        //dd($location[2],$phone->phone.rand(1000,9999),$webinfo->title);
        $referer="https://m.baidu.com/from=844b/bd_page_type=1/ssid=".substr(encrypt(rand(10,150)),-33)."/uid=0/pu=usm%401%2Csz%401320_2001%2Cta%40iphone_1_11.0_3_604/w=0_10_/t=iphone/l=1/tc?ref=www_iphone&lid=115726126565438".rand(10000,100000)."&order=1&fm=alop&waplogo=1&h5ad=0&tj=www_normal_1_0_10_title&vit=osres&waput=1&cltj=normal_title&asres=1&nt=wnor&title=".urlencode($webinfo->title)."&hwj=1584284466628849&dict=-1&wd=&eqid=a09a2de5947cc000100000025b0685ec&w_qd=IlPT2AEptyoA_ykw7fcb5uuu_yRSeo9mzC5Yf4vSxg9rRw95UExaym_&tcplug=1&sec=30009&di=31a5e6ed9859fe9f&bdenc=1&tch=124.0.270.174.0.0&nsrc=IlPT2AEptyoA_yixCFOxCGZb8c3JV3T5ABfPLiFD1y45nk_qva02E1RpWDLwRDrIBVL6wnX0sqdWeGGdWW1i7BB3rbIney6ylq&clk_info=%7B%22srcid%22%3A1599%2C%22tplname%22%3A%22www_normal%22%2C%22t%22%3A1527154162842%2C%22xpath%22%3A%22div-a-h3%22%7D";
        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);//跳过证书检查
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:$ip", "CLIENT-IP:$ip"));
        curl_setopt($ch, CURLOPT_PROXY, $progxy);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAngets[rand(0,count($userAngets)-1)]);
        //dd($progxy);
        $result=curl_exec($ch);
        Log::info('电话:'.$newphone.'姓名:'.$name->name.'IP地址:'.$ip.'地区:'.$location[2].'返回结果:'.$result);
        //dd(curl_error($ch));
        curl_close($ch);
    }


}
