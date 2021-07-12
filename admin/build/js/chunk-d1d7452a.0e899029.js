(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-d1d7452a"],{"866c":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("Card",{attrs:{"dis-hover":""}},[a("table-header-new",{attrs:{title:"所有管理员"}},[a("Button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.$refs.table.createData(!0)}}},[t._v("创建管理员")])],1),a("table-data",{ref:"table"})],1)},r=[],n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative"},[a("lett-loading",{attrs:{visible:t.loading},on:{click:t.getIndex}}),a("Table",{attrs:{columns:t.columns,data:t.items,border:""},scopedSlots:t._u([{key:"nickname",fn:function(e){var i=e.row;return[a("lett-avatar",{attrs:{src:i.avatar}}),t._v(" "+t._s(i.nickname)+" ")]}},{key:"permission",fn:function(e){var i=e.row;return t._l(i.permissions,(function(e,i){return a("Tag",{key:i},[t._v(t._s(e.title))])}))}},{key:"requestedAt",fn:function(e){var i=e.row;return i.requested_at?a("Tooltip",{attrs:{content:i.requested_at,placement:"top"}},[a("Time",{attrs:{interval:1,time:i.requested_at}})],1):t._e()}},{key:"action",fn:function(e){var i=e.row;e.index;return[a("Button",{staticClass:"mr-6",attrs:{size:"small",type:"primary"},on:{click:function(e){return t.updateData(!0,i)}}},[t._v("修改")]),a("Button",{attrs:{size:"small",type:"error"},on:{click:function(e){return t.deleteData(i)}}},[t._v("删除")])]}}],null,!0)}),a("lett-page",{attrs:{page:t.page},on:{"on-change":t.getIndex}}),a("modal-create",{attrs:{config:t.dataware.create,title:"创建管理员"},on:{"update:config":function(e){return t.$set(t.dataware,"create",e)},success:t.createData}}),a("modal-update",{attrs:{config:t.dataware.update,title:"修改管理员"},on:{"update:config":function(e){return t.$set(t.dataware,"update",e)},success:t.updateData}})],1)},s=[],o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("Drawer",{attrs:{"footer-hide":!0,title:"创建管理员",width:"720"},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[a("lett-loading",{attrs:{visible:t.loading}}),a("Form",{staticClass:"mt-20",attrs:{"label-width":70,"label-position":"right"}},[a("FormItem",{attrs:{label:"昵称"}},[a("Input",{attrs:{placeholder:"请输入管理员昵称"},model:{value:t.form.nickname,callback:function(e){t.$set(t.form,"nickname",e)},expression:"form.nickname"}})],1),a("FormItem",{attrs:{label:"用户名"}},[a("Input",{attrs:{placeholder:"请输入登录用户名"},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),a("FormItem",{staticClass:"mb-40",attrs:{label:"初始密码"}},[a("Input",{attrs:{placeholder:"请输入初始登录密码"},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),a("Button",{attrs:{long:"",type:"primary"},on:{click:t.confirm}},[t._v("确认创建")])],1)],1)},l=[],c={props:{config:{}},data:function(){return{loading:!1}},computed:{visible:{get:function(){return this.config.visible},set:function(){this.config.visible=!1,this.config.data={}}},form:{get:function(){return{password:""}},set:function(){}}},methods:{confirm:function(){var t=this;this.$post(this.config.api,this.form).then((function(e){t.form={},t.$emit("success",!1,e.data)}))}}},u=c,m=a("2877"),d=Object(m["a"])(u,o,l,!1,null,null,null),f=d.exports,p=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("Drawer",{attrs:{"footer-hide":!0,title:t.title,width:"720"},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[a("lett-loading",{attrs:{visible:t.loading}}),a("Form",{staticClass:"mt-20",attrs:{"label-width":70,"label-position":"right"}},[a("FormItem",{attrs:{label:"昵　称"}},[a("Input",{attrs:{placeholder:""},model:{value:t.form.nickname,callback:function(e){t.$set(t.form,"nickname",e)},expression:"form.nickname"}})],1),a("FormItem",{attrs:{label:"用户名"}},[a("Input",{attrs:{placeholder:""},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),a("FormItem",{attrs:{label:"禁止使用"}},[a("i-switch",{model:{value:t.form.disable,callback:function(e){t.$set(t.form,"disable",e)},expression:"form.disable"}}),a("span",{staticClass:"form-desc-middle"},[t._v("开启禁用后，该管理员将无法使用")])],1),a("FormItem",{staticClass:"pb-20"},[a("Button",{attrs:{type:"primary"},on:{click:t.updateProfile}},[t._v("保存资料")])],1),a("Divider"),a("FormItem",{staticClass:"pt-20",attrs:{label:"新密码"}},[a("Input",{attrs:{placeholder:"请输入用户新密码"},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),a("FormItem",{attrs:{label:"确认密码"}},[a("Input",{attrs:{placeholder:"请再次输入新密码"},model:{value:t.form.password_confirmation,callback:function(e){t.$set(t.form,"password_confirmation",e)},expression:"form.password_confirmation"}})],1),a("FormItem",[a("Button",{attrs:{type:"primary"},on:{click:t.updatePassword}},[t._v("保存密码")])],1)],1)],1)},b=[],v={props:{config:{},action:"",title:""},data:function(){return{loading:!1}},computed:{visible:{get:function(){return this.config.visible},set:function(){this.config.visible=!1,this.config.data={}}},form:{get:function(){return this.config.data},set:function(){}}},methods:{updateProfile:function(){var t=this;this.form.method="profileUpdate",this.$post(this.config.api,this.form).then((function(e){t.$emit("success",!0,e.data,!0)}))},updatePassword:function(){this.form.method="passwordUpdate",this.$post(this.config.api,this.form)},updateAvatar:function(t){this.$emit("success",!0,t,!0)}}},h=v,g=(a("c7bc"),Object(m["a"])(h,p,b,!1,null,"6bd533fa",null)),k=g.exports,w=a("adf6"),_={mixins:[w["a"]],components:{modalCreate:f,modalUpdate:k},data:function(){return{dataware:{index:{api:"/administrator/index"},create:{api:"/administrator/create",visible:!1},update:{api:"/administrator/update",visible:!1,data:{},fields:["avatar","nickname","username","updated_at","created_at"]},delete:{api:"/administrator/delete"}},columns:[{title:"ID",key:"id",maxWidth:100},{title:"昵称",slot:"nickname"},{title:"用户名",key:"username",tooltip:!0},{title:"最后访问",key:"requested_at",maxWidth:200,slot:"requestedAt"},{title:"最后IP",key:"requested_ip",maxWidth:200},{title:"操作",slot:"action",width:160,align:"center"}],permissionAll:{}}}},x=_,I=Object(m["a"])(x,n,s,!1,null,null,null),$=I.exports,y=a("c86f"),F={mixins:[y["a"]],name:"administratorHome",components:{tableData:$}},C=F,D=Object(m["a"])(C,i,r,!1,null,null,null);e["default"]=D.exports},c2d7:function(t,e,a){},c7bc:function(t,e,a){"use strict";var i=a("c2d7"),r=a.n(i);r.a}}]);