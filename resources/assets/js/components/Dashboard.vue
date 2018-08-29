<template>
  <div id="dashboard">
    <div class="row container" style="margin:0px; padding-left:5px; padding-right:5px; width:100%">
      <div class="panel panel-default" style="width:20%;height:650px !important;float: left;">
        <div class="panel-body" style="padding-top:25px">
            
            <form id="job-form" @submit.prevent="save_booking">
              <input id="his_id" type="text" hidden>
              <div class="input-group" style="text-align:left">
                <label style>LOCATION : </label>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" style="margin-left:20px" @click="dis_modal">
                  <i class="	glyphicon glyphicon-map-marker" style="color:white; margin-right:5px;"></i>Show Map
                </button>
              </div>
              
              <div class="input-group">
                <div class="">
                  <input id="a_pos" name="pick_address" type="text" class="form-control" required>
                </div>
                <span class="input-group-addon addonBtn" @click="clear_a_loc"><i class="	glyphicon glyphicon-remove"></i></span>
              </div>

              <div class="input-group">
                <div class="">
                  <input id="b_pos" name="drop_address" type="text" class="form-control" required>
                </div>
                <span class="input-group-addon addonBtn" @click="clear_b_loc"><i class="	glyphicon glyphicon-remove"></i></span>
              </div>

              <div class="input-group" >
                <label style>Route : </label>
                <span id="elapsed" class="">0</span>, 
                <span id="distance" class="">0</span>
              </div>

              <div class="input-group">
                <label style>When : </label>
                <div  id="radio" class="radio">
                  <label><input type="radio" name="optradio" @click="check_now" value="now">Now</label>
                  <label><input id="later_radio" type="radio" name="optradio" @click="check_later" value="later">Later</label>
                </div>
                <input id="bespeak_date" type="datetime-local" name="bdaytime" style="margin-left:8px; width:200px" hidden>
              </div>
            

              <hr style="margin-top:10px; margin-bottom:10px; border-color:#a29e9e">

              <div class="input-group" style="text-align:left">
                <label style>PASSENGER</label>
              </div>
                <div class="input-group" style="">
                  <span class="input-group-addon markBtn"><i class="	glyphicon glyphicon-user"></i></span>
                    <input type="text" class="form-control" id="passenger" required>
                  <span class="input-group-addon addonBtn" data-toggle="modal" data-target="#passenger_Modal"><i class="	glyphicon glyphicon-search"></i></span>
                </div>
                <div class="input-group"  style="">
                  <span class="input-group-addon markBtn"><i class="	glyphicon glyphicon-earphone"></i></span>
                  <input type="text" class="form-control" id="phone" required>
                  <span class="input-group-addon addonBtn" data-toggle="modal" data-target="#passenger_Modal"><i class="	glyphicon glyphicon-search"></i></span>
                </div>
              <div class="input-group">
                <span class="input-group-addon markBtn"><i class="	glyphicon glyphicon-envelope"></i></span>
                <input type="text" class="form-control" id="email" required>
              </div>

              <div class="input-group" style="text-align:left;width:100%">
                <label for="comment">Info:</label>
                <textarea class="form-control" rows="5" id="comment"></textarea>
              </div>

              <hr style="margin-top:10px; margin-bottom:10px; border-color:#a29e9e">

              <div class="form-group" style="padding:0px">
                <div style="display:inline-block; width:40%">
                  <label style="text-align:left;padding:0px; padding-top:8px">Driver # Car :</label>
                </div>
                <div  style="display:inline-block; width:58%">
                  <select class="form-control" id="sel_driver">  
                    <option value="0"></option>
                    <option :key="index" v-for="(item, index) in driversInZone" :value="item.num">{{item.num}}</option>
                  </select>
                  <input id="auto_sel_txt" type="text" class="form-control" readonly style="display:none">
                </div>
              </div>
              
              <div class="form-group">
                <div style="display:inline-block; width:40%">
                  <button id="autoBtn" type="button" class="btn btn-success" @click="clickedAuto">Auto</button>
                </div>
                <div style="display:inline-block; width:58%; text-align:right">
                  <button id="save_btn" type="submit" class="btn btn-success"><i class="	glyphicon glyphicon-floppy-disk" style="margin-right:5px"></i>Save</button>
                  <button id="update_btn" type="button" class="btn btn-success" style="display:none" @click="clicked_update"><i class="	glyphicon glyphicon-saved" style="margin-right:5px"></i>Update</button>
                  <button id="cancel_btn" type="button" class="btn btn-danger" @click="clicked_cancel"><i class="	glyphicon glyphicon-remove" style="margin-right:5px"></i>Cancel</button>
                </div>
              </div>

            </form>

        </div>
      </div>
      
      <div class="panel panel-center" style="width:60%; float: left;">
        <div style="border:15px solid #c5dfe8">
          <GmapMap ref="mainmap" :center="center" :zoom="14" style=" height: 620px">
          </GmapMap>
        </div>
      </div>
      
      <div class="panel panel-default" style="width:20%; height:650px !important; float: left;">
          <div class="panel-body">
            
            <form>
              <div style="height:380px; overflow:auto">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="f_column">ZONE_A</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_A'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_B</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_B'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_C</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_C'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_D</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_D'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_E</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_E'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_F</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_F'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="f_column">ZONE_G</td>
                        <td class="s_column">
                            <div v-for="item in driversInZone" v-if="item.zone === 'ZONE_G'" class="sub_item " >
                                <div v-if="item.status === 'b'" class="child busy">{{item.num}}</div>
                                <div v-if="item.status === 'w'" class="child wait">{{item.num}}</div>
                                <div v-if="item.status === 'r'" class="child rest">{{item.num}}</div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                
              </div>
            </form>
            
            <hr style="margin-top:10px; margin-bottom:10px; border-color:#a29e9e">

            <form>
              <div class="form-group" style="padding:0px">
                <div style="float:left; width:30%">
                  <label style="padding:0px; padding-top:8px">search :</label>
                </div>
                <div class="input-group" style="width:68%">
                  <input type="text" class="form-control" id="sel_car_num">
                  <span class="input-group-addon addonBtn" @click="searchZone"><i class="	glyphicon glyphicon-search"></i></span>
                </div>
              </div>
              <div class="form-group" style="padding:0px; position:relative">
                <div style="float:left; width:30%">
                  <label style="padding:0px; padding-top:8px">Zone :</label>
                </div>
                <div style="float:left;width:68%">
                  <input type="text" class="form-control" id="sel_zone">
                </div>
              </div>
              
              <div class="input-group" style="text-align:left;width:100%; padding-top:10px; clear:left">
                <label for="msg" style="margin-top:5px; margin-right:10px">Message :</label>
                <label><input id="checkMsgtype" type="checkbox" style="margin-right:5px; width:16px; height:16px"><span>ALL</span></label>
                <span class="btn btn-primary" data-toggle="modal" data-target="#driver_Modal" style="float:right;  margin-right: 10px;margin-bottom:5px; padding-top:3px; padding-bottom:3px">view drivers</span>
                <span class="btn btn-success" @click="sendMessage" style="float:right;  margin-right: 10px;margin-bottom:5px; padding-top:3px; padding-bottom:3px"><i class="glyphicon glyphicon-send" style="margin-right:10px;color:white"></i>send</span>
                <div id="msg_pan" class="" style="overflow:auto;position:absolute; top:40px; right:0; background-color:#ddd; height:300px; width: 300px; z-index:1000; border-radius:5%; padding:15px">
                  <div :key = "index" v-for="(item, index) in messages">
                    <div class="btn" data-toggle="collapse" :data-target="'#'+index" style="width:100%; text-align:left; border-bottom:1px solid #949090; border-radius:2%">{{index}}</div>
                    <div :id="index" class="collapse">
                      <div :key = "subIndex" v-for="(subItem, subIndex) in item">
                        <div style="margin-left:10px"> {{subIndex}}: <span @click="removeMsg(index, subIndex)" style="float:right; font-size:12px"><i class="	glyphicon glyphicon-remove"></i></span></div>
                        <div style="margin-left:20px">{{subItem.msg}}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <textarea class="form-control" rows="4" id="msg"></textarea>
              </div>
              
            </form>
          </div>
      </div>
    </div>

    <div class="container col-md-12" style="padding-left:5px;padding-right:5px;">
      <div class="panel-default" style="max-height:280px: over-flow:auto">
        <div class="panel-heading" style="text-align:left">
          <label >History</label>
          <div style="float:right">
            <button id="today" type="button" class="btn btn-primary sort-status sort-actived" @click="changeHistory('today')">TodayJobs (<span id="count_today">0</span>)</button>
            <button id="future" type="button" class="btn btn-primary sort-status" @click="changeHistory('future')">FutureJobs (<span id="count_future">0</span>)</button>
            <button id="late" type="button" class="btn btn-primary sort-status" @click="changeHistory('late')">PastJobs (<span id="count_late">0</span>)</button>
            <button id="unarrange" type="button" class="btn btn-danger sort-status" @click="changeHistory('unarrange')">UnarrangeJobs (<span id="count_unarrange">0</span>)</button>
          </div>
        </div>
        <div class="panel-body" style="padding:0px; height:220px; overflow:auto; display:block; width:100%">
          <table class="table table-bordered table-hover" >
            <thead>
              <tr>
                <th style="width:35px; text-align:center">ID</th>
                <th style="text-align:center">Car Number</th>
                <th style="text-align:center">Passenger</th>
                <th style="text-align:center">Driver</th>
                <th style="text-align:center">Pickup address</th>
                <th style="text-align:center">Drop OFF address</th>
                <th style="text-align:center; width:200px">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr :key="index" v-for="(item, index) in histories" :id="'his_'+item.id" :info="item.info" :email="item.email" :phone="item.phone" :order="item.order_date_time" :elapsed="item.elapsed_time" :distance="item.distance" :carNum="item.car_num">
                <td>{{index+1}}</td>
                <td>{{item.car_num}}</td>
                <td>{{item.passenger}}</td>
                <td>{{item.driver_name}}</td>
                <td>{{item.pick_address}}</td>
                <td>{{item.drop_address}}</td>
                <td v-if="item.status">{{item.status}}</td>
                <td v-else>
                  <span @click="editHistory(item.id)" style="padding:2px 5px; background-color:#5cb85c;color:white; border-radius:8%; margin-right:10px; cursor:pointer"><i class="glyphicon glyphicon-edit" style="color:white; margin-right:5px"></i>Edit</span>
                  <span @click="removeHistory(item.id)" style="padding:2px 5px; background-color:#f77d7d;color:white; border-radius:8%; cursor:pointer"><i class="	glyphicon glyphicon-remove" style="color:white; margin-right:5px"></i>remove</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> 
    </div>

    <div class="modal fade" id="myModal" >
      <div class="modal-dialog modal-lg" style="width:1200px">
        <div class="modal-content" style="height:800px; width:1200px">
          <div class="modal-body" style="padding-left:0px; padding:0px">
            <div style="padding-bottom:3px; padding-top:18px; background-color:#a7cee6">
              <label>Position A : </label>
              <input id="pac-input-a" class="controls" type="text"  style="margin-right:10px; width:400px" >
              <label>Position B : </label>
              <input id="pac-input-b" class="controls" type="text"  style="margin-right:10px;  width:400px" >
              <button type="button" class="btn btn-success" data-dismiss="modal"  @click="set_location">OK</button>
            </div>
            <div style="border: 10px solid #a7cee6;">
            <GmapMap ref="direction" :center="center" :zoom="6" style=" height: 730px">
            </GmapMap>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <div class="modal fade" id="passenger_Modal" >
      <div class="modal-dialog modal-lg" style="width:800px">
        <div class="modal-content" style=" width:800px; ">
          <div class="modal-header" style="background-color:#44a544; text-align:left; color:white;">
            <h4 class="modal-title">Passenger List</h4>
          </div>

          <div class="modal-body" style="max-height:400px;overflow:auto">
            
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th style="width:50px"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="passenger-cell" :key="index" v-for="(item, index) in passengers" :id="'passenger_'+index" :name="item.name" :phone="item.phone" :email="item.email">
                    <td style="text-align:left">{{item.name}}</td>
                    <td style="text-align:left">{{item.phone}}</td>
                    <td>
                      <span @click="selectPassenger(index)" class="uncheck_box"><i class="glyphicon glyphicon-unchecked"></i></span>
                      <span @click="selectPassenger(index)" class="check_box" hidden><i class="glyphicon glyphicon-check"></i></span>
                    </td>
                  </tr>
                </tbody>
              </table>
          </div>
          
          <div class="modal-footer" style="background-color:#44a544;">
            <button type="button" class="btn btn-primary" data-dismiss="modal"  @click="set_passenger">OK</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="driver_Modal" >
      <div class="modal-dialog modal-lg" style="width:800px">
        <div class="modal-content" style=" width:800px; ">
          <div class="modal-header" style="background-color:#44a544; text-align:left; color:white;">
            <h4 class="modal-title">Driver List</h4>
          </div>

          <div class="modal-body" style="max-height:400px;overflow:auto">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Car Number</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="passenger-cell" :key="index" v-for="(item, index) in driverInfos" >
                    <td style="text-align:left">{{item.name}}</td>
                    <td style="text-align:left">{{item.email}}</td>
                    <td style="text-align:left">{{item.phone}}</td>
                    <td style="text-align:left">{{item.car_number}}</td>
                  </tr>
                </tbody>
              </table>
          </div>
          
          <div class="modal-footer" style="background-color:#44a544;">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'///////add new
