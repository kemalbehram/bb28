(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-b241a668"],{"0a11":function(t,e,a){"use strict";a.r(e);var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("userHeader",{attrs:{title:"联系QQ"}}),a("div",{staticClass:"base-wrap pt-40"},[a("van-field",{staticClass:"mb-40 round",attrs:{border:!1,placeholder:"请输入QQ",size:"large"},model:{value:t.form.contact_qq,callback:function(e){t.$set(t.form,"contact_qq",e)},expression:"form.contact_qq"}}),a("div",{staticClass:"base-wrap"},[a("van-button",{attrs:{block:"",round:"",type:"danger"},on:{click:t.onSave}},[t._v("保存")])],1)],1)],1)},n=[],s=a("21ef"),c={components:{userHeader:s["a"]},data:function(){return{form:{contact_qq:this.$store.state.user.info.contact_qq,method:"contactQqUpdate"}}},methods:{onSave:function(){var t=this;this.$post("/user/update",this.form).then((function(e){200===e.code&&(t.$store.state.user.info.contact_qq=e.data.contact_qq,t.$router.go(-1))}))}}},r=c,i=a("2877"),l=Object(i["a"])(r,o,n,!1,null,null,null);e["default"]=l.exports},"21ef":function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"header",class:t.fixed?"fixed":""},[a("van-icon",{attrs:{color:"white",name:"arrow-left",size:"22px"},on:{click:function(e){return t.toBack()}}}),t.title?a("h2",[t._v(t._s(t.title))]):t._e(),a("span",{staticClass:"slot"},[t._t("default")],2)],1)},n=[],s={props:{icon:{default:"play-circle",type:String},title:{default:"",type:String},close:{default:!1,type:Boolean},fixed:{default:!1,type:Boolean}},methods:{toBack:function(){this.close?this.$store.state.config.show_pop=!1:this.$router.back(-1)}}},c=s,r=(a("fb42"),a("2877")),i=Object(r["a"])(c,o,n,!1,null,"2f7e4949",null);e["a"]=i.exports},cffd:function(t,e,a){},fb42:function(t,e,a){"use strict";var o=a("cffd"),n=a.n(o);n.a}}]);