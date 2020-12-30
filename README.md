# 腾讯云 CLS PB 格式打包

生成PB格式打包数据

附加签名代码

[案例](demo/simple_example.php)

更多细节请参考腾讯云CLS[官方文档](https://cloud.tencent.com/document/product/614/16873)

## 代码说明

src/Cls、src/GPBMetadata 是由 protobuf 3+ 版本生成，腾讯官方原版是 2.6+ 版本，3+ 生成后的代码不支持可选参数和必填参数， 需要开发者自行控制。目录大驼峰命名也是直接生成的，未做改变。

src/GPBMetadate/Cls.php 中的数据经过了转码处理，原文不转码无法通过代码仓库提交传输。

src/Sign.php
内容是下载的腾讯云官方提供的 [签名计算 demo](http://signature-1254139626.file.myqcloud.com/signature.zip?_ga=1.30193663.1374220542.1605231743)
未作改动。

## cls日志常见问题

### host

请参考 https://cloud.tencent.com/document/product/614/18940
注意非腾讯云服务器请选择选择外网地域，否则 dns 无法解析域名。

## 感谢

代码上传后出现损坏，经检查为 GPBMetadate/Cls.php 中含有 特殊数据，根据 https://github.com/f39516046/cls 的方法，将特殊数据转换为二进制字符串再解码解决无法传输问题。