import axios from 'axios'
// import Firebase from 'firebase'

Vue.use(VueGoogleMaps, {
  load: {
    libraries: 'places, directions', // This is required if you use the Autocomplete plugin
    key: 'AIzaSyBdTrGJd1qFEMlgjqNatB3NmTVIwf7DSBM',
  },
})


const config = {
  apiKey: "AIzaSyDSWh8uzfCZ2PqU7p_ZhINW58zixySLlWQ",
  authDomain: "asur-142af.firebaseapp.com",
  databaseURL: "https://asur-142af.firebaseio.com",
  projectId: "asur-142af",
  storageBucket: "asur-142af.appspot.com",
  messagingSenderId: "88413771227"
 }
firebase.initializeApp(config)
const database = firebase.database()
const messaging = firebase.messaging()
let messagingToken = ''
messaging.requestPermission()
.then(function(){
  return messaging.getToken();
})
.then(function(token){
  messagingToken = token
  // console.log(token);
})
.catch(function(err){
  console.log('Error Occured.')
})

export default {
 
  data:function(){
    return{
      autoselCars:[],
      drivers:{},
      passengers:{},
      driverInfos:{},
      tokens:{},
      markers:[],
      map_inited: false,
      default_origin:'Ricardo Palma 636, José Leonardo Ortiz, Peru',
      default_dest: 'Colon 123, Chiclayo, Peru',
      tmp_distance:'0',
      tmp_duration:'0',
      center:{lat:-6.77, lng:-79.84},
      zones:[],
      driversInZone:[],
      histories:[],
      messages:{},
      cus_pos:{},
    };
  },
  watch:{
    // a_loc:function(val){
    //   this.setAutoCars()
    // }
  },
  mounted() {
    let vm = this
    $('#a_pos').on('change', function(){
      vm.setAutoCars()
    });
    this.$refs.mainmap.$mapPromise.then((map) => {
      this.vueGoogleMapsInit()
      database.ref('Administrator').set({
        token: messagingToken
      });
    });
    database.ref('RidersInformation').on('value', function(data){
      vm.passengers = data.val()
    });
    database.ref('DriversInformation').on('value', function(data){
      vm.driverInfos = data.val()
    });

    database.ref('Drivers').on('value', function(drivers){
      vm.drivers = drivers.val()
      vm.update_marker();
      vm.updateInZone();
      vm.setAutoCars()
    });

    database.ref('Tokens').on('value', function(data){
      vm.tokens = data.val()
    });
    messaging.onMessage(function(payload){
      // console.log(payload.data)
      let type = payload.data.title;
      if(type == 'Accept'){
        let order = payload.data.orderID;//payload.data.order
        let driver = payload.data.driverID
        vm.arrangeDriver(order, driver)
      }else if(type == 'Finished'){
        let orderID = payload.data.orderID;
        vm.finishOrder(orderID);
      }
      
    })
  },

  methods:{
    vueGoogleMapsInit: function(){
      let vm = this
      // this.map_inited = true;
      
      ///////// Main map initialize////////
      let data = require('../../../../public/region_info.json')
      let main_map = this.$refs.mainmap.$mapObject
      for(let x in data){
        let regin = new google.maps.Polygon({ paths:data[x].bounds,  strokeColor:'#FF0000',  strokeOpacity:0.2,  strokeWeight:2,  fillColor:'#FF0000',  fillOpacity: 0.1});
        regin.setMap(main_map);
        vm.zones.push([data[x].name, regin])

        let label ={
            text:data[x].name,
            fontSize:'20px',
            fontWeight:'bold',
            color:'#bf3d39'
        }
        let icon = require('../../../../public/imgs/empty.png')
        let zone_center = data[x].center
        new google.maps.Marker({ position: {lat:zone_center['lat'], lng:zone_center['lng']},  map: main_map, label: label,icon:icon});
      }

      this.geocoder = new google.maps.Geocoder();

      vm.update_marker();

      //////////Modal map initialize ////////////
      let modal_map = this.$refs.direction.$mapObject;
      
      this.directionsService = new google.maps.DirectionsService()
      this.directionsDisplay = new google.maps.DirectionsRenderer({draggable:true, map:modal_map})
      // this.drawRoute()
      
      let search_A = new google.maps.places.Autocomplete(document.getElementById('pac-input-a'));
      let search_B = new google.maps.places.Autocomplete(document.getElementById('pac-input-b'));

      search_A.addListener('place_changed', function() {  vm.drawRoute();   });
      search_B.addListener('place_changed', function() {   vm.drawRoute();  });

      this.directionsDisplay.addListener('directions_changed', function(){
        vm.tmp_distance = this.directions.routes[0].legs[0].distance.text
        vm.tmp_duration = this.directions.routes[0].legs[0].duration.text
        
        $('#pac-input-a').val(this.directions.routes[0].legs[0].start_address);
        $('#pac-input-b').val(this.directions.routes[0].legs[0].end_address);
      });

      let pick = new google.maps.places.Autocomplete(document.getElementById('a_pos'));
      let drop = new google.maps.places.Autocomplete(document.getElementById('b_pos'));
      pick.addListener('place_changed', function() {vm.calcDistance() });
      drop.addListener('place_changed', function() {vm.calcDistance()});

      vm.updateHistory('future', true)
      vm.updateHistory('late', true)
      vm.updateHistory('unarrange', true)
      vm.updateHistory('today')
    },
    calcDistance:function(){
      
      $('#pac-input-a').val($('#a_pos').val());
      $('#pac-input-b').val($('#b_pos').val());

      let result = this.drawRoute('calc')
      if(!result){
        $('#elapsed').text('0')
        $('#distance').text('0')
      }
    },
    dis_modal:function(){
      $('#pac-input-a').val($('#a_pos').val());
      $('#pac-input-b').val($('#b_pos').val());

      this.drawRoute();
      // modal_map.setCenter(vm.center)
      // modal_map.setZoom(10)
    },
    drawRoute: function (flag = '') {
      let vm = this
      
      // let origin = this.default_origin
      // let destination = this.default_dest
      
      let inputA = $('#pac-input-a').val();
      let inputB = $('#pac-input-b').val();

      if(inputA == '' || inputB == '') {
        vm.directionsDisplay.setMap(null)
        return false;
      }
      let modal_map = this.$refs.direction.$mapObject;
      vm.directionsDisplay.setMap(modal_map)
      // if(inputA != ''){ origin = inputA }
      // if(inputB != ''){ destination = inputB}      
      
      vm.directionsService.route({
        origin: inputA,////////////
        destination: inputB,///////////////
        travelMode: 'DRIVING'
      }, function (response, status) {
        if (status === 'OK') {
          vm.directionsDisplay.setDirections(response)
          if(flag == 'calc'){
            $('#elapsed').text(response.routes[0].legs[0].duration.text)
            $('#distance').text(response.routes[0].legs[0].distance.text)
          }
          return true;
        } else {
          alert("the route can't be drawn")
          return false;
        }
      })
    },
    set_location: function(){
      $('#a_pos').val($('#pac-input-a').val());
      $('#b_pos').val($('#pac-input-b').val());

      if($('#a_pos').val() == '' || $('#b_pos').val() == ''){
        $('#elapsed').text('0')
        $('#distance').text('0')
      }else{
        $('#elapsed').text(this.tmp_duration)
        $('#distance').text(this.tmp_distance)
      }
      this.setAutoCars()
      $('#myModal').fadeToggle()
    },
    save_booking:function(){
      let vm = this
      let formData = vm.getJobData();    
      
      if(formData == false){
        alert('Please set the date and time of this order.')
        return;
      }
      axios.post("/post_history", formData)
        .then(function(response){
          database.ref('Orders/'+response.data[0] ).set({
            passenger: formData.passenger,
            from: formData.pick_address,
            to : formData.drop_address
          });
          vm.sendOrder(response.data[0]);
          if(response.data[1] == $('.sort-actived').attr('id')){
            vm.updateHistory(response.data[1])
          }else{
            vm.updateHistory(response.data[1], true)
          }
          
          if($('.sort-actived').attr('id') == 'unarrange'){
            vm.updateHistory('unarrange')
          }else{
            vm.updateHistory('unarrange', true)
          }

          vm.clicked_cancel()
      });
    },
    getJobData(){
      let order= $('input[name="optradio"]:checked').val();
      if(!order) return false;
      if(order == "later"){order = $("#bespeak_date").val();}
      let data = {
          id:$('#his_id').val(),
          pick_address:$('#a_pos').val(),
          drop_address:$('#b_pos').val(),
          elapsed_time:$('#elapsed').text(),
          distance:$('#distance').text(),
          order_date: order,
          passenger:$('#passenger').val(),
          phone:$('#phone').val(),
          email:$('#email').val(),
          comment:$('#comment').val(),
          driver:$('#sel_driver').val()
      }
      return data
    },
    clear_a_loc:function(){ 
      $('#a_pos').val('')
      $('#elapsed').text('0')
      $('#distance').text('0')
    },
    clear_b_loc:function(){ 
      $('#b_pos').val('')
      $('#elapsed').text('0')
      $('#distance').text('0')
    },
    check_now:function(){ $('#bespeak_date').hide();},
    check_later:function(){ $('#bespeak_date').show();},
    update_marker(){
      this.clear_marker();
      let vm = this
                    
      let main_map = this.$refs.mainmap.$mapObject
      for(let x in vm.drivers){
        let driver = vm.drivers[x]
        let car_num = vm.getCarNumByID(x)
        let label ={
            text:String(car_num),
            fontSize:'12px',
            fontWeight:'bold',
            color:'black'
        }
       
        let marker = new google.maps.Marker({ position: {lat:driver.l[0], lng:driver.l[1]},  map: main_map, label: label,icon: vm.getIcon(driver.status)});
        vm.markers.push(marker)
      }
    },
    getCarNumByID(driverID){
      for(let x in this.driverInfos){
        if(x == driverID){
          return this.driverInfos[x].car_number
        }
      }
      return false;
    },
    clear_marker(){
      for (var i = 0; i < this.markers.length; i++) {
          this.markers[i].setMap(null);
      }
    },
    getIcon(status){
        let icon = require('../../../../public/imgs/y_marker.png')
        if(status == "b"){
            icon = require('../../../../public/imgs/r_marker.png')
        }else if(status == "r"){
            icon = require('../../../../public/imgs/g_marker.png')
        }
        return icon;
    },
    updateInZone(){
      let vm = this
      let zoneList=[]
      for(let x in vm.drivers){
        let driver = vm.drivers[x]
        let car_num = vm.getCarNumByID(x)
        let zone_name = vm.getZoneName(driver.l);
        let d_z = {num: car_num, status: driver.status, zone: zone_name}
        zoneList.push(d_z)  
      }
      this.driversInZone = zoneList
    },
    getZoneName(position){
      let vm = this
      let m_latlng = new google.maps.LatLng({lat:position[0], lng:position[1]})
      for(let x in vm.zones){
        let isContain = google.maps.geometry.poly.containsLocation(m_latlng, vm.zones[x][1])
        if(isContain){
          return vm.zones[x][0]
        } 
      }
    },
    changeHistory(flag){
      // console.log(this.drivers.keys())
      let isCurrent = $('#'+flag).hasClass('sort-actived')
      if(!isCurrent){
        $('.sort-actived').removeClass('sort-actived')
        $('#'+flag).addClass('sort-actived')
        this.updateHistory(flag);
      }
    },
    updateHistory(sel, onlyCount = false){
      let vm = this
      axios.get("/get_history/"+sel)
        .then(function(response){
          $('#count_'+sel).text(response.data.length)
          if(!onlyCount){
             vm.histories = response.data;
          }
      });
    },
    editHistory(his_id){
      let sel_his = this.findHistory(his_id)
      $('#a_pos').val(sel_his.pick_address)
      $('#b_pos').val(sel_his.drop_address)
      // this.a_loc = sel_his.pick_address;
      // this.b_loc = sel_his.drop_address;
      // $('#a_pos').val(sel_his.pick_address)
      // $('#b_pos').val(sel_his.drop_address)
      $('#elapsed').val(sel_his.elapsed_time)
      $('#distance').val(sel_his.distance)
      $('#later_radio').attr('checked', 'checked')
      $('#bespeak_date').val(sel_his.order_date_time)
      $('#bespeak_date').css('display', 'inline-block')
      $('#passenger').val(sel_his.passenger)
      $('#phone').val(sel_his.phone)
      $('#email').val(sel_his.email)
      $('#comment').val(sel_his.info)
      $('#sel_driver').val(sel_his.driver_name)

      $('#save_btn').css('display','none')
      $('#update_btn').css('display','inline-block')

      $('#autoBtn').text('Auto')
      $('#auto_sel_txt').css('display', 'none');
      $('#sel_driver').css('display', 'block');
    },
    removeHistory(his_id){
      let vm = this
      axios.post("/remove_history", {id:his_id})
        .then(function(response){
          

          if($('.sort-actived').attr('id') == 'unarrange'){
            vm.updateHistory('unarrange')
            vm.updateHistory('today', true)
            vm.updateHistory('future', true)
            vm.updateHistory('late', true)
          }else{
            vm.updateHistory('unarrange', true)
            vm.updateHistory($('.sort-actived').attr('id'))
          }
      });
    },
    findHistory(his_id){
      let hisList = this.histories;
      for(let hisNum in hisList){
        if(hisList[hisNum].id == his_id){
          return hisList[hisNum];
        }
      }
      return ''
    },
    clicked_update(){
      let vm = this
      let formData = vm.getJobData()      
      axios.post("/update_history", formData)
        .then(function(response){
          if(response.data == $('.sort-actived').attr('id')){
            vm.updateHistory(response.data)
          }else{
            vm.updateHistory(response.data, true)
          }

          if($('.sort-actived').attr('id') == 'unarrange'){
            vm.updateHistory('unarrange')
          }else{
            vm.updateHistory('unarrange', true)
          }

          vm.clicked_cancel()
      });
    },
    clicked_cancel(){
      // this.a_loc = ''
      // this.b_loc = ''
      $('#a_pos').val('')
      $('#b_pos').val('')
      $('#elapsed').text('0')
      $('#distance').text('0')
      $('#later_radio').removeAttr('checked', 'checked')
      $('#bespeak_date').val('')
      $('#bespeak_date').css('display', 'none')
      $('#passenger').val('')
      $('#phone').val('')
      $('#email').val('')
      $('#comment').val('')
      $('#sel_driver').val('0')

      $('#save_btn').css('display','inline-block')
      $('#update_btn').css('display','none')
    },
    selectPassenger(index){
      let cell = $('#passenger_'+index)
      if(cell.hasClass('selected')){
        cell.removeClass('selected')
      }else{
        $('.passenger-cell.selected').removeClass('selected')
        cell.addClass('selected')
      }

    },
    set_passenger(){
      let selected = $('.passenger-cell.selected');
      if(selected.length){
        $('#passenger').val(selected.attr('name'))
        $('#phone').val(selected.attr('phone'))
        $('#email').val(selected.attr('email'))

        selected.removeClass('selected')
      }
    },
    searchZone(){
      let vm = this;
      let car_num = $('#sel_car_num').val()
      if(car_num != ''){
        for(let x in vm.driversInZone){
          if(vm.driversInZone[x].num == car_num){
            $('#sel_zone').val(vm.driversInZone[x].zone)
            break;
          }
        }
      }
    },
    sendOrder(orderID){
      // console.log(this.tokens)
      var s_type = $('#autoBtn').text();
      let sendTokens = []
      if(s_type == 'Manual'){
        for(let x in this.autoselCars){
          let sel_token = this.tokenByCarNum(this.autoselCars[x])
          sendTokens.push(sel_token)
        }
      }else{
        let sel_token = this.tokenByCarNum($('#sel_driver').val());
        sendTokens.push(sel_token);
      }

      // sendTokens.push(messagingToken)
      // console.log(sendTokens)
      this.runFCM('order', sendTokens, 'noMessage',orderID);
    },
    sendMessage(){
      let vm = this;
      let msg = $('#msg').val();
      let sendTokens = [];
      
      if($('#checkMsgtype').prop("checked")){
        for(let x in vm.drivers){
          for(let y in vm.tokens){
            if(x == y){
              sendTokens.push(vm.tokens[y].token)
            }
          }
        }
      }else{
        let car_num = $('#sel_car_num').val();
        let d_token = this.tokenByCarNum(car_num);
        if(d_token == false){
          alert('There is not the selected driver')
          return;
        }else{
          sendTokens.push(d_token)
        }
      }
      if(sendTokens.length == 0 || msg == '') {alert("This message can't be sent. Try it.");return}
      this.runFCM('message',sendTokens, msg)

      $('#msg').val('');
      $('#sel_car_num').val('');
      $('#sel_zone').val('');
      $('#checkMsgtype').prop('checked', false);
      
    },
    runFCM(type, tokens, sendData, orderId=''){
      let vm = this;
      axios.post("/sendMessage", {type:type, admin:messagingToken, cusPos:vm.cus_pos, token: tokens, data:sendData, orderID:orderId})
        .then(function(response){
            console.log(response.data)     
      });
    },
    tokenByCarNum(car_num){
      let vm = this
      var driverID = ''
      for(let x in vm.driverInfos){
        if(vm.driverInfos[x].car_number == car_num){
          driverID = x
          break;
        }
      }

      if(driverID == '') return false;

      for(let y in vm.tokens){
        if(y == driverID){
          return vm.tokens[y].token;
        }
      }

      return false;
    },
    clickedAuto(){
      let vm = this;
      if($('#autoBtn').text() == 'Auto'){
        $('#autoBtn').text('Manual')
        $('#auto_sel_txt').css('display', 'block');
        $('#sel_driver').css('display', 'none');
      }else{
        $('#autoBtn').text('Auto')
        $('#auto_sel_txt').css('display', 'none');
        $('#sel_driver').css('display', 'block');
      }
    },
    arrangeDriver(order, driver){
      let vm = this
      let driverName, carNum
      for(let x in vm.driverInfos){
        if(x == driver){
          driverName = vm.driverInfos[x].name
          carNum = vm.driverInfos[x].car_number
          break;
        }
      }
      axios.post("/set_driver",{orderID:order, name:driverName,num:carNum})
        .then(function(response){
          let tabType = $('.sort-actived').attr('id');
          if($('.sort-actived').attr('id') == 'unarrange'){
            vm.updateHistory('unarrange')
          }else{
            vm.updateHistory(tabType)
            vm.updateHistory('unarrange', true)
          }
      });
    },
    setAutoCars(){
      let vm = this
      let start_pos = $('#a_pos').val()
      if(start_pos == ''){
        $('#auto_sel_txt').val('');
        vm.autoselCars = []
      }else{
        this.geocoder.geocode({'address': start_pos}, function(results, status) {
          if(status === 'OK'){
            let pos = results[0].geometry.location
            vm.cus_pos = {'0':pos.lat(), '1':pos.lng()};
            let customer_zone = vm.getZoneName(vm.cus_pos);
            let strCarNum = '';
            for(let x in vm.driversInZone){
              if(vm.driversInZone[x].zone == customer_zone && vm.driversInZone[x].status == 'r'){
                strCarNum += vm.driversInZone[x].num + ', ';
                vm.autoselCars.push(vm.driversInZone[x].num);
              }
            }
            if(strCarNum != ''){
              strCarNum = strCarNum.substr(0,strCarNum.length-2)
            }
            $('#auto_sel_txt').val(strCarNum);
          }else{
            
          }
        });
      }
    },
    finishOrder(orderId){
      let vm = this
      axios.post("/finish_order",{orderID:orderId})
        .then(function(response){
          let tabType = $('.sort-actived').attr('id');
          vm.updateHistory(tabType)
      });

    }
    // viewDrivers(){
    //   if($('#msg_pan').hasClass('active')){
    //     $('#msg_pan').removeClass('active')
    //   }else{
    //     $('#msg_pan').addClass('active')
    //   }
    // }
  }
}
</script>



