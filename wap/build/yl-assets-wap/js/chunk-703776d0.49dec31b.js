(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-703776d0"],{"21ef":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"header",class:t.fixed?"fixed":""},[a("van-icon",{attrs:{color:"white",name:"arrow-left",size:"22px"},on:{click:function(e){return t.toBack()}}}),t.title?a("h2",[t._v(t._s(t.title))]):t._e(),a("span",{staticClass:"slot"},[t._t("default")],2)],1)},n=[],i={props:{icon:{default:"play-circle",type:String},title:{default:"",type:String},close:{default:!1,type:Boolean},fixed:{default:!1,type:Boolean}},methods:{toBack:function(){this.close?this.$store.state.config.show_pop=!1:this.$router.back(-1)}}},c=i,o=(a("fb42"),a("2877")),r=Object(o["a"])(c,s,n,!1,null,"2f7e4949",null);e["a"]=r.exports},3574:function(t,e,a){"use strict";var s=a("f292"),n=a.n(s);n.a},"440a":function(t,e,a){"use strict";var s=a("cb4c"),n=a.n(s);n.a},"50cb":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("userHeader",{staticClass:"flex-header",attrs:{title:"红包聊天室"}}),a("div",{staticClass:"chat-content",class:{anim:1==t.animate},attrs:{id:"chat-content"}},t._l(t.getChats,(function(e,s){return a("div",{key:s,class:e.user.id==t.$store.state.user.info.id?"chat-item right":"chat-item"},[1e5==e.user.id||e.user.wechat?a("van-image",{staticClass:"chat-common-avatar",attrs:{src:e.user.avatar,heigth:"38",radius:"38"}}):a("div",{staticClass:"chat-common-avatar",class:t._f("sub_last")(e.user.id)},[a("span",[t._v(t._s(t._f("cut_name")(e.user.nickname)))])]),a("div",{staticClass:"name-info",class:e.user.id==t.$store.state.user.info.id?"self":""},[a("div",{staticClass:"nickname",class:1e5==e.user.id?"nickname-red":""},[t._v(t._s(e.user.nickname))]),a("div",{staticClass:"chat-time pl-10"},[t._v(t._s(t._f("date_lint")(e.created_at)))])]),1==e.type?a("div",{staticClass:"chat-info"},[a("span",{staticClass:"font-16"},[t._v(t._s(e.message))])]):a("span",{staticClass:"red-bag-info",on:{click:function(a){return t.showDetail(e.redbag.id,s)}}},[!1===e.has_received?a("img",{staticStyle:{display:"inline-block",height:"85px"},attrs:{src:t.red_bag_bg,alt:"",srcset:""}}):a("img",{staticStyle:{display:"inline-block",height:"85px"},attrs:{src:t.rb_recived,alt:"",srcset:""}})])],1)})),0),a("van-popup",{staticClass:"overflow",attrs:{"close-icon":"close",closeable:"","get-container":"#chat-content",round:""},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}},[a("red-bad-detail",{attrs:{detail:t.detail,index:t.choose_index},on:{success:t.modifyStatus}})],1)],1)},n=[],i=(a("99af"),a("ac1f"),a("1276"),a("21ef")),c=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"conetnt bg-white"},[t.content.code?a("div",[a("div",{staticClass:"action"},[a("div",{staticClass:"action-title"},[t._v("恭喜发财,大吉大利")]),200==t.detail.code?a("div",{staticClass:"pb-10"},[a("van-button",{attrs:{type:"primary"},on:{click:t.receiveBag}},[t._v("立即领取")])],1):a("div",{staticClass:"pb-10"},[a("span",{staticClass:"font-18 color-red"},[t._v(t._s(t.content.message))])])]),a("div",{staticClass:"line"},[a("span",[t._v("已领取"+t._s(t.content.data.logs.length)+"个")]),a("span",[t._v(t._s(t.content.data.received)+"/"+t._s(t.content.data.total))])]),a("div",[t.content.data.logs.length>0?a("div"):a("van-empty",{attrs:{description:"还没有人领取红包哦"}})],1)]):a("van-loading",{attrs:{type:"spinner"}})],1)},o=[],r=(a("a9e3"),{props:{detail:{type:Object,default:{}},index:{type:Number,default:0}},computed:{content:function(){return this.detail}},methods:{receiveBag:function(){var t=this,e={};e.id=this.content.data.id,setTimeout((function(){t.$post("red-bag/chatReceive",e).then((function(e){200==e.code&&(t.$emit("success",t.index),t.content.data.logs.unshift({amount:e.data.amount,user:t.$store.state.user.info}),t.content.code=400,t.content.message="您已领取过该红包")}))}),1e3)}}}),l=r,d=(a("440a"),a("2877")),u=Object(d["a"])(l,c,o,!1,null,"a48d1bdc",null),f=u.exports,h={data:function(){return{message:"",loading:!1,loading_send:!1,finished:!1,chat_items:[],page:{current:1},red_bag_bg:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/red_ico.png",rb_recived:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/icon/red_over_ico.png",page_loading:!0,animate:!1,show:!1,detail:{},choose_index:null}},components:{userHeader:i["a"],redBadDetail:f},created:function(){var t=this;setTimeout((function(){t.page_loading=!1}),1e3)},methods:{showDetail:function(t,e){var a=this;this.$get("/red-bag/detail",{id:t}).then((function(t){if(a.detail=t,a.choose_index=e,a.show=!0,200!=t.code)return console.log(t),console.log(t.message),a.$notify(t.message)}))},sendMessage:function(){var t=this,e={message:this.message};if(""==this.message)return this.$notify("请添加发送内容");this.loading_send=!0,this.$post("chat/send",e,!1,!1).then((function(e){return t.loading_send=!1,t.message="",t.$store.dispatch("chatsScrollBottom"),t.$notify(e.message)}))},getIndex:function(){var t=this,e={};e.page=this.page.current+1,this.loading=!0,this.$get("chat/index",e).then((function(e){t.loading=!1,t.finished=!0,t.page_loading=!1;var a=e.data.items.reverse();t.$store.state.chat.chat_contents=a.concat(t.$store.state.chat.chat_contents),t.page=e.data.page}))},getUnix:function(){var t=new Date;return t.getTime()},getTodayUnix:function(){var t=new Date;return t.setHours(0),t.setMinutes(0),t.setSeconds(0),t.setMilliseconds(0),t.getTime()},getYearUnix:function(){var t=new Date;return t.setMonth(0),t.setDate(1),t.setHours(0),t.setMinutes(0),t.setSeconds(0),t.setMilliseconds(0),t.getTime()},getLastDate:function(t){var e=new Date(t),a=e.getMonth()+1<10?"0"+(e.getMonth()+1):e.getMonth()+1,s=e.getDate()<10?"0"+e.getDate():e.getDate();return e.getFullYear()+"-"+a+"-"+s},modifyStatus:function(t){this.getChats[t].has_received=!0},receiveBag:function(t,e){var a=this,s={};s.id=t,setTimeout((function(){a.$post("red-bag/chatReceive",s).then((function(t){a.$toast.clear(),200==t.code&&(a.getChats[e].has_received=!0)}))}),1e3)}},watch:{getChats:function(t){this.animate=!0;var e=document.getElementById("chat-content");e.scrollTo({top:e.scrollHeight,behavior:"smooth"})},show:function(t){0==t&&(this.detail={})}},computed:{getOnlineUsers:function(){return this.$store.state.chat.online_user.length},getChats:function(){return this.$store.state.chat.chat_contents},getFormatTime:function(){return function(t){var e=t.split(/[- : \/]/),a=new Date(e[0],e[1]-1,e[2],e[3],e[4],e[5]);t=a.getTime();var s=this.getUnix(),n=this.getTodayUnix(),i=(this.getYearUnix(),(s-t)/1e3),c="";return c=i<=0||Math.floor(i/60)<=0?"刚刚":i<3600?Math.floor(i/60)+"分钟前":i>=3600&&t-n>=0?Math.floor(i/3600)+"小时前":i/86400<=31?Math.ceil(i/86400)+"天前":this.getLastDate(t),c}}}},g=h,p=(a("3574"),Object(d["a"])(g,s,n,!1,null,"d490cc00",null));e["default"]=p.exports},5899:function(t,e){t.exports="\t\n\v\f\r                　\u2028\u2029\ufeff"},"58a8":function(t,e,a){var s=a("1d80"),n=a("5899"),i="["+n+"]",c=RegExp("^"+i+i+"*"),o=RegExp(i+i+"*$"),r=function(t){return function(e){var a=String(s(e));return 1&t&&(a=a.replace(c,"")),2&t&&(a=a.replace(o,"")),a}};t.exports={start:r(1),end:r(2),trim:r(3)}},7156:function(t,e,a){var s=a("861d"),n=a("d2bb");t.exports=function(t,e,a){var i,c;return n&&"function"==typeof(i=e.constructor)&&i!==a&&s(c=i.prototype)&&c!==a.prototype&&n(t,c),t}},a9e3:function(t,e,a){"use strict";var s=a("83ab"),n=a("da84"),i=a("94ca"),c=a("6eeb"),o=a("5135"),r=a("c6b6"),l=a("7156"),d=a("c04e"),u=a("d039"),f=a("7c73"),h=a("241c").f,g=a("06cf").f,p=a("9bf2").f,v=a("58a8").trim,m="Number",_=n[m],b=_.prototype,y=r(f(b))==m,x=function(t){var e,a,s,n,i,c,o,r,l=d(t,!1);if("string"==typeof l&&l.length>2)if(l=v(l),e=l.charCodeAt(0),43===e||45===e){if(a=l.charCodeAt(2),88===a||120===a)return NaN}else if(48===e){switch(l.charCodeAt(1)){case 66:case 98:s=2,n=49;break;case 79:case 111:s=8,n=55;break;default:return+l}for(i=l.slice(2),c=i.length,o=0;o<c;o++)if(r=i.charCodeAt(o),r<48||r>n)return NaN;return parseInt(i,s)}return+l};if(i(m,!_(" 0o1")||!_("0b1")||_("+0x1"))){for(var C,w=function(t){var e=arguments.length<1?0:t,a=this;return a instanceof w&&(y?u((function(){b.valueOf.call(a)})):r(a)!=m)?l(new _(x(e)),a,w):x(e)},$=s?h(_):"MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(","),I=0;$.length>I;I++)o(_,C=$[I])&&!o(w,C)&&p(w,C,g(_,C));w.prototype=b,b.constructor=w,c(n,m,w)}},cb4c:function(t,e,a){},cffd:function(t,e,a){},f292:function(t,e,a){},fb42:function(t,e,a){"use strict";var s=a("cffd"),n=a.n(s);n.a}}]);