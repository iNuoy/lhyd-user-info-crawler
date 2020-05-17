<?php
echo "+----------------------------------------------------------------------\n";
echo "| Copyright (c) 2020 Inuoy\n";
echo "+----------------------------------------------------------------------\n";
echo "| Author: Inuoy <admin@inuoy.cn>\n";
echo "+----------------------------------------------------------------------\n";
echo "| Blog: https://blog.inuoy.cn\n";
echo "+----------------------------------------------------------------------\n";
echo "| Time: 2020-05-16\n";
echo "+----------------------------------------------------------------------\n";

include_once('./export_excel.php');
include_once('./simple_html_dom.php');

// 执行时间为无限制，php默认执行时间是30秒，可以让程序无限制的执行下去 
set_time_limit(0); 

// 即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行. 
ignore_user_abort(); 

// 读入开始爬取用户id
echo '请输入要开始爬取的用户id:';
$startUid = (int)fgets(STDIN);  // 开始爬取的用户id
echo 'startUid: ' . $startUid."\n";

// 读入结束爬取的用户id
echo '请输入要结束爬取的用户id（注意，建议一次不要爬太多，我电脑爬一万行数据花了一小时多）:';
$endUid = (int)fgets(STDIN);    // 结束爬取的用户id
echo 'endUid: ' . $endUid."\n\n\n\n\n";

// 判断开始、结束uid是否有效
if($startUid == $endUid) {
    echo '开始、结束用户uid不能一样。  提前结束爬取';
    exit();
}
if($startUid > $endUid) {
    echo '结束用户uid必须大于开始用户uid。  提前结束爬取';
    exit();
}

$nothingCount = 0;
$count = $startUid-1;
$userArr = [];

echo "------------------------------- 爬取开始 -------------------------------\n";
for($i = 0;$i < $endUid - $startUid;$i++) {
    $count++;
    $url = 'http://unionread.vip/module/user/fhome.php?uid='.$count; // 拼接爬取的网页
    $htmlStr = file_get_contents($url);
    
    // 判断该用户是否存在
    if(strpos($htmlStr,'对不起，该用户不存在！') !== false) {
        $userArr[$i] = -1; // 该用户不存在

        // 提示
        echo "当前爬取第: ".($i+1)." 行数据，uid:".$count."，该用户不存在！\n";

        // 记录有多少用户不存在
        $nothingCount++;
        continue;
    }

    // 进度提示
    echo "当前爬取第: ".($i+1)." 行数据，uid:".$count."\n";
    
    $html = new simple_html_dom();
    $html->load($htmlStr);
    
    // uid
    $param['uid'] = $count;
    // 名称
    $name = $html->find(".hm .mbn", 0);
    $param['name'] = $name->innertext;
    // 头像
    $avatar = $html->find(".hm p", 0);
    $param['avatar'] = 'http://unionread.vip'.$avatar->children[0]->attr['src'];
    // 性别
    $sex = $html->find("#pprl li", 0);
    $param['sex'] = str_replace("<em>性别</em>","", $sex->innertext);
    // 年龄
    $age = $html->find("#pprl li", 1);
    $param['age'] = str_replace("<em>年龄</em>","", $age->innertext);
    // 签名
    $sign = $html->find("#pprl li", 4);
    $param['sign'] = str_replace("<em>签名</em>","", $sign->innertext);
    // 最后登录时间
    $endTime = $html->find("#pprl li", 5);
    $param['endTime'] = str_replace("<em>最后登录时间</em>","", $endTime->innertext);
    // 注册时间
    $registerTime = $html->find("#pprl li", 6);
    $param['registerTime'] = str_replace("<em>注册时间</em>","", $registerTime->innertext);
    
    $userArr[$i] = $param;

    // 检查是否不存在用户超过100，如果是那么结束爬取
    if($nothingCount > 100) { 
        echo "警告：爬取不存在用户数量超过 100 名，提前结束爬取。\n";
        break;
    }

    // 阻塞(避免爬取速度过快)
    sleep(0.5);
}
echo "------------------------------- 爬取结束 -------------------------------\n";

// 导出 excel
echo "------------------------------ 导出 excel ------------------------------\n";
$title = array('标识','昵称','性别','年龄','签名','最后登录时间','注册时间','头像链接');
$arr = [];
for($i = 0;$i < count($userArr);$i++) {
    if($userArr[$i] == -1) { continue; }
    $arr[] = array(
        $userArr[$i]['uid'],
        $userArr[$i]['name'],
        $userArr[$i]['sex'],
        $userArr[$i]['age'],
        $userArr[$i]['sign'],
        $userArr[$i]['endTime'],
        $userArr[$i]['registerTime'],
        $userArr[$i]['avatar']
    );
}

$res = export_excel($arr, $title, time().' 联合阅读用户名单', './联合阅读用户名单/');
if($res == 1) {
    echo "------------------------------- 导出成功 -------------------------------\n";
} else {
    echo '---------------------- 创建目录失败，请给予相应权限 ----------------------\n';
}