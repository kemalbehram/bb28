(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-081d6099"],{"21ef":function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"header",class:t.fixed?"fixed":""},[n("van-icon",{attrs:{color:"white",name:"arrow-left",size:"22px"},on:{click:function(e){return t.toBack()}}}),t.title?n("h2",[t._v(t._s(t.title))]):t._e(),n("span",{staticClass:"slot"},[t._t("default")],2)],1)},o=[],s={props:{icon:{default:"play-circle",type:String},title:{default:"",type:String},close:{default:!1,type:Boolean},fixed:{default:!1,type:Boolean}},methods:{toBack:function(){this.close?this.$store.state.config.show_pop=!1:this.$router.back(-1)}}},c=s,i=(n("fb42"),n("2877")),r=Object(i["a"])(c,a,o,!1,null,"2f7e4949",null);e["a"]=r.exports},7116:function(t,e,n){},a087:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("userHeader",{attrs:{title:"修改昵称"}}),n("van-field",{staticClass:"mb-40 round",attrs:{border:!1,size:"large",placeholder:"请输入新昵称"},model:{value:t.form.nickname,callback:function(e){t.$set(t.form,"nickname",e)},expression:"form.nickname"}}),n("van-button",{attrs:{block:"",type:"danger"},on:{click:t.onSave}},[t._v("保存")])],1)},o=[],s=n("21ef"),c={data:function(){return{form:{nickname:this.$store.state.user.info.nickname,method:"nicknameUpdate"}}},components:{userHeader:s["a"]},methods:{onSave:function(){var t=this;this.$post("/user/update",this.form).then((function(e){200===e.code&&(t.$store.state.user.info.nickname=e.data.nickname,t.$router.go(-1))}))}}},i=c,r=(n("e99c"),n("2877")),l=Object(r["a"])(i,a,o,!1,null,"0915baf3",null);e["default"]=l.exports},cffd:function(t,e,n){},e99c:function(t,e,n){"use strict";var a=n("7116"),o=n.n(a);o.a},fb42:function(t,e,n){"use strict";var a=n("cffd"),o=n.n(a);o.a}}]);