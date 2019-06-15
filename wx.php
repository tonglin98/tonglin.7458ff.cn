<?php
//定义常量token 
define('TOKEN','liutonglin'); 
// 定义消息模板
$_msg_template = array(
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><MsgId>1234567890123456</MsgId></xml>',//文本回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',//图片回复XML模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',//音乐模板
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',// 新闻主体
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',//某个新闻模板
);
function _response_text($object,$content){
    $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>%d</FuncFlag>
                </xml>";
    $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $object->MsgId);
    die($resultStr);
}


function msgText($to, $from, $content, $msgid) {
  		$time = time();
        $response = "<xml><ToUserName><![CDATA[$to]]></ToUserName><FromUserName><![CDATA[$from]]></FromUserName><CreateTime>$time</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[$content]]></Content><MsgId>$msgid</MsgId></xml>";
  		file_put_contents('sendxml.txt',json_encode($response));
        die($response);
}
    
//检查标签
    function checkSignature()
    {
        //先获取到这三个参数
        $signature = $_GET['signature'];   
        $nonce = $_GET['nonce']; 
        $timestamp = $_GET['timestamp']; 
 
              //把这三个参数存到一个数组里面
        $tmpArr = array($timestamp,$nonce,TOKEN); 
        //进行字典排序
        sort($tmpArr);  
    
        //把数组中的元素合并成字符串，impode()函数是用来将一个数组合并成字符串的
        $tmpStr = implode($tmpArr);  
 
        //sha1加密，调用sha1函数
               $tmpStr = sha1($tmpStr);
        //判断加密后的字符串是否和signature相等
        if($tmpStr == $signature) 
        {
            
            return true;
        }
        return false;
    }


    //如果相等，验证成功就返回echostr
    if(checkSignature())
     {    
        //返回echostr
        $echostr = $_GET['echostr'];
        if($echostr)   
        {
            echo $echostr;
        }
    }
	$xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
	
	file_put_contents('msg.txt',$xml_str);
	// 如果 没有消息则返回空
	if(empty($xml_str)){
            die('');
    }else{
    	// 解析XML
     	libxml_disable_entity_loader(true);
        //禁止xml实体解析，防止xml注入
        $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
      	file_put_contents('msg2.txt',json_encode($request_xml));
      	// 判断数据
      	 switch ($request_xml->MsgType){
                case 'event':
                    //判断具体的时间类型（关注、取消、点击）
                    $event = $request_xml->Event;
                      if ($event=='subscribe') { // 关注事件
                          
                      }elseif ($event=='CLICK') {//菜单点击事件
                          
                      }elseif ($event=='VIEW') {//连接跳转事件
                         
                      }else{

                      }
                    break;
                case 'text'://文本消息
             		$text = $request_xml->Content;
					$res = file_get_contents('http://api.qingyunke.com/api.php?key=free&appid=0&msg='.$text);
					$res = json_decode($res);
                    echo _response_text($request_xml,$res->content);
                    break;
                case 'image'://图片消息
                
                    break;
                case 'voice'://语音消息
                   
                    break;
                case 'video'://视频消息
                    
                    break;
                case 'shortvideo'://短视频消息
                    
                    break;
                case 'location'://位置消息
                   
                    break;
                case 'link'://链接消息
                 
                    break;
            }        
    
    }

	
 
?>