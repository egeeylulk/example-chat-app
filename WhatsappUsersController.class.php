<?php
namespace Home\Controller;
use Think\Controller;
use PhpMyAdmin\SqlParser\Components\Condition;


class WhatsappUsersController extends Controller{


    public function registration(){
        $data=getJsondata();
        $name=$data->name;
        $surname=$data->surname;
        $tel_no=$data->tel_no;

        if(is_null($name) || is_null($surname) || is_null($tel_no)){
            response(false,"Please do not leave empty",false);
        }
        if(strtoupper($name)!=$name){
            response(false,"Name must be in uppercase.",false);
        }
        if(strtoupper($surname)!=$surname){
            response(false,"Surname must be in uppercase.",false);
        }
        if(strlen((string)$tel_no)!=10){
            response(false,"Telephone Number must be 10 digits.",false);
        }

        $check = M("whatsappusers")
            ->where(["tel_no" => $tel_no])
            ->find();
        if (Count($check) > 0) {
            response(
                false,
                "This Telephone Number Has Been Registered Before.",
                false
            );
        }
        //IF EVERYTHING IS OKAY, IT WILL ADD THE USER TO THE DATABASE
        else {
            $wpusers = M("whatsappusers");
            $dataList[] = [
                "name" => strtolower($name),
                "surname" => strtolower($surname),
                "tel_no" => $tel_no,
                //"password" =>md5($password),
               
            ];
            $wpusers->addAll($dataList);
            response(true, "Registered.", true);
        }

    }


    public function login(){
        $data=getJsondata();
        $tel_no=$data->tel_no;

        $check = M("whatsappusers")
            ->where([
                "tel_no" => $tel_no,
            ])->select();

        if (count($check) == 0) {
            response(false, "Phone is wrong", false);
        }

        Session("ID", $check[0]["id"]);
        var_dump(session("ID"));
        //die();

        response(true, "WELCOME.", true);
    }


    public function logout()
    {
        session('ID', null);
        response(false, "You Exit", false);
    }

    //CHAT FUNCTIONS
    //1) SEND TEXT MESSAGE

    public function sendtextmessage(){
        $data=getJsondata();

        $message=$data->message;
        $receiver=$data->receiver;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $receiver,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$receiver,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
        
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
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
                    "receiver_id" => $receiver,
                ];
                   M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpmessages");
                $messageOld=M('wpmessages')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpmessages')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "reply"=>$reply,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    //2) SEND PHOTO

    public function sendphoto(){
        $data=getJsondata();

        $message=$data->message;
        $receiver=$data->receiver;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;
        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $receiver,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
        
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$receiver,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
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
                    "receiver_id" => $receiver,
                    "preview_content" => $preview_content,
                    "type"=>1,
                ];
                   M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpmessages");
                $messageOld=M('wpmessages')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpmessages')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>1,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    //3) SEND VIDEO

    public function sendvideo(){
        $data=getJsondata();

        $message=$data->message;
        $receiver=$data->receiver;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $receiver,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
        
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$receiver,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
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
            
                
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
                   M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpmessages");
                $messageOld=M('wpmessages')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpmessages')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>2,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    //4) SEND STICKER

    public function sendsticker(){
        $data=getJsondata();

        $message=$data->message;
        $receiver=$data->receiver;
        $name=$data->name;
        $id=$data->id;
        $reply=$data->reply;
        $sender_id=$data->sender_id;
        $preview_content=$data->preview_content;

        $namesender=M('whatsappusers')->field("name")->where(["id" => $_SESSION["ID"],
                ])->select();
                //var_dump( $name[0]["name"]);
                $nameofsender= $namesender[0]["name"];
                $namereceiver=M('whatsappusers')->field("name")->where(["id" => $receiver,
                ])->select();
                $nameofreceiver= $namereceiver[0]["name"];
                //var_dump( $nameofreceiver[0]["name"]);
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","reply"])
        ->where
                ([
                "sender_id"=>$receiver,])
        ->select();
        if (count($check) == 0) {
            echo "There isn't any message to list";
         
            if($id == null || $id == ""){
            
                
        
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
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
            
                
                $wpmessage = M("wpmessages");
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "preview_content" => $preview_content,
                    "type"=>2,
                ];
                   M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender send message to $nameofreceiver ", true);
            }
            else{
                
                $wpmessage = M("wpmessages");
                $messageOld=M('wpmessages')->field("id")->where(["id" => $id,
                    ])->select();
                    $oldMessage= $messageOld[0]["id"];
                    $reply= $oldMessage;
                    
                $receiver1=M('wpmessages')->field("sender_id")->where(["id" => $id,
                ])->select();
                $receiver_id11= $receiver1[0]["sender_id"];
        
            
                $dataList[] = [
                    "message" => $message,
                    "sender_id" => $_SESSION["ID"],
                    "receiver_id" => $receiver,
                    "reply"=>$reply,
                    "preview_content" => $preview_content,
                    "type"=>2,
                    
                   
                ];
                M('wpmessages')->where(["sender_id"=>$receiver
                   ])->setField(['status' => 1]);
        
        
                
        
        
                $wpmessage->addAll($dataList);
                response(true, "$nameofsender reply to $nameofreceiver ", true);
        
            }
        }

    }

    
    

    public function seeunreadmessages(){
        $data=getJsondata();

        $list=$data->list; 
        $message=$data->message;
        $sender_id=$data->sender_id;
        $receiver_id=$data->receiver_id;
        
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","preview_content"])
        ->where
                (["status"=>"mesaj gönderildi ama okunmadı","receiver_id"=>$_SESSION["ID"]])
        ->select();
    
        if (count($check) == 0) {
            response(false, "There isn't any message to list", false);
        } else {
            response($check, "Here is your messages", true);
        }

    }

    //5) SEE OLD MESSAGES THAT USER CHOOSEN


    public function readmessages(){
        $data=getJsondata();
        //$id=$data->id;
        $message=$data->message;
        //$sender_id=$data->sender_id;
        $receiver_id=$data->receiver_id;
        $date=$data->date;
        $status=$data->status;
        $sender_id=$data->sender_id;

        
        $newstatus=1;
        

        $check = M("wpmessages")
        ->where(["sender_id" => $sender_id,"receiver_id"=>$_SESSION["ID"]
        ])->setField(['status' => $newstatus]);
        
            response(true, "Message read", true);
        }

    //6) SEE OLD MESSAGES BETWEEN CHOOSEN USER 
    public function seeoldmessages(){
        $data=getJsondata();

        $list=$data->list; 
        $message=$data->message;
        $receiver_id=$data->receiver_id;
        $sender_id=$data->sender_id;
    
        $check = M("wpmessages")
        ->field(["date","message","sender_id","reply","preview_content"])
        ->where
                (["status"=>1,
                "sender"=>$sender_id,])
        ->select();
    
        if (count($check) == 0) {
            response(false, "There isn't any message to list", false);
        } else {
            response($check, "Here is your messages", true);
        }

    }

    
    


    

}




?>