<?php
namespace Home\Model;
use Think\Model;

Class LogsModel extends Model{
    // 数据表名（不包含表前缀）
    protected $tableName = "company_logs";

    public function action_log($toid,$info='',$status=true){

        $data['url'] = substr(__ACTION__, strpos(__ACTION__, 'index.php')+strlen('index.php')+1);

        $data['url']=strtolower($data['url']);

        $data['operator'] =session('internal_name');

        $data['operate_time'] = date("Y-m-d H:i:s");

        $data['ip']=get_client_ip();

        $node = M('company_authrule')->where(array('name'=>$data['url']))->find();//查找节点名称

        if($status){

            $data['status']=0;

        }else{

            $data['status']=1;

        }

        if(!empty($node) || !empty($info)){

            if($info){

                $data['description']=$info;

            }else{

                $data['description'] = $node['title'];

            }

            $data['toid'] = $toid;

            M('company_logs')->add($data);//记录日志

        }

    }
}