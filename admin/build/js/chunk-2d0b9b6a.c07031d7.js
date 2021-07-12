(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0b9b6a"],{"33b6":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("grid-form",{attrs:{gutter:20,left:"12",right:"12"}},[s("template",{slot:"left"},[s("Card",{attrs:{"dis-hover":"",title:"基本设置"}},[s("Button",{attrs:{slot:"extra",size:"small",type:"primary"},on:{click:t.updateData},slot:"extra"},[t._v("保存设置")]),s("lett-loading",{attrs:{visible:t.loading},on:{click:t.getData}}),s("Form",{staticClass:"pt-10",attrs:{"label-width":100,"label-position":"left"}},[s("FormItem",{attrs:{label:"网站标题后缀"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.web_title,callback:function(e){t.$set(t.items,"web_title",e)},expression:"items.web_title"}})],1),s("FormItem",{attrs:{label:"PC顶部LOGO"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.logo_web_header,callback:function(e){t.$set(t.items,"logo_web_header",e)},expression:"items.logo_web_header"}})],1),s("FormItem",{attrs:{label:"PC底部LOGO"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.logo_web_footer,callback:function(e){t.$set(t.items,"logo_web_footer",e)},expression:"items.logo_web_footer"}})],1),s("FormItem",{attrs:{label:"PC头部广告"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.banner_web_1,callback:function(e){t.$set(t.items,"banner_web_1",e)},expression:"items.banner_web_1"}})],1),s("FormItem",{attrs:{label:"PC文章广告"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.banner_web_2,callback:function(e){t.$set(t.items,"banner_web_2",e)},expression:"items.banner_web_2"}})],1),s("FormItem",{attrs:{label:"PC底部版权"}},[s("Input",{attrs:{rows:2,type:"textarea"},model:{value:t.items.web_copyright,callback:function(e){t.$set(t.items,"web_copyright",e)},expression:"items.web_copyright"}})],1),s("FormItem",{attrs:{label:"首页热门活动"}},[s("Input",{staticClass:"mb-10",attrs:{placeholder:"首页热门活动图片",type:"text"},model:{value:t.items.web_home_promotion_image,callback:function(e){t.$set(t.items,"web_home_promotion_image",e)},expression:"items.web_home_promotion_image"}}),s("Input",{staticClass:"mb-10",attrs:{placeholder:"首页热门活动标题",type:"text"},model:{value:t.items.web_home_promotion_title,callback:function(e){t.$set(t.items,"web_home_promotion_title",e)},expression:"items.web_home_promotion_title"}}),s("Input",{attrs:{placeholder:"首页热门活动时间",type:"text"},model:{value:t.items.web_home_promotion_time,callback:function(e){t.$set(t.items,"web_home_promotion_time",e)},expression:"items.web_home_promotion_time"}})],1),s("FormItem",{attrs:{label:"PC底部法律"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.web_lawyer,callback:function(e){t.$set(t.items,"web_lawyer",e)},expression:"items.web_lawyer"}})],1),s("FormItem",{attrs:{label:"单IP登陆限制"}},[s("Input",{attrs:{placeholder:"每个IP最多可登陆用户，填0为不限制",type:"text"},model:{value:t.items.ip_limit,callback:function(e){t.$set(t.items,"ip_limit",e)},expression:"items.ip_limit"}})],1),s("FormItem",{attrs:{label:"限制IP白名单"}},[s("Input",{attrs:{rows:6,placeholder:"请输入IP白名单，多个IP用小写逗号分隔",type:"textarea"},model:{value:t.items.ip_white,callback:function(e){t.$set(t.items,"ip_white",e)},expression:"items.ip_white"}})],1),s("FormItem",{attrs:{label:"客服QQ两个"}},[s("Input",{attrs:{rows:2,type:"textarea"},model:{value:t.items.service_qq,callback:function(e){t.$set(t.items,"service_qq",e)},expression:"items.service_qq"}})],1),s("FormItem",{attrs:{label:"网站备用域名"}},[s("Input",{attrs:{rows:4,type:"textarea"},model:{value:t.items.domain_backup,callback:function(e){t.$set(t.items,"domain_backup",e)},expression:"items.domain_backup"}})],1),s("FormItem",{attrs:{label:"转账手续费"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.transfer_fee_rate,callback:function(e){t.$set(t.items,"transfer_fee_rate",e)},expression:"items.transfer_fee_rate"}})],1),s("FormItem",{attrs:{label:"用户笔笔返"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.transfer_award_user_base,callback:function(e){t.$set(t.items,"transfer_award_user_base",e)},expression:"items.transfer_award_user_base"}})],1),s("FormItem",{attrs:{label:"用户首冲奖励"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.transfer_award_user_first,callback:function(e){t.$set(t.items,"transfer_award_user_first",e)},expression:"items.transfer_award_user_first"}})],1),s("FormItem",{attrs:{label:"下线流水日返"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.ref_1_bet_rebate,callback:function(e){t.$set(t.items,"ref_1_bet_rebate",e)},expression:"items.ref_1_bet_rebate"}})],1),s("FormItem",{attrs:{label:"余额宝日利率"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.wallet_fund_rate,callback:function(e){t.$set(t.items,"wallet_fund_rate",e)},expression:"items.wallet_fund_rate"}})],1),s("FormItem",{attrs:{label:"默认用户查询"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.user_query_default,callback:function(e){t.$set(t.items,"user_query_default",e)},expression:"items.user_query_default"}})],1),s("FormItem",{attrs:{label:"下注报警下限"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.bet_warning_total,callback:function(e){t.$set(t.items,"bet_warning_total",e)},expression:"items.bet_warning_total"}})],1),s("FormItem",{attrs:{label:"中奖报警下限"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.bet_warning_bonus,callback:function(e){t.$set(t.items,"bet_warning_bonus",e)},expression:"items.bet_warning_bonus"}})],1),s("FormItem",{attrs:{label:"万能验证码"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.sms_un_check_code,callback:function(e){t.$set(t.items,"sms_un_check_code",e)},expression:"items.sms_un_check_code"}})],1),s("FormItem",{attrs:{label:"充值笔笔送"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.recharge_back,callback:function(e){t.$set(t.items,"recharge_back",e)},expression:"items.recharge_back"}})],1),s("FormItem",{attrs:{label:"USDT汇率"}},[s("Input",{attrs:{type:"text"},model:{value:t.items.usdt_rate,callback:function(e){t.$set(t.items,"usdt_rate",e)},expression:"items.usdt_rate"}})],1),s("FormItem",{attrs:{label:"支付宝充值"}},[s("i-switch",{model:{value:t.items.alipay,callback:function(e){t.$set(t.items,"alipay",e)},expression:"items.alipay"}})],1),s("FormItem",{attrs:{label:"微信充值"}},[s("i-switch",{model:{value:t.items.wechat,callback:function(e){t.$set(t.items,"wechat",e)},expression:"items.wechat"}})],1),s("FormItem",{attrs:{label:"银联充值"}},[s("i-switch",{model:{value:t.items.bank,callback:function(e){t.$set(t.items,"bank",e)},expression:"items.bank"}})],1),s("FormItem",{attrs:{label:"USDT充值"}},[s("i-switch",{model:{value:t.items.usdt,callback:function(e){t.$set(t.items,"usdt",e)},expression:"items.usdt"}})],1)],1)],1)],1),s("template",{slot:"right"},[s("Card",{attrs:{"dis-hover":"",title:"其它设置"}},[s("Button",{attrs:{slot:"extra",size:"small",type:"primary"},on:{click:t.updateData},slot:"extra"},[t._v("保存设置")]),s("lett-loading",{attrs:{visible:t.loading},on:{click:t.getData}}),s("Form",{staticClass:"pt-10",attrs:{"label-width":100,"label-position":"left"}},[s("FormItem",{attrs:{label:"封盘前10秒提示"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.second_notice,callback:function(e){t.$set(t.items,"second_notice",e)},expression:"items.second_notice"}})],1),s("FormItem",{attrs:{label:"封盘提示"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.close_notice,callback:function(e){t.$set(t.items,"close_notice",e)},expression:"items.close_notice"}})],1),s("FormItem",{attrs:{label:"开盘提示"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.open_notice,callback:function(e){t.$set(t.items,"open_notice",e)},expression:"items.open_notice"}})],1),s("FormItem",{attrs:{label:"广告1"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.advertising_1,callback:function(e){t.$set(t.items,"advertising_1",e)},expression:"items.advertising_1"}})],1),s("FormItem",{attrs:{label:"广告2"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.advertising_2,callback:function(e){t.$set(t.items,"advertising_2",e)},expression:"items.advertising_2"}})],1),s("FormItem",{attrs:{label:"银行中心简介"}},[s("Input",{attrs:{rows:12,type:"textarea"},model:{value:t.items.bank_intro,callback:function(e){t.$set(t.items,"bank_intro",e)},expression:"items.bank_intro"}})],1),s("FormItem",{attrs:{label:"下线推广简介"}},[s("Input",{attrs:{rows:14,type:"textarea"},model:{value:t.items.refer_intro,callback:function(e){t.$set(t.items,"refer_intro",e)},expression:"items.refer_intro"}})],1),s("FormItem",{attrs:{label:"聊天下注教程"}},[s("Input",{attrs:{rows:14,type:"textarea"},model:{value:t.items.bet_chat_tutorial,callback:function(e){t.$set(t.items,"bet_chat_tutorial",e)},expression:"items.bet_chat_tutorial"}})],1),s("FormItem",{attrs:{label:"用户转账简介"}},[s("Input",{attrs:{rows:8,type:"textarea"},model:{value:t.items.transfer_intro_user,callback:function(e){t.$set(t.items,"transfer_intro_user",e)},expression:"items.transfer_intro_user"}})],1),s("FormItem",{attrs:{label:"三方统计代码"}},[s("Input",{attrs:{rows:4,type:"textarea"},model:{value:t.items.web_stats_code,callback:function(e){t.$set(t.items,"web_stats_code",e)},expression:"items.web_stats_code"}})],1)],1)],1)],1)],2)},i=[],r={data:function(){return{loading:!1,items:{}}},created:function(){this.getData()},methods:{updateData:function(){this.$post("/option/update/patch",this.items)},getData:function(){var t=this;this.$get("/option/get").then((function(e){t.items=e.data,t.items.alipay=1==t.items.alipay,t.items.bank=1==t.items.bank,t.items.wechat=1==t.items.wechat,t.items.usdt=1==t.items.usdt}))}}},l=r,o=s("2877"),n=Object(o["a"])(l,a,i,!1,null,null,null);e["default"]=n.exports}}]);