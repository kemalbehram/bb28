(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-67e388d6"],{"21ef":function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"header",class:t.fixed?"fixed":""},[n("van-icon",{attrs:{color:"white",name:"arrow-left",size:"22px"},on:{click:function(e){return t.toBack()}}}),t.title?n("h2",[t._v(t._s(t.title))]):t._e(),n("span",{staticClass:"slot"},[t._t("default")],2)],1)},i=[],o={props:{icon:{default:"play-circle",type:String},title:{default:"",type:String},close:{default:!1,type:Boolean},fixed:{default:!1,type:Boolean}},methods:{toBack:function(){this.close?this.$store.state.config.show_pop=!1:this.$router.back(-1)}}},s=o,r=(n("fb42"),n("2877")),l=Object(r["a"])(s,a,i,!1,null,"2f7e4949",null);e["a"]=l.exports},"2e4d":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"relative"},[n("userHeader",{attrs:{title:"分类统计"}}),n("div",[n("van-grid",{attrs:{border:!1,"column-num":3}},[n("van-grid-item",[n("van-field",{attrs:{value:t.game_name,clickable:"",name:"picker",placeholder:"选择游戏",readonly:""},on:{click:function(e){t.showPicker=!0}}})],1),n("van-grid-item",[n("van-button",{staticStyle:{border:"none","font-size":"12px"},attrs:{plain:""},on:{click:function(e){return t.showTimePick("start")}}},[n("b",[t._v(" "+t._s(t.start)+" "),n("van-icon",{attrs:{name:"arrow-down"}})],1)])],1),n("van-grid-item",[n("van-button",{staticStyle:{border:"none","font-size":"12px"},attrs:{plain:""},on:{click:function(e){return t.showTimePick("end")}}},[n("b",[t._v(" "+t._s(t.end)+" "),n("van-icon",{attrs:{name:"arrow-down"}})],1)])],1)],1),n("div",{staticClass:"container"},[n("h1",{staticClass:"title"},[t._v(t._s(t.game_name))]),n("van-cell-group",t._l(t.group,(function(t,e){return n("van-cell",{attrs:{border:!1,title:t.title,value:t.value}})})),1)],1)],1),n("van-popup",{style:{height:"80%"},attrs:{position:"bottom"},model:{value:t.showTime,callback:function(e){t.showTime=e},expression:"showTime"}},[n("van-datetime-picker",{attrs:{"min-date":t.minDate,title:"选择年月日",type:"date"},on:{cancel:function(e){t.showTime=!1},confirm:t.getCurrentTime}})],1),n("van-popup",{style:{height:"70%"},attrs:{position:"bottom"},model:{value:t.showPicker,callback:function(e){t.showPicker=e},expression:"showPicker"}},[n("van-picker",{attrs:{columns:t.columns,"show-toolbar":""},on:{cancel:function(e){t.showPicker=!1},confirm:t.onConfirm}})],1)],1)},i=[],o=n("21ef"),s={components:{userHeader:o["a"]},data:function(){return{minDate:new Date(2019,1,1),showTime:!1,showPicker:!1,game_name:"28游戏",start:"",end:"",value:"game28",choose_time:"",columns:[{text:"28游戏",value:"game28"}],group:[{title:"游戏人数",value:0},{title:"投注金额",value:0},{title:"中奖金额",value:0},{title:"投注提成",value:0},{title:"下线返水",value:0},{title:"盈亏情况",value:0}]}},created:function(){this.getToday(),this.getTotal()},methods:{showTimePick:function(t){this.choose_time=t,this.showTime=!0},getToday:function(){var t=new Date,e=t.getMonth()+1,n=t.getFullYear()+"-"+e+"-"+t.getDate();this.start=n,this.end=n},getCurrentTime:function(t){var e=t.getMonth()+1;"start"==this.choose_time?this.start=t.getFullYear()+"-"+e+"-"+t.getDate():this.end=t.getFullYear()+"-"+e+"-"+t.getDate(),this.showTime=!1,this.getTotal()},onConfirm:function(t){this.value=t.value,this.game_name=t.text,this.showPicker=!1,this.getTotal()},getTotal:function(){var t=this,e={start:this.start,end:this.end,game:this.value};this.$get("/offline/class-statis",e).then((function(e){if(200!==e.code)return t.$notify(e.message);for(var n=0;n<e.data.length;n++)t.group[n].value=e.data[n]}))}}},r=s,l=(n("5e97"),n("2877")),c=Object(l["a"])(r,a,i,!1,null,"d4c0de16",null);e["default"]=c.exports},"5e97":function(t,e,n){"use strict";var a=n("eb60"),i=n.n(a);i.a},cffd:function(t,e,n){},eb60:function(t,e,n){},fb42:function(t,e,n){"use strict";var a=n("cffd"),i=n.n(a);i.a}}]);