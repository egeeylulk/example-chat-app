<?php
namespace Home\Controller;
use Think\Controller;
use PhpMyAdmin\SqlParser\Components\Condition;

class WhatsappGroupChatController extends Controller{



    //ADMIN CREATES GROUP
    //1)

    public function creategroup()
    {
       
       $data=getJsondata();

        $group_name=$data->group_name;
        $id=$data->id;
        
        $wpgroupchat = M("wpgroupchat");
        $dataList[] = [
            "admin_id"=>$_SESSION["ID"],
            "group_name" => $group_name,
        ];
         
        
        $wpgroupchat->addAll($dataList);
        response(true, "Group Created.", true); 
    }   


    
    public function listgroup()
    {
        $data=getJsondata();

       $map ['groupUsers']=array('like',"%".$_SESSION["ID"]."%");
       $groupchat = M('wpgroupchat')->where($map)->select();
       
        echo "<bre>";
        var_dump($groupchat);
        die();
    }


    public function addUsersgroup(){
        $data=getJsondata();

        $groupId=M('wpgroupchat')->field("id")->where(["admin_id" => $_SESSION["ID"],
        ])->select();
        $groupid=$groupId[0]["id"];
        $addUser=$data->addUser;
        $dataList2[] = [
            "admin_id"=>$_SESSION["ID"],
            "group_id" => $groupid,
            "user_id" => $addUser,
        ];

        
        $wpgroupusers=M("wpgroupusers");
        $wpgroupusers->addAll($dataList2);

    }


    public function makeadmin()
    {
        $data=getJsondata();

        $pickgroup = $data->pickgroup;
        $pickusers = $data->pickusers;
        
        $wpgroupchat = M("wpgroupchat"); 
        
        $wpgroupchat->where("id=$pickgroup")->setField('admin_id', $pickusers );
        
        {
            response(true, "Kullanıcılar Başarıyla Gruba Eklendi.", true);
        } 
    }

    public function sendtextmessage(){
        $data=getJsondata();

        $groupId=M('wpgroupmessage')->field("id")->where(["sender_id" => $_SESSION["ID"],
        ])->select();
        $groupid=$groupId[0]["id"];
        $message=$data->message;
        $dataList3[] = [
            "sender_id"=>$_SESSION["ID"],
            "group_id" => $groupid,
            "message" => $message,
            
        ];
        

        
        $wpgroupmessage=M("wpgroupmessage");
        $wpgroupmessage->addAll($dataList3);
        response(true, "message sent to the group.", true);
        
    }

    public function sendphotomessage(){
        $data=getJsondata();
        $type=$data->type;
        $preview_content=$data->preview_content;

        $groupId=M('wpgroupchat')->field("id")->where(["sender_id" => $_SESSION["ID"],
        ])->select();
        $groupid=$groupId[0]["id"];
        $message=$data->message;
        $dataList3[] = [
            "sender_id"=>$_SESSION["ID"],
            "group_id" => $groupid,
            "message" => $message,
            "type" => 1,
            "preview_content" => $preview_content,

            
        ];
        

        
        $wpgroupmessage=M("wpmessages");
        $wpgroupmessage->addAll($dataList3);
    }

    public function sendvideomessage(){
        $data=getJsondata();
        $type=$data->type;
        $preview_content=$data->preview_content;

        $groupId=M('wpgroupchat')->field("id")->where(["sender_id" => $_SESSION["ID"],
        ])->select();
        $groupid=$groupId[0]["id"];
        $message=$data->message;
        $dataList3[] = [
            "sender_id"=>$_SESSION["ID"],
            "group_id" => $groupid,
            "message" => $message,
            "type" => 2,
            "preview_content" => $preview_content,

            
        ];
        
        

        
        $wpgroupmessage=M("wpmessages");
        $wpgroupmessage->addAll($dataList3);
    }
    public function sendstickermessage(){
        $data=getJsondata();
        $type=$data->type;
        $preview_content=$data->preview_content;

        $groupId=M('wpgroupchat')->field("id")->where(["sender_id" => $_SESSION["ID"],
        ])->select();
        $groupid=$groupId[0]["id"];
        $message=$data->message;
        $dataList3[] = [
            "sender_id"=>$_SESSION["ID"],
            "group_id" => $groupid,
            "message" => $message,
            "type" => 3,
            "preview_content" => $preview_content,

            
        ];
        $wpgroupmessage=M("wpmessages");
        $wpgroupmessage->addAll($dataList3);

    
}

public function addadmin()
    {
        $data=getJsondata();
        $user_id = $data->user_id;
        $id = $data->id;
        $group_id=$data->group_id;
        $pickusers = $data->pickusers;
        $pickgroup = $data->pickgroup;

        $addteacher = M("whatsappusers")
            ->where(["id" => $id])
            ->find();
        if (Count($addteacher) > 0) {
            response(false, "No .", false);
        }

        $director = M("wpgroupusers");
        $dataList[] = [
            "admin_id" => $pickusers,
            "group_id" => $pickgroup,
            "user_id" => $pickusers,
        ];
        $director->addAll($dataList);
        response(true, "Successfully Registered to the Admin Panel.", true);
    }

