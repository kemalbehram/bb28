(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-eb3a4e0c"],{2022:function(e,t,a){},"299e":function(e,t,a){"use strict";var r=a("2022"),n=a.n(r);n.a},5142:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("create-recharge",{ref:"create",staticClass:"mb-20"}),a("Card",{attrs:{"dis-hover":""}},[a("table-header-new",{attrs:{config:e.config,title:"修正记录"},on:{"update:config":function(t){e.config=t},"on-search":e.onSearch}},[a("div",{staticClass:"color-sub"},[e._v("此处展示历史修正记录，如果需要修改或者删除修正记录 请联系程序工程师")])]),a("table-data",{ref:"table",on:{"on-search":e.onSearch}})],1)],1)},n=[],s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("Card",{attrs:{"dis-hover":""}},[a("table-header-new",{staticClass:"mb-14",attrs:{title:"统计修正"}}),a("lett-loading",{attrs:{visible:e.loading},on:{click:function(t){e.loading=!1}}}),a("div",{staticClass:"display-flex mb-14"},[a("DatePicker",{staticClass:"width-100 mr-10",attrs:{placeholder:"请输入修正日期",type:"date"},on:{"on-change":e.setDate}}),a("Select",{staticClass:"mr-10",attrs:{placeholder:"请选择修正类型"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}},e._l(e.fix_name,(function(t,r){return a("Option",{key:r,attrs:{value:r}},[e._v(e._s(t))])})),1),a("Input",{staticClass:"pr-10",attrs:{placeholder:"用户ID"},model:{value:e.form.user_id,callback:function(t){e.$set(e.form,"user_id",t)},expression:"form.user_id"}},[a("span",{attrs:{slot:"prepend"},slot:"prepend"},[e._v("用户ID")])]),a("Input",{attrs:{placeholder:"修正金额"},model:{value:e.form.value,callback:function(t){e.$set(e.form,"value",t)},expression:"form.value"}},[a("span",{attrs:{slot:"prepend"},slot:"prepend"},[e._v("修正金额")])])],1),a("div",{staticClass:"display-flex mb-10"},[a("Input",{staticClass:"flex-1 mr-10",attrs:{placeholder:"请输入修正原因"},model:{value:e.form.remark,callback:function(t){e.$set(e.form,"remark",t)},expression:"form.remark"}},[a("span",{attrs:{slot:"prepend"},slot:"prepend"},[e._v("修正原因")])]),a("Button",{attrs:{type:"primary"},on:{click:e.onConfirm}},[e._v("确认修正")])],1)],1)},l=[],i=(a("b0c0"),{data:function(){return{loading:!1,amount:null,form:{date:"",remark:"",value:"",name:""}}},computed:{fix_name:function(){return this.$store.state.config.data_fix_label}},methods:{onConfirm:function(){var e=this;if(this.form.amount=this.amount,!this.form.date)return this.$Message.warning("请选择修正日期");if(!this.form.name)return this.$Message.warning("请选择修正类型");if(!this.form.value)return this.$Message.warning("请输入修正金额");if(!this.form.remark)return this.$Message.warning("输入修正原因");var t="请确认要修正吗？";if(!0!==confirm(t))return!1;this.loading=!0,this.$post("stats-fix/create",this.form).then((function(t){if(200!==t.code)return e.loading=!1;e.form.date="",e.form.name="",e.form.value="",e.form.remark="",e.$parent.$refs.table.getIndex(),e.loading=t.message}))},setDate:function(e){this.form.date=e}}}),o=i,c=(a("299e"),a("2877")),u=Object(c["a"])(o,s,l,!1,null,"243fa114",null),d=u.exports,f=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"relative"},[a("lett-loading",{attrs:{visible:e.loading},on:{click:e.getIndex}}),a("Table",{attrs:{columns:e.columns,data:e.items,height:e._f("table_height")(510),border:""},scopedSlots:e._u([{key:"user",fn:function(e){var t=e.row;return a("table-user",{attrs:{data:t.user}})}},{key:"name",fn:function(t){var a=t.row;return[e._v(e._s(a.title)+" / "+e._s(a.name))]}},{key:"value",fn:function(t){var r=t.row;return a("span",{class:e.amountClass(r.value)},[e._v(e._s(r.value))])}},{key:"created_at",fn:function(e){var t=e.row;return a("table-date",{staticClass:"color-black",attrs:{date:t.created_at}})}}])}),a("lett-page",{attrs:{page:e.page},on:{"on-change":e.onPage}})],1)},m=[],p=(a("acd8"),a("adf6")),h={mixins:[p["a"]],data:function(){return{dataware:{index:{api:"/stats-fix/index"}},columns:[{title:"管理员ID",key:"admin_id",width:120},{title:"修正日期",key:"date",width:180},{title:"修正类型",slot:"name"},{title:"用户",slot:"user",width:160},{title:"修正金额",slot:"value",align:"right",width:100},{title:"操作备注",key:"remark"},{title:"时间",slot:"created_at",align:"center",width:160}]}},methods:{amountClass:function(e){return parseFloat(e)>0?"color-red":"color-green"}}},v=h,g=Object(c["a"])(v,f,m,!1,null,null,null),_=g.exports,b=a("c86f"),k={mixins:[b["a"]],name:"riskStatsFix",components:{tableData:_,createRecharge:d},data:function(){return{loading:!1,config:{value:{status:"0"},select:{field:"admin_id",value:""},fields:[{value:"admin_id",label:"管理员ID",default:!0},{value:"id",label:"流水ID"},{value:"value",label:"操作金额"}]}}}},w=k,x=Object(c["a"])(w,r,n,!1,null,null,null);t["default"]=x.exports}}]);