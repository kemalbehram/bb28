(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0e5b24"],{"961b":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("grid-form",{attrs:{gutter:20,left:"12",right:"12"}},[a("template",{slot:"left"},[a("Card",{attrs:{"dis-hover":"",title:"基本设置"}},[a("Button",{attrs:{slot:"extra",size:"small",type:"primary"},on:{click:t.updateData},slot:"extra"},[t._v("保存设置")]),a("lett-loading",{attrs:{visible:t.loading},on:{click:t.getData}}),a("Form",{staticClass:"pt-10",attrs:{"label-width":100,"label-position":"left"}},[a("FormItem",{attrs:{label:"彩票控制"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_lotto_control,callback:function(e){t.$set(t.items,"master_lotto_control",e)},expression:"items.master_lotto_control"}})],1),a("FormItem",{attrs:{label:"下分双模式"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_trans_dual_mode,callback:function(e){t.$set(t.items,"master_trans_dual_mode",e)},expression:"items.master_trans_dual_mode"}})],1),a("FormItem",{attrs:{label:"客服头像"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_service_avatar,callback:function(e){t.$set(t.items,"master_service_avatar",e)},expression:"items.master_service_avatar"}})],1),a("FormItem",{attrs:{label:"MOCK中奖"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_mock_winner,callback:function(e){t.$set(t.items,"master_mock_winner",e)},expression:"items.master_mock_winner"}})],1),a("FormItem",{attrs:{label:"平台初始资金"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_app_init_fund,callback:function(e){t.$set(t.items,"master_app_init_fund",e)},expression:"items.master_app_init_fund"}})],1),a("FormItem",{attrs:{label:"Build_PC"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_build_id_pc,callback:function(e){t.$set(t.items,"master_build_id_pc",e)},expression:"items.master_build_id_pc"}})],1),a("FormItem",{attrs:{label:"Build_WAP"}},[a("Input",{attrs:{type:"text"},model:{value:t.items.master_build_id_wap,callback:function(e){t.$set(t.items,"master_build_id_wap",e)},expression:"items.master_build_id_wap"}})],1)],1)],1)],1)],2)},i=[],r={data:function(){return{loading:!1,items:{}}},created:function(){this.getData()},methods:{updateData:function(){this.$post("/option/update/patch",this.items)},getData:function(){var t=this,e={regexp:"master"};this.$get("/option/get",e).then((function(e){t.items=e.data}))}}},l=r,n=a("2877"),m=Object(n["a"])(l,s,i,!1,null,null,null);e["default"]=m.exports}}]);