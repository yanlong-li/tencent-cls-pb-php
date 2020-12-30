<?php

use Cls\Log;
use Cls\Log\Content;
use Cls\LogGroup;
use Cls\LogGroupList;
use Cls\LogTag;
use yanlongli\TencentClsSign\TencentClsSign;

include_once '../vendor/autoload.php';


// name 属性
$nameContent = new Content();
$nameContent->setKey("name");
$nameContent->setValue("张三");
// get 属性
$ageContent = new Content();
$ageContent->setKey("age");
$ageContent->setValue(18);

// sex属性
$sexContent = new Content();
$sexContent->setKey("sex");
$sexContent->setValue(0);// 0 or 1

$log = new Log();
// 必填，否则会提示 InvalidContent
$log->setTime(time());
// 数组，content 相当于 JSON 中的一个字段 + 值
$log->setContents([$nameContent, $ageContent, $sexContent]);

// 以上内容会生成如下 json 数据
// {"name":"张三","age":18,"sex":0}

$logGroup = new LogGroup();
// 数组格式，多个 log 会同时生成多条日志记录,批量上传输时可根据上面的代码生成多个 log,并用数组封装
$logGroup->setLogs([$log]);
$logGroup->setFilename("filename.txt");
$logGroup->setSource("source");

// tag 可选
$tag = new LogTag();
$tag->setKey("tagKey");
$tag->setValue('tagValue');
// 数组格式，同 Content
$logGroup->setLogTags([$tag]);

// 封装日志集合
$logGroupList = new LogGroupList();
$logGroupList->setLogGroupList([$logGroup]);
// 将日志集生成 RAW 数据，注意这里返回的字符串中有特殊字符，打印出来是乱码的，可以直接 post 提交
$rawData = $logGroupList->serializeToString();

//如果想看数据可以使用 $logGroupList->serializeToJsonString()


$host = 'ap-guangzhou.cls.tencentcs.com';
$secretId = '';
$secretKey = '';
$topicId = '';

$path = '/structuredlog';
$headers = [
    'content-type' => 'application/x-protobuf',
    'host' => $host,
];
$headers['Authorization'] = TencentClsSign::signature(
    $secretId,
    $secretKey,
    'POST',
    $path,
    [
        'topic_id' => $topicId
    ],
    $headers
);


$url = 'https://' . $host . $path . '?topic_id=' . $topicId;

// GuzzleHttp\Client,其它的 http 库 或 curl 请另行查找文档
$client->request('POST', $url, [
        'headers' => $headers,
        'body' => $rawData,
    ]
);
