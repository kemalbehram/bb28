(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-52ae8e99"],{"21ef":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"header",class:t.fixed?"fixed":""},[a("van-icon",{attrs:{color:"white",name:"arrow-left",size:"22px"},on:{click:function(e){return t.toBack()}}}),t.title?a("h2",[t._v(t._s(t.title))]):t._e(),a("span",{staticClass:"slot"},[t._t("default")],2)],1)},o=[],r={props:{icon:{default:"play-circle",type:String},title:{default:"",type:String},close:{default:!1,type:Boolean},fixed:{default:!1,type:Boolean}},methods:{toBack:function(){this.close?this.$store.state.config.show_pop=!1:this.$router.back(-1)}}},n=r,i=(a("fb42"),a("2877")),c=Object(i["a"])(n,s,o,!1,null,"2f7e4949",null);e["a"]=c.exports},"693d":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("userHeader",{attrs:{title:"今日盈利"}}),a("div",{staticClass:"my-info"},[a("span",{staticClass:"title"},[t._v("盈利金额(元)")]),a("span",{staticClass:"balance font-roman"},[t._v(t._s(t.calProfit))]),t._m(0)]),a("van-button",{staticStyle:{border:"none"},attrs:{block:"",plain:""},on:{click:t.showTimePick}},[a("van-icon",{staticClass:"left",attrs:{color:"#c2c2c2",name:"arrow-left",size:"14"}}),a("b",[t._v(t._s(t.nowDate))]),a("van-icon",{staticClass:"right",attrs:{color:"#c2c2c2",name:"arrow"}})],1),a("van-grid",{staticClass:"mt-6",attrs:{border:!1,"column-num":3}},[a("van-grid-item",{staticClass:"has-border"},[a("p",{staticClass:"font-18 color-red"},[t._v(t._s(t.bet_total))]),a("p",[t._v("投注金额")])]),a("van-grid-item",{staticClass:"has-border middle"},[a("p",{staticClass:"font-18 color-red"},[t._v(t._s(t.bet_bonus))]),a("p",[t._v("中奖金额")])]),a("van-grid-item",{staticClass:"has-border"},[a("p",{staticClass:"font-18 color-red"},[t._v(t._s(t.user_award))]),a("p",[t._v("活动金额")])]),a("van-grid-item",[a("p",{staticClass:"font-18 color-red"},[t._v(t._s(t.rebate))]),a("p",[t._v("返水金额")])]),a("van-grid-item",{staticClass:"middle"},[a("p",{staticClass:"font-18 color-red"},[t._v(t._s(t.recharge))]),a("p",[t._v("充值金额")])]),a("van-grid-item",[a("p",{staticClass:"font-20 color-red"},[t._v(t._s(t.withdraw))]),a("p",[t._v("提现金额")])])],1),a("van-popup",{attrs:{position:"bottom"},model:{value:t.$store.state.config.show_pop,callback:function(e){t.$set(t.$store.state.config,"show_pop",e)},expression:"$store.state.config.show_pop"}},[a("van-datetime-picker",{attrs:{"max-date":t.maxDate,"min-date":t.minDate,"cancel-button-text":"重置",title:"选择年月日",type:"date"},on:{cancel:t.cancelPicker,confirm:t.getCurrentTime},model:{value:t.currentDate,callback:function(e){t.currentDate=e},expression:"currentDate"}})],1)],1)},o=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("span",{staticClass:"formula"},[t._v(" 盈亏计算： "),a("b",{staticClass:"color-red"},[t._v("中奖-投注+活动+返点")])])}],r=a("21ef"),n={name:"profit",components:{userHeader:r["a"]},data:function(){return{nowDate:"",minDate:new Date(2020,0,1),maxDate:new Date(2021,10,1),currentDate:new Date,bet_total:0,bet_bonus:0,user_award:0,rebate:0,recharge:0,withdraw:0,dateSearch:!1}},created:function(){this.getStats(),this.getToday()},computed:{calProfit:function(){var t=parseFloat(this.bet_bonus)-parseFloat(this.bet_total)+parseFloat(this.user_award)+parseFloat(this.rebate);return t>0?t:0}},methods:{getStats:function(){var t=this,e={};this.dateSearch&&(e.date_start=this.nowDate),this.$get("user/stats",e).then((function(e){t.bet_total=e.data.bet_total,t.bet_bonus=e.data.bet_bonus,t.user_award=e.data.user_award,t.recharge=e.data.wallet_recharge,t.withdraw=e.data.wallet_withdraw}))},showTimePick:function(){this.$store.state.config.show_pop=!0},getToday:function(){var t=new Date,e=t.getMonth()+1;this.nowDate=t.getFullYear()+"-"+e+"-"+t.getDate(),console.log(this.nowDate)},getCurrentTime:function(t){var e=t.getMonth()+1;this.nowDate=t.getFullYear()+"-"+e+"-"+t.getDate(),this.$store.state.config.show_pop=!1,this.dateSearch=!0,this.getStats()},cancelPicker:function(){this.$store.state.config.show_pop=!1,this.dateSearch=!1,this.getToday(),this.getStats()}}},i=n,c=(a("b194"),a("2877")),l=Object(c["a"])(i,s,o,!1,null,"77198df6",null);e["default"]=l.exports},"810d":function(t,e,a){},b194:function(t,e,a){"use strict";var s=a("810d"),o=a.n(s);o.a},cffd:function(t,e,a){},fb42:function(t,e,a){"use strict";var s=a("cffd"),o=a.n(s);o.a}}]);