<style>
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
  .nav-style{
    background-color:#79baef;
    font-weight:bold;

  }
  .panel{
    margin-bottom:5px;
  }
  .panel-body{
      padding:5px;
  }
  .input-group{
    margin-bottom:8px;
  }

  .radio{
    display:inline-block;
    margin-left:10px;
  }

  .devider{
    border-top: 2px solid gray;
    margin-bottom:10px;
  }
  
  .collap-panel{
    margin-top:2px !important;
  }
  .panel-heading{
    padding-top:8px;
    padding-bottom:8px;
  }
  .collap-btn{
    display:block;
    width:200px;
    margin-bottom:2px;
  }
  .collap-btn:after {
      font-family: "Glyphicons Halflings";
      content: "\e114";
      float: right;
      margin-left: 15px;
  }
    /* Icon when the collapsible content is hidden */
  .collap-btn.collapsed:after {
      content: "\e080";
  }
  .panel{
    padding:0px;
    background-color:#c2e3ef59;
  }

  .btn{
      padding-top: 2px;
      padding-bottom: 2px;
  }
  .pac-container{
    z-index:5000;
  }
  .f_column{
      width:15%;
      padding-top: 2px!important;
      padding-bottom: 2px!important;
  }
  .s_column{
      text-align:left;
      padding-top: 2px!important;
      padding-bottom: 2px!important;
  }
  .sub_item{
      width:40px!important;
      padding:0px;
      margin-left:2px;
      margin-bottom:2px;
      font-size:16px;
      font-weight:bold;
      text-align:center;
      display:inline-block;
  }
  .sub_item .child{
      padding: 2px 12px!important;
  }
  .sub_item .busy{
      background-color:#f77d7d;
  }
  .sub_item .wait{
      background-color:yellow;
  }
  .sub_item .rest{
      background-color:#4aef4a;
  }
  .sort-actived,
  .sort-actived :hover
  {
    background-color:white!important;
    color: black!important;
  }

</style>
