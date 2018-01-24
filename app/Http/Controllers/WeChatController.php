<?php

namespace App\Http\Controllers;

use Log;
use EasyWeChat;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        //Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        //企业号应用
        $app = app('wechat.work');
        $app->server->push(function ($message) {
            //Log::info($message);
            switch ($message['MsgType']) {
                case 'event':
                    return "I am your xiongchao daddy , daddy love you forever!!!";
                    break;
                case 'text':
                    return '收到文字消息: ' . $message['Content'];
                    break;
                case 'image':
                    $items = [
                        new EasyWeChat\Kernel\Messages\NewsItem([
                            'title' => "Image",
                            'description' => '收到图片消息',
                            'url' => $message['PicUrl'],
                            'image' => $message['PicUrl'],
                            // ...
                        ]),
                    ];
                    return new EasyWeChat\Kernel\Messages\News($items);
                    break;
                case 'voice':
                    //return '收到语音消息:';
                    return new EasyWeChat\Kernel\Messages\Voice($message['MediaId']);
                    break;
                case 'video':
                   // return '收到视频消息';
                    $video = new EasyWeChat\Kernel\Messages\Video($message['MediaId'], [
                        'title' => "your movie",
                        'description' => 'send your movie to you',
                    ]);
                    return $video;
                    break;
                case 'location':
                    return '微信目前不支持回复坐标消息';
                    break;
                case 'link':
                    return '微信目前不支持回复链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        return $app->server->serve();
    }

    /**
     * send message test
     */
    public function send()
    {
        $work = EasyWeChat::work(); // 企业微信
        $result=$work["message"]->send(array(
            "touser" => 'XiongChao|likangpeng|wangkai|weizhicheng|huqing|test|demo',
            "msgtype" => "text",
            "agentid" => 1000003,
            "text" => array(
                "content" => "test",
            ),
            "safe" => 0
        ));
        dd($result);
    }
}
