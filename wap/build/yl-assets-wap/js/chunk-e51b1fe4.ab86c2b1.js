(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e51b1fe4"],{"07ac":function(t,e,o){var n=o("23e7"),a=o("6f53").values;n({target:"Object",stat:!0},{values:function(t){return a(t)}})},"0e1d":function(t,e,o){"use strict";var n=o("4616"),a=o.n(n);a.a},1008:function(t,e,o){},4616:function(t,e,o){},"4ebe":function(t,e,o){"use strict";var n=o("1008"),a=o.n(n);a.a},"4f4f":function(t,e,o){},5899:function(t,e){t.exports="\t\n\v\f\r                　\u2028\u2029\ufeff"},"58a8":function(t,e,o){var n=o("1d80"),a=o("5899"),r="["+a+"]",s=RegExp("^"+r+r+"*"),i=RegExp(r+r+"*$"),c=function(t){return function(e){var o=String(n(e));return 1&t&&(o=o.replace(s,"")),2&t&&(o=o.replace(i,"")),o}};t.exports={start:c(1),end:c(2),trim:c(3)}},6310:function(t,e,o){"use strict";var n=o("b585"),a=o.n(n);a.a},"6c40":function(t,e,o){"use strict";var n=o("a4f2"),a=o.n(n);a.a},"6f53":function(t,e,o){var n=o("83ab"),a=o("df75"),r=o("fc6a"),s=o("d1e7").f,i=function(t){return function(e){var o,i=r(e),c=a(i),l=c.length,u=0,f=[];while(l>u)o=c[u++],n&&!s.call(i,o)||f.push(t?[o,i[o]]:i[o]);return f}};t.exports={entries:i(!0),values:i(!1)}},"704b":function(t,e,o){"use strict";var n=o("abb1"),a=o.n(n);a.a},7156:function(t,e,o){var n=o("861d"),a=o("d2bb");t.exports=function(t,e,o){var r,s;return a&&"function"==typeof(r=e.constructor)&&r!==o&&n(s=r.prototype)&&s!==o.prototype&&a(t,s),t}},8989:function(t,e,o){"use strict";var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("van-popup",{staticClass:"lotto-win-pop",style:t.lotto.chat_open_log?"position:absolute;margin-top:25px;":"position:absolute;",attrs:{overlay:!1,"get-container":"#lotto-win-table",position:"top"},on:{closed:function(e){t.lotto.show_open_dom=!1}},model:{value:t.lotto.show_open_log,callback:function(e){t.$set(t.lotto,"show_open_log",e)},expression:"lotto.show_open_log"}},[o("keno",{attrs:{lotto:t.lotto}}),o("div",{staticClass:"more"},[o("van-button",{attrs:{color:"#fff",size:"small",type:"info"},on:{click:t.showTrend}},[t._v("查看更多开奖结果")])],1)],1)},a=[],r=(o("b0c0"),o("ac1f"),o("5319"),function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{class:t.lotto.chat_open_log?"lotto-win-table chat-open-log":"lotto-win-table"},[o("table",[t._m(0),o("tbody",[o("div",{staticClass:"pt-10"}),t._l(t.items,(function(e){return[o("tr",{key:e.id,attrs:{align:"center"}},[o("td",[t._v(t._s(e.short_id))]),o("td",[t._v(t._s(t._f("time_lint")(e.lotto_at)))]),o("td",[e.win_extend?o("div",{staticClass:"win-code-extend"},[o("span",{staticClass:"win-code-item color-white bg-blue",domProps:{innerHTML:t._s(e.win_extend.code_arr[0])}}),o("span",[t._v("+")]),o("span",{staticClass:"win-code-item color-white bg-blue",domProps:{innerHTML:t._s(e.win_extend.code_arr[1])}}),o("span",[t._v("+")]),o("span",{staticClass:"win-code-item color-white bg-blue",domProps:{innerHTML:t._s(e.win_extend.code_arr[2])}}),o("span",[t._v("=")]),o("span",{staticClass:"win-code-item color-white bg-red"},[t._v(t._s(e.win_extend.code_he))]),o("span",[t._v(t._s(e.win_extend.code_bos)+t._s(e.win_extend.code_sod))])]):o("div",[t._v("开奖中")])])])]})),o("div",{staticClass:"pb-10"})],2)])])}),s=[function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("thead",[o("tr",[o("th",[t._v("期号")]),o("th",[t._v("开奖时间")]),o("th",{attrs:{width:"186px"}},[t._v("开奖结果")])])])}],i={props:{lotto:Object,loading:Boolean},computed:{items:function(){return this.lotto.open_last},items_data:function(){if(this.items.length>0)return this.items;var t="lotto_table_data_default:keno",e=sessionStorage.getItem(t);if(e)return JSON.parse(e);for(var o={bet_count_down:100,bet_stats:{},float_odds:null,lotto_at:"2020-05-20 00:00:00",short_id:"0000000",status:1,win_extend:null},n={bet_count_down:-1,bet_stats:{},float_odds:{bet_people:"200",bet_total:"1000000.000"},lotto_at:"2020-05-20 00:00:00",short_id:"0000000",status:2,win_extend:{code_arr:[0,0,0],code_bos:"小",code_he:"0",code_sod:"双"}},a=[],r=0;r<15;r++)r>=5?a.push(n):a.push(o);return a.length>0&&sessionStorage.setItem(t,JSON.stringify(a)),a}}},c=i,l=(o("4ebe"),o("2877")),u=Object(l["a"])(c,r,s,!1,null,"71f92df4",null),f=u.exports,p={components:{keno:f},props:{lotto:{default:function(){return this.$parent},type:Object}},data:function(){return{loading:!1,page:{current:1},items:[],query:{source:"all",page:1}}},methods:{refreshIndex:function(){1===this.page.current&&this.getIndex(1,!1)},getIndex:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,o=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];this.query.page=e,o&&(this.loading=!0);var n="/lotto/"+this.$route.params.name+"/open-log";this.$get(n,this.query).then((function(e){if(200!==e.code)return!1;t.loading=!1,t.items=e.data.items,t.page=e.data.page}))},showTrend:function(){this.$router.replace({name:"lottoTrend",params:{name:this.$route.params.name}})},amountClass:function(t){if(parseFloat(t)>0)return"color-red"},onBetting:function(t){this.lotto.bet_current=this.lotto.bet_newest[t],this.lotto.current_tab=this.$store.state.user.option.bet_model}}},d=p,h=(o("6c40"),Object(l["a"])(d,n,a,!1,null,"7e5ee17e",null));e["a"]=h.exports},"8fb3":function(t,e,o){"use strict";var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"relative",attrs:{"lett-name":"nav-bar"}},[o("van-nav-bar",{attrs:{border:!1,fixed:"","left-arrow":""},on:{"click-left":function(e){return t.$store.dispatch("toBack")}}},[o("van-dropdown-menu",{attrs:{slot:"title",overlay:!1},slot:"title"},[o("van-dropdown-item",{ref:"roomitem",attrs:{title:t.title||"加载中"}},[o("van-grid",{attrs:{border:!1,"column-num":3,gutter:"12"}},t._l(t.getRoomList,(function(e,n){return e.is_open?o("van-grid-item",{key:n,on:{click:function(o){return t.toLotto(e.id)}}},[o("div",{staticClass:"lotto-item-content"},[o("p",{staticClass:"name"},[t._v(t._s(e.name))]),o("span",[t._v(t._s(e.dx)+"/"+t._s(e.zh))])])]):t._e()})),1)],1)],1),o("van-icon",{attrs:{slot:"right",name:"bars",size:"22"},on:{click:function(e){t.$refs.action.show=!0}},slot:"right"})],1),o("lotto-setting",{ref:"setting"}),o("nav-action",{ref:"action"}),o("lottoRule",{ref:"rule",attrs:{data:t.lotto.config}})],1)},a=[],r=(o("4160"),o("c975"),o("b0c0"),o("a9e3"),o("07ac"),o("159b"),function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("van-popup",{staticClass:"lotto-setting",attrs:{position:"bottom"},on:{close:t.updateStore,open:t.setForm},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}},[o("section-content",{attrs:{label:"设置如无特殊说明，将对所有游戏生效",subscript:"OPTION",title:"游戏设置"}},[o("van-cell-group",{attrs:{border:!0}},[o("van-cell",{attrs:{center:"",label:"打开后下注界面将已聊天模式展示",title:"聊天模式"}},[o("van-switch",{attrs:{slot:"right-icon","active-color":"#09c195",size:"24"},slot:"right-icon",model:{value:t.form.bet_chat,callback:function(e){t.$set(t.form,"bet_chat",e)},expression:"form.bet_chat"}})],1)],1)],1)],1)}),s=[],i={data:function(){return{show:!1,form:{bet_chat:!1}}},methods:{onUpdate:function(){this.form.method="optionUpdate",this.$post("user/update",this.form,!1,!1).then((function(t){}))},setForm:function(){this.form=JSON.parse(JSON.stringify(this.$store.state.user.option))},updateStore:function(){this.$parent.$refs.action.show=!1,this.$store.state.user.option=this.form,this.$store.dispatch("lottoScrollBottom"),this.onUpdate()}}},c=i,l=(o("0e1d"),o("2877")),u=Object(l["a"])(c,r,s,!1,null,"a763abda",null),f=u.exports,p=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("van-action-sheet",{attrs:{actions:t.actions,round:!1,"cancel-text":"关闭"},on:{select:t.onSelect},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}})},d=[],h={props:{content:String},data:function(){return{show:!1}},computed:{actions:function(){var t=[{name:"投注记录",action:"route",route:{name:"vote"}},{name:"游戏规则",action:"lottoRule"},{name:"我要充值",action:"route",route:{name:"walletRecharge"}},{name:"我要提现",action:"route",route:{name:"walletWithdraw"}}];return t}},methods:{onSelect:function(t){"route"===t.action&&this.$router.push(t.route),"lottoRule"===t.action&&(this.$parent.$refs.rule.show=!0),"lottotra"==t.action&&this.$router.push({name:"lottoTraRoom",params:{name:this.$route.params.name,room_id:this.$route.params.room_id}}),"lottochat"==t.action&&this.$router.push({name:"lottoChatRoom",params:{name:this.$route.params.name,room_id:this.$route.params.room_id}})}}},m=h,_=Object(l["a"])(m,p,d,!1,null,null,null),v=_.exports,b=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("van-popup",{staticClass:"nav-bar-action",attrs:{position:"bottom"},on:{close:function(e){t.$parent.$refs.action.show=!1}},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}},[o("div",{staticClass:"article-content"},[o("h5",[t._v("游戏/返水规则")]),o("div",{staticClass:"article-content--html",domProps:{innerHTML:t._s(t.getRule)}})])])},g=[],w={props:{data:Object},data:function(){return{show:!1}},computed:{getRule:function(){var t="room"+this.$route.params.room_id;return this.$store.state.config.play_types[t].rules}}},$=w,x=(o("6310"),Object(l["a"])($,b,g,!1,null,"333aee8e",null)),y=x.exports,C={name:"lottoHeader",props:{lotto:{default:function(){return this.$parent},type:Object},lotto_name:{type:String},room_id:{type:Number}},data:function(){return{}},components:{lottoSetting:f,navAction:v,lottoRule:y},computed:{items:function(){return Object.values(this.$store.state.config.lotto_items)},title:function(){var t=this.$store.state.config.lotto_items;return t[this.lotto_name].subtitle+"-房间"+this.room_id},getRoomList:function(){var t=[];return Object.values(this.$store.state.config.play_types).forEach((function(e,o){if(-1!=e.name.indexOf("room")){var n={};n.name=e.title,n.id=e.id,n.rules="<p>游戏规则游戏规则</p>",n.onlinePeople=888,n.img_url="https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/room_bg.png",n.dx=e.subtitle.dx,n.zh=e.subtitle.zh1+"/"+e.subtitle.zh2,n.is_open=e.is_open,t.push(n)}})),t}},methods:{toLotto:function(t){this.lotto.room_id=t;var e={name:"lottoChatRoom"==this.$route.name?"lottoChatRoom":"lottoTraRoom",params:{name:this.lotto_name,room_id:t}};return this.$refs.roomitem.toggle(),this.$router.push(e)},goback:function(){return this.$router.push({name:"lottoRoom",params:{name:this.$route.params.name}})}}},I=C,N=(o("704b"),Object(l["a"])(I,n,a,!1,null,"603c1a82",null));e["a"]=N.exports},"928b":function(t,e,o){"use strict";var n=o("4f4f"),a=o.n(n);a.a},"9e84":function(t,e,o){"use strict";var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"comfirm-area"},[o("van-dialog",{attrs:{lockScroll:!1,overlay:!1,"get-container":"#bet-confirm","show-cancel-button":"",title:"下注清单"},on:{cancel:t.cacelBet,confirm:t.lotto.betConfirmfinal},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[o("div",{staticClass:"bet-confirm",attrs:{border:!1}},[o("van-cell-group",[o("van-cell",{attrs:{"v-if":t.lotto.bet_list.length>0}},t._l(t.lotto.bet_list,(function(e,n){return o("div",[o("div",[t._v("【"+t._s(e.name)+"】× "+t._s(e.amount))])])})),0),o("van-cell",[t._v("【合计】总投注数:"+t._s(t.lotto.bet_total_count)+",总金额:"+t._s(t.lotto.bet_total))])],1)],1)]),o("van-overlay",{attrs:{show:t.overlay,id:"bet-confirm","z-index":"4000"}})],1)},a=[],r={props:{lotto:{default:function(){return this.$parent},type:Object}},data:function(){return{params:{},visible:!1,overlay:!1}},methods:{cacelBet:function(){this.overlay=!1,this.lotto.bet_list=[]}}},s=r,i=(o("928b"),o("2877")),c=Object(i["a"])(s,n,a,!1,null,"2cec5cb7",null);e["a"]=c.exports},a4f2:function(t,e,o){},a9e3:function(t,e,o){"use strict";var n=o("83ab"),a=o("da84"),r=o("94ca"),s=o("6eeb"),i=o("5135"),c=o("c6b6"),l=o("7156"),u=o("c04e"),f=o("d039"),p=o("7c73"),d=o("241c").f,h=o("06cf").f,m=o("9bf2").f,_=o("58a8").trim,v="Number",b=a[v],g=b.prototype,w=c(p(g))==v,$=function(t){var e,o,n,a,r,s,i,c,l=u(t,!1);if("string"==typeof l&&l.length>2)if(l=_(l),e=l.charCodeAt(0),43===e||45===e){if(o=l.charCodeAt(2),88===o||120===o)return NaN}else if(48===e){switch(l.charCodeAt(1)){case 66:case 98:n=2,a=49;break;case 79:case 111:n=8,a=55;break;default:return+l}for(r=l.slice(2),s=r.length,i=0;i<s;i++)if(c=r.charCodeAt(i),c<48||c>a)return NaN;return parseInt(r,n)}return+l};if(r(v,!b(" 0o1")||!b("0b1")||b("+0x1"))){for(var x,y=function(t){var e=arguments.length<1?0:t,o=this;return o instanceof y&&(w?f((function(){g.valueOf.call(o)})):c(o)!=v)?l(new b($(e)),o,y):$(e)},C=n?d(b):"MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(","),I=0;C.length>I;I++)i(b,x=C[I])&&!i(y,x)&&m(y,x,h(b,x));y.prototype=g,g.constructor=y,s(a,v,y)}},abb1:function(t,e,o){},b585:function(t,e,o){}}]);