    public function sendtextmessagegroup(){
        $data=getJsondata();

        $message=$data->message;
        $group_id=$data->group_id;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $group_id=$data->group_id;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('wpgroupchat')->field("group_name")->where(["id" => $group_id,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
    
        $check = M("wpgroupmessage")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$group_id,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
        
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                ];
        
                $wpmessage->addAll($dataList);
                
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                response(true,"This is your first message",true);
            }


        } else {
            echo "Here is your messages";
            if($id == null || $id == ""){
            
                
        
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $group_id,
                ];
                   M('wpmessages')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpgroupmessage");
                $messageOld=M('wpgroupmessage')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpgroupmessage')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $group_id,
                    "reply"=>$reply,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    public function sendphotogroup(){
        $data=getJsondata();

        $message=$data->message;
        $group_id=$data->group_id;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;
        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $group_id,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
        
    
        $check = M("wpgroupmessage")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$group_id,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>1,
                ];
        
                $wpmessage->addAll($dataList);
                
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                response(true,"This is your first message",true);
            }


        } else {
            echo "Here is your messages";
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>1,
                ];
                   M('wpmessages')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpgroupmessage");
                $messageOld=M('wpmessages')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpgroupmessage')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>1,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    public function sendvideogroup(){
        $data=getJsondata();

        $message=$data->message;
        $group_id=$data->group_id;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $group_id,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
        
    
        $check = M("wpgroupmessage")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$group_id,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
        
                $wpmessage->addAll($dataList);
                
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                response(true,"This is your first message",true);
            }


        } else {
            echo "Here is your messages";
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
                   M('wpgroupmessage')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpgroupmessage");
                $messageOld=M('wpgroupmessage')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpgroupmessage')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>2,
                    
                   
                ];
                M('wpgroupmessage')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }


    public function sendstickergroup(){
        $data=getJsondata();

        $message=$data->message;
        $group_id=$data->group_id;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $group_id,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
    
        $check = M("wpgroupmessage")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$group_id,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
        
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
        
                $wpmessage->addAll($dataList);
                
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                response(true,"This is your first message",true);
            }


        } else {
            echo "Here is your messages";
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpgroupmessage");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
                   M('wpgroupmessage')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpgroupmessage");
                $messageOld=M('wpgroupmessage')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpgroupmessage')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "group_id" => $group_id,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>2,
                    
                   
                ];
                M('wpgroupmessage')->where(["sender_id"=>$group_id
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    public function readgroupmessage()
    {
        $data = getJsondata();

        $pickgroup = $data->pickgroup;
    
        $check = M("wpgroupmessage")
            ->field(["message","sender_id"])
            ->where([
                "group_id" =>$pickgroup,
                //"status"=>0
            ])
            ->select();
            var_dump(($check[0]["status"]));
        if ($check[0]["status"] == 0) {
            $groupmessage = M("wpgroupmessage");
            $groupmessage->where("group_id=$pickgroup")->setField("status",1);
            response($check, true, true);
        }
    }


    public function leavegroup()
    {
        $data = getJsondata();

       $pickgroup = $data->pickgroup;

       $check= M("wpgroupusers")
       ->field(["user_id"])
       ->where(["group_id"=>$pickgroup])
       ->select();

       $a = explode(' ', $check);
      
       //var_dump($a);
        //die();


   $groupchat = M("wpgroupusers");
   $groupchat->where("group_id=$pickgroup")->setField("group_id",$check[$_SESSION["user_id"]]["group_id"]);

   response( $_SESSION["user_id"], "Gruptan Başarıyla Çıkıldı.",true);
}



public function seeoldmessagesgroup(){
    $data=getJsondata();

    $list=$data->list; 
    $message=$data->message;
    $receiver_id=$data->receiver_id;
    $group_id=$data->group_id;

    $check = M("wpgroupmessage")
    ->field(["date","message","sender_id","reply","preview_content"])
    ->where
            (["group_id"=>$group_id,
            "status"=>1,])
    ->select();

    if (count($check) == 0) {
        response(false, "There isn't any message to list", false);
    } else {
        response($check, "Here is your messages", true);
    }

}



}

?>