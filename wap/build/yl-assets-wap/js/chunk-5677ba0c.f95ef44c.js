(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5677ba0c"],{"158e":function(t,n,e){"use strict";var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",[e("van-cell-group",{staticClass:"menu-group"},t._l(t.itemList,(function(n,a){return e("van-cell",{key:a,attrs:{border:!1,icon:n.icon,title:n.name,"is-link":""},on:{click:function(e){return t.toUrl(n)}}})})),1)],1)},o=[],i=(e("c975"),e("ac1f"),e("466d"),{data:function(){return{itemList:[{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"todo-list-o",to:"/vote",name:"投注记录"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"todo-list-o",to:"/user/account-change",name:"帐变明细"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"idcard",to:"/user/setting",name:"密码安全"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"todo-list-o",to:"/service",name:"在线客服"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"chat-o",to:"/service/wechat",name:"微信客服"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"more-o",to:"/user/setting/bind",name:"绑定账号"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"down",download:!0,to:"https://www.google.com",name:"APP下载"},{img_url:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_setting.png",icon:"setting-o",to:"/config/setting",name:"设置"}]}},methods:{toUrl:function(t){!0!==t.download?this.$router.push({path:t.to}):this.toDownload()},toDownload:function(){var t=navigator.userAgent,n=t.indexOf("Android")>-1||t.indexOf("Adr")>-1,e=!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);n&&(location.href="http://yl.digin-wywyn-ndfb.superwaters.cn/andriod.html"),e&&(location.href="http://yl.digin-wywyn-ndfb.superwaters.cn/ios.html")}}}),s=i,c=(e("a060"),e("2877")),r=Object(c["a"])(s,a,o,!1,null,"333963b6",null);n["a"]=r.exports},"466d":function(t,n,e){"use strict";var a=e("d784"),o=e("825a"),i=e("50c4"),s=e("1d80"),c=e("8aa5"),r=e("14c3");a("match",1,(function(t,n,e){return[function(n){var e=s(this),a=void 0==n?void 0:n[t];return void 0!==a?a.call(n,e):new RegExp(n)[t](String(e))},function(t){var a=e(n,t,this);if(a.done)return a.value;var s=o(t),l=String(this);if(!s.global)return r(s,l);var u=s.unicode;s.lastIndex=0;var m,d=[],p=0;while(null!==(m=r(s,l))){var f=String(m[0]);d[p]=f,""===f&&(s.lastIndex=c(l,i(s.lastIndex),u)),p++}return 0===p?null:d}]}))},"4b4c":function(t,n,e){},"6c9e":function(t,n,e){"use strict";var a=e("a146"),o=e.n(a);o.a},"878f":function(t,n,e){"use strict";var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("van-tabbar",{staticClass:"shadow-top",attrs:{border:!1,route:!0,"z-index":999,"active-color":"#ff6a00"}},[e("van-tabbar-item",{attrs:{icon:"wap-home",replace:"",to:"/"}},[e("span",[t._v("首页")])]),e("van-tabbar-item",{attrs:{icon:"card",to:"/wallet"}},[e("span",[t._v("充提")])]),e("van-tabbar-item",{attrs:{icon:"friends-o",to:"/user/promote"}},[e("span",[t._v("代理")])]),e("van-tabbar-item",{attrs:{icon:"manager",to:"/user"}},[e("span",[t._v("我的")])])],1)},o=[],i={data:function(){return{}}},s=i,c=(e("9f19"),e("2877")),r=Object(c["a"])(s,a,o,!1,null,"656c3956",null);n["a"]=r.exports},"9bf1":function(t,n,e){"use strict";var a=e("f8a1"),o=e.n(a);o.a},"9f19":function(t,n,e){"use strict";var a=e("b06f"),o=e.n(a);o.a},a060:function(t,n,e){"use strict";var a=e("4b4c"),o=e.n(a);o.a},a146:function(t,n,e){},b06f:function(t,n,e){},da45:function(t,n,e){"use strict";e.r(n);var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"wrap pb-64"},[t._m(0),e("van-cell",{attrs:{label:t.item.title,icon:"volume","is-link":"",title:"公告通知",to:"/notice-list"}}),e("van-cell",{attrs:{icon:"service","is-link":"",label:"提供各种问题疑难排解",title:"在线客服",to:"/service"}}),e("lett-Tabbar")],1)},o=[function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"header"},[e("span",[t._v("最新游戏")])])}],i=e("878f"),s=e("fb91"),c={components:{lettTabbar:i["a"],headerIcon:s["a"]},data:function(){return{item:{}}},created:function(){this.getIndex()},methods:{getIndex:function(){var t=this;this.$get("/article/1001/new",{},!1).then((function(n){200===n.code&&(t.item=n.data.item)}))}}},r=c,l=(e("9bf1"),e("2877")),u=Object(l["a"])(r,a,o,!1,null,"71aa751c",null);n["default"]=u.exports},f8a1:function(t,n,e){},fb91:function(t,n,e){"use strict";var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",[e("img",{attrs:{alt:"",src:"https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/user-icon.png"},on:{click:t.showPop}}),e("van-popup",{attrs:{position:"left"},model:{value:t.show,callback:function(n){t.show=n},expression:"show"}},[e("div",{staticClass:"top"},[e("img",{attrs:{src:t.user.avatar,alt:""}}),e("span",{staticClass:"nickname"},[t._v(t._s(t.user.nickname))]),e("span",{staticClass:"mobile"},[t._v("账号："+t._s(t.user.mobile))]),e("span",{staticClass:"id"},[t._v("ID："+t._s(t.user.id))])]),e("action-menu")],1)],1)},o=[],i=(e("e7e5"),e("d399")),s=e("158e"),c={data:function(){return{show:!1}},components:{actionMenu:s["a"]},computed:{user:function(){return this.$store.state.user.info}},methods:{showPop:function(){void 0!=this.$store.state.user.token&&""!=this.$store.state.user.token?this.show=!0:i["a"].fail("请先登录")}}},r=c,l=(e("6c9e"),e("2877")),u=Object(l["a"])(r,a,o,!1,null,"1f6dc144",null);n["a"]=u.exports}}]);