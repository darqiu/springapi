<?php
/*
服务商及技师数据接口
*/
class demoModules extends Modules
{

    //验证用户登录帐号及密码
    public function user_login($username="")
    {
        if($username==""){ return; }
        $query = " SELECT userid,password,salt,checked
        FROM ".DB_MEMBERS.".users
        WHERE mobile = ? ";
        $args  = array($username);
        $row   = $this->db->getRow($query,$args);
        return $row;
    }

    /*
    @信息
    @传入 userid
    */
    public function userinfo($userid=0)
    {
        if(!$userid){ appError(400,"帐号参数信息异常"); }
        //获取技师信息表
        $query = " SELECT bu.*
        ,pa.name AS provname,ca.name AS cityname,aa.name AS areaname
        FROM ".DB_JURAN.".biz_users AS bu
        INNER JOIN ".DB_JURAN.".config_areas AS pa ON pa.id = bu.provid
        INNER JOIN ".DB_JURAN.".config_areas AS ca ON ca.id = bu.cityid
        INNER JOIN ".DB_JURAN.".config_areas AS aa ON aa.id = bu.areaid
        WHERE userid = $userid ";
        $row = $this->db->getRow($query);
        if(!$row){ appError(400,"技师档案不存在"); }
        //设置默认头像
        $default_face = "https://jrwww.shui.cn/bakfile/openapi/images/fuwu/default_head.png";
        $data = array(
            "userid"  => $userid,
            "name"    => $row["name"],
            "bizid"   => $row["bizid"],
            "sex"     => $row["sex"],
            "birthday"=> $row["birthday"],
            "face_url"=> ($row["face_url"])?$row["face_url"]:$default_face,
            "mobile"  => $row["mobile"],
            "provid"  => (int)$row["provid"],
            "provname"=> $row["provname"],
            "cityid"  => (int)$row["cityid"],
            "cityname"=> $row["cityname"],
            "areaid"  => (int)$row["areaid"],
            "areaname"=> $row["areaname"],
            "tags"    => $row["tags"]
        );
        return $data;
    }



}
?>
