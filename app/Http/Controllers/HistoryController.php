<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class HistoryController extends Controller
{
    //
    public function posthistory(Request $request){
        $sort_val = 'future';

        $pick_address = $request['pick_address'];
        $drop_address = $request['drop_address'];
        $elapsed_time = $request['elapsed_time'];
        $distance = $request['distance'];
        $order_date_time = $request['order_date'];
        $passenger = $request['passenger'];
        $phone = $request['phone'];
        $email = $request['email'];
        $comment = $request['comment'];
        $driver = $request['driver'];

        if($order_date_time == 'now'){
            $order_date_time = $this->getCurrentDate();
            $sort_val = 'today';
        }
        $order_date = substr($order_date_time, 0, 10);
        $id = DB::table('histories')->insertGetId(
            ['passenger' => $pick_address, 'phone' => $phone, 'email' => $email, 'info' => $comment,'pick_address' => $pick_address,'drop_address' => $drop_address, 'distance' => $distance, 'elapsed_time' => $elapsed_time, 'order_date' => $order_date, 'order_date_time' => $order_date_time]
        );

        return [$id, $sort_val];
    }

    public function gethistory($sort_val){
        $order_date = substr($this->getCurrentDate(), 0, 10);
        
        if($sort_val == 'today'){
            $result = DB::table('histories')->where('order_date', $order_date )->get();
        }elseif($sort_val == 'future'){
            $result = DB::table('histories')->where('order_date', '>', $order_date)->get();
        }elseif($sort_val == 'late'){
            $result = DB::table('histories')->where('order_date', '<', $order_date)->get();
        }else{
            $result = DB::table('histories')->where('car_num', NULL)->get();
        }
        
        return $result;
    }

    public function updatethistory(Request $request){
        $sort_val = 'future';
        $id = $request['id'];
        $pick_address = $request['pick_address'];
        $drop_address = $request['drop_address'];
        $elapsed_time = $request['elapsed_time'];
        $distance = $request['distance'];
        $order_date_time = $request['order_date'];
        $passenger = $request['passenger'];
        $phone = $request['phone'];
        $email = $request['email'];
        $comment = $request['comment'];
        $driver = $request['driver'];

        if($order_date_time == 'now'){
            $order_date_time = $this->getCurrentDate();
            $sort_val = 'today';
        }
        if($driver == '0') $driver = NULL;
        $order_date = substr($order_date_time, 0, 10);
        DB::table('histories')->where('id', $id)->update(
            ['passenger' => $pick_address, 'phone' => $phone, 'email' => $email, 'info' => $comment,'pick_address' => $pick_address,'drop_address' => $drop_address, 'distance' => $distance, 'elapsed_time' => $elapsed_time, 'order_date' => $order_date, 'order_date_time' => $order_date_time,'driver_name' => NULL, 'car_num'=>NULL]
        );

        return $sort_val;
    }

    public function removethistory(Request $request){
        $id = $request['id'];
        DB::table('histories')->where('id', $id)->delete();

        return "removed";
    }

    public function getCurrentDate(){
        $current_date = date("Y-m-d h:i:a");
        $a_p = substr($current_date, 17, 2);
        
        $date = substr($current_date, 0, 10);
        $time = substr($current_date, 11, 2);
        $extra = substr($current_date, 13, 3);

        if($a_p == "pm"){
            $time = $time + 12;
        }
        $current_date = $date."T".$time.$extra;

        return $current_date;
    }

    public function sendMessage(Request $request){
        $type = $request['type'];
        $admin = $request['admin'];
        $token = $request['token'];
        $msg = $request['data'];
        $cusPos = $request['cusPos'];
        $orderID = $request['orderID'];

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $dataBuilder = new PayloadDataBuilder();
        if($type == 'order'){
            $dataBuilder->addData(['type'=>$type,'customer' => $admin, 'lat'=>$cusPos['0'], 'lng'=>$cusPos['1'], 'str'=>'', 'orderID'=>$orderID]);
        }else{
            $dataBuilder->addData(['type'=>$type, 'message'=>$msg]);
        }
        

        $option = $optionBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, null, $data);

        $success = $downstreamResponse->numberSuccess();
        $failure = $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return $type.'///'.$admin.'///'.$token[0].'///'.$cusPos['lat'].'///'.$orderID;
        return $success;
    }

    public function setDriver(Request $request){
        $id = $request['orderID'];
        $driverName = $request['name'];
        $carNum = $request['num'];

        DB::table('histories')->where('id', $id)->update( ['driver_name' => $driverName, 'car_num'=>$carNum, 'status' => 'waiting'] );

        return $id;
    }

    public function finishOrder(Request $request){
        $id = $request['orderID'];
        DB::table('histories')->where('id', $id)->update( ['status' => 'finished'] );

        return $id;
    }
}
