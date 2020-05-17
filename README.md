# 联合阅读用户资料爬虫

## 介绍

联合阅读（<http://unionread.vip>）用户资料爬虫

## 关于

作者博客：<https://blog.inuoy.cn>

## 实现说明

非常简单的爬虫， 用 file_get_contents 来读入，然后通过 simple_html_dom 来解析，最后导出 excel 到本地。没错就这么简单，希望联合阅读稳定下来以后一定要做好反爬虫。

## 使用说明

爬虫通过命令行运行（php cli），运行cdm并切换到爬虫目录

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517091557768.png)

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517091721649.png)

输入 php index.php（需要用到php运行环境）

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517092523507.png)

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517092553117.png)

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517092611358.png)

![爬虫目录](https://cdn.blog.inuoy.cn//2020/05/20200517093042774.png)