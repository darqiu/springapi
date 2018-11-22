<?php
/*
XXXX信息及数据接口
@qiuyong
@2018-10-31
*/
class demoAction extends Action
{

    /*
    技录接口
    传入参数：username,md5(password)
    */
    public function login()
    {
        $info = $this->checksafe(); //API校参
        $users = getModel("demo");
        //处理数据信息
        $source   = $info["source"];
        $data     = $info["data"];
        $username = $data["username"];
        $passwd   = $data["password"];
		    $arr_dat = array(
               "userid"  =>  $userid,
               "token"   =>  "",
               "bizid"   =>  "",
               "name"    =>  "",
               "expires" =>  date("YmdHis")
        );
        //参数校验
        if($username==""){ rejson(400,$arr_dat,"登录帐号不能为空");return; }
        if($passwd==""){ rejson(400,$arr_dat,"登录密码不能为空");return; }
        $row      = $users->user_login($username);
        $userid   = (int)$row["userid"];
        //对用户信息进行认证
        if($userid){
            $salt    = $row["salt"];
            $oldpass = $row["password"];
            $nickname= $row["nickname"];
            $newpass = MD5($passwd.$salt);

            //判断帐号密码是不正确
            if($oldpass!=$newpass){ rejson(400,$arr_dat,"密码错误，请重新检查输入"); }
            //判断帐号是否有效
            if($row["checked"]<>1){ rejson(400,$arr_dat,"帐号已冻结，无法进入系统"); }
            //返回用户登录状态
            $token = $users->user_sync("userid=$userid&source=$source");
            //取得技师信息
            $worker = getModel("worker");
            $binfo = $worker->userinfo($userid);
            if(!$binfo){ appError(400,"技师信息不存在，无法进入"); }
            $bizid  = $binfo["bizid"];		//商户ID
            $name   = $binfo["name"];     //技师姓名
            $arr = array(
                "userid"  =>  $userid,
                "token"   =>  $token,
                "bizid"   =>  $bizid,
                "name"    =>  ($name)?$name:"缺少名字",
                "expires" =>  date("YmdHis")
            );
            rejson(200,$arr);
        }else{
            rejson(400,$arr_dat,"注册帐号不存在");
        }
    }



    /*
    查询信息 -- 此为演示版本，无需校参
    传入参数：userid
    */
    public function userinfo()
    {
        $worker  = getModel("worker");
        //处理数据信息
        $source   = $info["source"];  //来源头
        $data     = $info["data"];    //入参数据
        $userid   = (int)$data["userid"];
        $userinfo = $worker->userinfo($userid);
        rejson(200,$userinfo,"OK");

    }


}
