<?php
namespace Home\Controller;
use Think\Controller;
class AcptController extends Controller {
    public function mycb(){
      exit(json_encode(['code'=>1,'msg'=>'success']));
    }
    public function index(){
      exit(json_encode(['code'=>1,'msg'=>'success']));
    }
}
