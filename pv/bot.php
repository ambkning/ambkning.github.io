<?php
ob_start();
error_reporting(0);
date_default_timezone_set("Asia/Tehran");
$telegram_ip_ranges = [['lower' => '149.154.160.0','upper' => '149.154.175.255'],['lower' => '91.108.4.0', 'upper' => '91.108.7.255']];
    $ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
    $ok = false;
foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) { $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower'])); 
    $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok = true;
}
if (!$ok)die();
///////////////////
define('API_TOKEN' , '7294172678:AAHeIH5yVnuhF9Zz7QFDqF-y727ytyTH-ks');
//////////////////
function bot($method,$datas=[]){$ch = curl_init();curl_setopt($ch,CURLOPT_URL,'https://api.telegram.org/bot'.API_TOKEN.'/'.$method );curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);return json_decode(curl_exec($ch));
}
function sm($type ,$ss,$chatid,$text,$keyboard=null,$parse_mode= markdown,$disable_web_page_preview=false){
    bot('send'.$type,[
        'chat_id'=>$chatid,
        $ss =>$text,
        'parse_mode'=>$parse_mode,
        'disable_web_page_preview'=>$disable_web_page_preview,
        'reply_markup'=>$keyboard
        ]);
} 
function fm($chatid,$userid,$message){
    bot('ForwardMessage',[
        'chat_id'=>$chatid,
        'from_chat_id'=>$userid,
        'message_id'=>$message
        ]);
}
function sp($chatid,$file_id,$message= null,$parse_mode= markdown){
    bot('sendphoto', [
        'chat_id' => $chat_id,
        'photo' => $file_id,
        'caption' =>$message,
        'parse_mode' => $parse_mode
        ]);
}
function em($chatid,$text,$message,$keyboard=null,$disable_web_page_preview=false,$parse_mode =  markdown){
    bot('EditMessageText',[
        'chat_id'=>$chatid,
        'text'=>$text,
        'message_id'=>$message,
        'parse_mode'=>$parse_mode,
        'disable_web_page_preview'=>$disable_web_page_preview,
        'reply_markup'=>$keyboard
        ]);
} 
function erm($chatid,$message,$reply_markup){
    bot('editMessageReplyMarkup',[
        'chat_id'=>$chatid,
        'message_id'=>$message,
        'reply_markup'=>$reply_markup
        ]);
}
function json($file,$data){
    $data = json_encode($data,448);
	file_put_contents($file,$data);
}
function step($user2 ,$step = none){
    $user = json_decode(file_get_contents('data/'.$user2.'.json'),true);
    $user['step'] = $step;
	json('data/'.$user2.'.json', $user);
}
///////////////////
$us = bot('GetMe')->result->username;
$users = json_decode(file_get_contents('users.json'),true);
$data = json_decode(file_get_contents('data.json'),true);
if($data['lock']['forward_from'] == null)$data['lock']['forward_from'] = '🔓';
if($data['lock']['file'] == null)$data['lock']['file'] = '🔓';
if($data['lock']['video'] == null)$data['lock']['video'] = '🔓';
if($data['lock']['photo'] == null)$data['lock']['photo'] = '🔓';
if($data['lock']['audio'] == null)$data['lock']['audio'] = '🔓';
if($data['lock']['voice'] == null)$data['lock']['voice'] = '🔓';
if($data['lock']['sticker'] == null)$data['lock']['sticker'] = '🔓';
if($data['lock']['text'] == null)$data['lock']['text'] = '🔓';
if($data['power'] == null)$data['power'] = 'on';
if($data['start'] == null)$data['start'] = '🤖 سلام به ربات من خوش اومدی!';
if($data['profile'] == null)$data['profile'] = 'تنظیم نشده!';
$admin = ''; # ایدی عددی ادمین
$admin2 = ['[[*[5738171226]*]]' , null , null]; # ایدی عددی ادمین به صورت آرایه
$Update = json_decode(file_get_contents('php://input'),1);
if(isset($Update['message'])){
    $Message = $Update['message'];
    $MessageId = $Message['message_id'];
    $Text = $Message['text'];
    $ChatId = $Message['chat']['id'];
    $FromId = $Message['from']['id'];
    $FirstName = $Message['from']['first_name'];
    $UserName = $Message['from']['username'];
    $Contact  = $Message['contact'];
    $Phone_number  = $Message['contact']['phone_number'];
    $First  = $Message['contact']['first_name'];
    $user = json_decode(file_get_contents('data/'.$ChatId.'.json'),true);
    $one = json_decode(file_get_contents('https://api.telegram.org/botTOKEN/getChatMember?chat_id=@'.$data['channel'].'&user_id='.$ChatId),true)['result']['status'];
} else if(isset($Update['callback_query'])){
    $Callback = $Update['callback_query'];
    $Data = $Callback['data'];
    $MessageId = $Callback['message']['message_id'];
    $FromId = $Callback['from']['id'];
    $ChatId = $Callback['chat']['id'];
    $user = json_decode(file_get_contents('data/'.$FromId.'.json'),true);
    $one = json_decode(file_get_contents('https://api.telegram.org/botTOKEN/getChatMember?chat_id=@'.$data['channel'].'&user_id='.$FromId),true)['result']['status'];
}
///////////////////
$main = json_encode(['inline_keyboard'=>[
[['text'=>"📬 پروفایل",'callback_data'=>"profile"],['text'=>"☎️ پشتیبانی",'callback_data'=>"support"]],(in_array($FromId,$admin2)?[['text'=>'🔑 ورود به پنل','callback_data'=>"manage"]]:[])
]]);
$back = json_encode(['inline_keyboard'=>[
[['text'=>"🔙 بازگشت",'callback_data'=>"back"]],
]]);	
$answer = json_encode(['inline_keyboard'=>[
[['text'=>"🌟 پاسخ 🌟",'callback_data'=>"answer-$ChatId"]],
]]);	
$panel = json_encode(['inline_keyboard'=>[
[['text'=>"📊 آمار",'callback_data'=>"statistics"],['text'=>"📦 تنظیمات",'callback_data'=>"setting"]],
[['text'=>"📩 پیام همگانی",'callback_data'=>"sendmessage"],['text'=>"📨 فروارد همگانی",'callback_data'=>"forwardmessage"]],
[['text'=>"🔐 قفل ها",'callback_data'=>"locks"],['text'=>"📣 تنظیم کانال",'callback_data'=>"setchannel"]],
[['text'=>"📳 روشن",'callback_data'=>"on"],['text'=>"📴 خاموش",'callback_data'=>"off"]],
[['text'=>"🔙 بازگشت",'callback_data'=>"back"]],
]]);
$setting = json_encode(['inline_keyboard'=>[
[['text'=>"✏️ تنظیم متن استارت",'callback_data'=>"text-start"],['text'=>"✏️ تنظیم متن پروفایل",'callback_data'=>"text-profile"]],
[['text'=>"🔙 بازگشت",'callback_data'=>"back"]],
]]);
$locks = json_encode(['inline_keyboard'=>[
[['text'=>"🔁 فورواد",'callback_data'=>"forward"],['text'=>$data['lock']['forward_from'],'callback_data'=>"lock-forward_from"]],
[['text'=>"🗂 فایل",'callback_data'=>"file"],['text'=>$data['lock']['file'],'callback_data'=>"lock-file"]],
[['text'=>"🎥 فیلم",'callback_data'=>"video"],['text'=>$data['lock']['video'],'callback_data'=>"lock-video"]],
[['text'=>"📸 عکس",'callback_data'=>"photo"],['text'=>$data['lock']['photo'],'callback_data'=>"lock-photo"]],
[['text'=>"🎙 وویس",'callback_data'=>"voice"],['text'=>$data['lock']['voice'],'callback_data'=>"lock-voice"]],
[['text'=>"🎧 آهنگ",'callback_data'=>"audio"],['text'=>$data['lock']['audio'],'callback_data'=>"lock-audio"]],
[['text'=>"📷 استیکر",'callback_data'=>"sticker"],['text'=>$data['lock']['sticker'],'callback_data'=>"lock-sticker"]],
[['text'=>"✏️ متن",'callback_data'=>"text"],['text'=>$data['lock']['text'],'callback_data'=>"lock-text"]],
[['text'=>"🔙 بازگشت",'callback_data'=>"back"]],
]]);
///////////////////
if($data['power'] != 'on' && !in_array($FromId,$admin2)) { 
    SM('message','text',$ChatId,'ربات خاموش میباشد!');
    exit;
}
if($Text == '/start'){
    if(!in_array($FromId,$users)){
        $user['step'] = 'none';
        json('data/'.$FromId.'.json', $user);
        $users[] = $FromId;
        json('users.json' , $users);
    }
    step($FromId);
    SM('message','text',$FromId,$data['start'],$main);
}
if(!in_array($one , ['member' , 'creator' , 'administrator']) && !in_array($FromId,$admin2) && $data['channel'] != null){
    SM('message','text',$FromId,"🏷 جهت استفاده از ربات ، ابتدا باید درکانال ما عضو شوید!\n@{$data['channel']}");
    return false;
}
if(preg_match('/^(lock-(.*))/',$Data,$match)){
    if($data['lock'][$match[2]] == '🔓')$as= '🔐'; else $as= '🔓';
    $data['lock'][$match[2]] = $as;
    json('data.json', $data);
    if($as == '🔐')$lock = 'فعال'; else $lock = 'غیر فعال';
    em($FromId , 'قفل مورد نظر '.$lock . ' شد!',$MessageId,$panel);
}
if(preg_match('/^(text-(.*))/',$Data,$match)){
    step($FromId,'set-'.$match[2]);
    if($data[$match[2]] != null)$data[$match[2]] = $data[$match[2]]; else $data[$match[2]] = 'تنظیم نشده است!';
    em($FromId,"🏷 متن جدید را وارد کنید!\nمتن فعلی :\n{$data[$match[2]]}",$MessageId,$back);
}
if(isset($Text) && preg_match('/^(set-(.*))/',$user['step'],$match) && $Text != '/start'){
    step($FromId);
    $data[$match[2]] = $Text;
    json('data.json', $data);
    sm('message','text',$ChatId,'تنظیم شد.',$setting);
}
if($user['step'] == 'support' && $Text != '/start'){
    step($FromId);
     if(isset($Message['forward_from'])){
         if($data['lock']['forward_from'] != '🔐'){
        sm('message','text',$ChatId,'✅ پیام شما باموفقیت فروارد شد! لطفا منتظر پاسخ خود باشید.',$back);
        fm($admin,$ChatId,$MessageId);
        sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
    } else {
        sm('message','text',$ChatId,"🔐 قفل فوروارد فعال است!");
    }
     }
    if(isset($Message['document'])){
        if($data['lock']['document'] != '🔐'){
        $file_id = $Message['document']['file_id'];
        sm('message','text',$ChatId,'✅ فایل شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
        sm('document','document',$admin,$file_id);
        sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
    } else {
        sm('message','text',$ChatId,"🔐 قفل فایل فعال است!");
    }
    }
    if(isset($Message['video']) && $data['lock']['video'] != '🔐'){
        if($data['lock']['video'] != '🔐'){
       $file_id = $Message['video']['file_id'];
        sm('message','text',$ChatId,'✅ ویدیو شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
        sm('video','video',$admin,$file_id);
        sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
    } else {
        sm('message','text',$ChatId,"🔐 قفل فیلم فعال است!");
    }
    }
    if(isset($Message['photo'])){
        if($data['lock']['photo'] != '🔐'){
       sm('message','text',$ChatId,'✅ عکس شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
       fm($admin,$ChatId,$MessageId);
       sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
       } else {
           sm('message','text',$ChatId,"🔐 قفل عکس فعال است!");
       }
    }
       if(isset($Message['voice'])){
           if($data['lock']['voice'] != '🔐'){
           $file_id = $Message['voice']['file_id'];
           sm('message','text',$ChatId,'✅ صدا شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
           sm('voice','voice',$admin,$file_id);
           sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
       } else {
           sm('message','text',$ChatId,"🔐 قفل صدا فعال است!");
       }
       }
    if(isset($Message['audio'])){
        if($data['lock']['audio'] != '🔐'){
        $file_id = $Message['audio']['file_id'];
        sm('message','text',$ChatId,'✅ فایل صوتی شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
        sm('audio','audio',$admin,$file_id);
        sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
    } else {
        sm('message','text',$ChatId,"🔐 قفل اهنگ فعال است!");
    }
    }
    if(isset($Message['sticker'])){
        if($data['lock']['sticker'] != '🔐'){
        $file_id = $Message['sticker']['file_id'];
        sm('message','text',$ChatId,'✅ استیکر شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
        sm('sticker','sticker',$admin,$file_id);
        sm('message','text',$admin,"🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
    } else {
           sm('message','text',$ChatId,"🔐 قفل استیکر فعال است!");
       }
    }
       if(isset($Message['text']) && $Text != '/start'){
           if($data['lock']['text'] != '🔐'){
           sm('message','text',$ChatId,'✅ پیام شما باموفقیت ارسال شد! لطفا منتظر پاسخ خود باشید.',$back);
           sm('message','text',$admin,"🔐 پیام جدید دریافت شد!\n\n🔖 متن پیام :\n$Text\n\n🎫 ارسال کننده : [$FromId](tg://user?id=$FromId)",$answer);
       } else {
           sm('message','text',$ChatId,"🔐 قفل متن فعال است!");
    }
       }
}
if(preg_match('/^(answer-(.*))/',$Data,$match)){
    step($FromId,'answer-'.$match[2]);
    em($FromId,"🔐 لطفا پیام خود را برای ارسال به [{$match[2]}](tg://user?id={$match[2]}) ارسال کنید.",$MessageId,$back);
} else if(preg_match('/^(answer-(.*))/',$user['step'] ,$match)){
        if(isset($Message['document'])){
        $file_id = $Message['document']['file_id'];
        sm('message','text',$ChatId,'sent!',$back);
        sm('document','document',$match[2],$file_id);
    } 
   else if(isset($Message['video'])){
       $file_id = $Message['video']['file_id'];
        sm('message','text',$ChatId,'sent!',$back);
        sm('video','video',$match[2],$file_id);
    } 
   else if(isset($Message['photo'])){
       sm('message','text',$ChatId,'sent!',$back);
       fm($match[2],$ChatId,$MessageId);
       } 
   else if(isset($Message['voice'])){
       $file_id = $Message['voice']['file_id'];
       sm('message','text',$ChatId,'sent!',$back);
       sm('voice','voice',$match[2],$file_id);
    } 
   else if(isset($Message['audio'])){
       $file_id = $Message['audio']['file_id'];
       sm('message','text',$ChatId,'sent!',$back);
       sm('audio','audio',$match[2],$file_id);
    } 
   else if(isset($Message['sticker'])){
       $file_id = $Message['sticker']['file_id'];
       sm('message','text',$ChatId,'sent!',$back);
       sm('sticker','sticker',$match[2],$file_id);
       } 
   else if(isset($Message['text']) && $Text != '/start'){
        sm('message','text',$ChatId,'sent!',$back);
        sm('message','text',$match[2],$Text);
    } 
    step($FromId);
}
switch($Data){
    case 'support';
    step($FromId,'support');
    em($FromId,'🔖 لطفا پیام خود را ارسال کنید!',$MessageId,$back);
    break;
    case 'back';
    step($FromId);
    em($FromId,'به منوی اصلی باز گشتید!',$MessageId,$main);
    break;
    case 'sendmessage';
    step($FromId,'sm');
    em($FromId,"🔸 لطفا پیام خود را ارسال کنید.",$MessageId,$back);
    break;
    case 'forwardmessage';
    step($FromId,'fm');
    em($FromId,"🔸 لطفا پیام خود را ارسال کنید.",$MessageId,$back);
    break;
    case 'setchannel';
    step($FromId,'setchannel');
    if($data['channel'] != null)$data['channel'] = '@'.$data['channel']; else $data['channel'] = 'تنظیم نشده است!';
    em($FromId,"🔰 لطفا آیدی کانال را بدون '@' ارسال کنید!\n ‼️ توجه : ابتدا ربات را ادمین کانال کنید.\nدرصورتی که قصد حذف قفل کانال را دارید عدد '0' را ارسال کنید!\n کانال فعلی : {$data['channel']}",$MessageId,$back);
    break;
    case 'statistics';
    $time = \date('H:i:s');
    $members = \count($users);
    em($FromId,"📚 امار ربات در ساعت $time برابر است با $members کاربر!",$MessageId,$panel);
    break;
    case 'manage';
    em($FromId,'👇🏻 به پنل مدیریت خوش آمدید',$MessageId,$panel);
    break;
    case 'locks';
    em($FromId,'⚙️ به منوی تنظیمات قفل ها خوش آمدید.',$MessageId,$locks);
    break;
    case 'setting';
    em($FromId,'🏷 به منوی تنظیمات خوش آمدید.',$MessageId,$setting);
    break;
    case 'profile';
    em($FromId,$data['profile'],$MessageId,$back);
    break;
    case 'on';
    if($data['power'] == 'on'){
        em($FromId,'ربات روشن بود',$MessageId,$panel);
    } else {
        $data['power'] = on;
        json('data.json', $data);
        em($FromId,'ربات با موفقیت روشن شد!',$MessageId,$panel);
    }
    break;
    case 'off';
    if($data['power'] == 'off'){
        em($FromId,'ربات خاموش بود',$MessageId,$panel);
    } else {
        $data['power'] = off;
        json('data.json', $data);
        em($FromId,'ربات با موفقیت خاموش شد!',$MessageId,$panel);
    }
    break;
}
if($FromId == $admin){
    if(in_array($Text,['/panel','/admin','/manage','panel','admin','manage','پنل','پنل مدیریت','مدیریت','مدیر'])){
    sm('message','text',$ChatId,'👇🏻 به پنل مدیریت خوش آمدید',$panel);
    }
     switch($user['step']){
         case 'sm';
         if($Text != '🔙 بازگشت' || $Text != '/start'){
         step($FromId,'none');
         $members = \count($users);
         sm('message','text',$ChatId,"درحال ارسال پیام به $members کاربر.\nلطفا صبور باشید!");
         foreach($users as $userss)
         {
             sm('message','text',$userss,$Text);
         }
         sm('message','text',$ChatId,"ارسال پیام به پایان رسید!",$panel);
         }
         break;
         case 'fm';
         if($Text != '🔙 بازگشت' || $Text != '/start'){
         step($FromId,'none');
         $members = \count($users);
         sm('message','text',$ChatId,"درحال فروارد پیام به $members کاربر.\nلطفا صبور باشید!");
         foreach($users as $userss)
         {
             fm($userss,$ChatId,$MessageId);
         }
         sm('message','text',$ChatId,"فروارد پیام به پایان رسید!",$panel);
         }
         break;
         case 'setchannel';
         step($ChatId);
         if($Text == '0' && $Text != '/start'){
             unset($data['channel']);
             $data['channel'] = array_values($data['channel']);
             json('data.json' , $data);
             sm('message','text',$ChatId,"قفل کانال باموفقیت حذف شد!",$panel);
         } else {
             $Text = str_replace('@',null,$Text);
             $data['channel'] = $Text;
             json('data.json', $data);
             sm('message','text',$ChatId,"کانال @$Text باموفقیت به عنوان قفل کانال تنظیم شد!",$panel,html);
         }
         break;
    }
}
