(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6f827c42"],{"13d5":function(t,e,a){"use strict";var r=a("23e7"),n=a("d58f").left,s=a("a640"),o=a("ae40"),l=s("reduce"),i=o("reduce",{1:0});r({target:"Array",proto:!0,forced:!l||!i},{reduce:function(t){return n(this,t,arguments.length,arguments.length>1?arguments[1]:void 0)}})},3489:function(t,e,a){},"8c1a":function(t,e,a){},bb1a:function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("Card",{attrs:{"dis-hover":""}},[a("table-header-new",{attrs:{title:"下注统计"}},[a("DatePicker",{staticClass:"date-picker",attrs:{options:t.$store.state.date_picker,format:"yyyy-MM-dd",placeholder:"请选择日期",placement:"bottom-end",type:"daterange"},on:{"on-change":t.getData}}),a("Button",{staticClass:"refresh",on:{click:function(e){return t.getData(t.date)}}},[t._v("刷新")]),a("div",{staticClass:"header-intro"},[t._v("默认查询为当天数据，本页面仅统计有数据用户，未产生数据用户将不做展示。")])],1),a("table-data",{ref:"table"})],1)},n=[],s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative"},[a("lett-loading",{attrs:{visible:t.loading},on:{click:function(e){return t.getData(t.$parent.$parent.date)}}}),a("Table",{staticClass:"mb-10",attrs:{columns:t.columns,data:t.items,height:t._f("table_height")(300),"summary-method":t.handleSummary,border:"","no-data-text":"暂无相关数据 请尝试更换搜索条件","show-summary":""},scopedSlots:t._u([{key:"user",fn:function(e){var r=e.row;return r?a("table-user",{attrs:{data:r}}):t._e()}},{key:"wallet_total",fn:function(e){var r=e.row;return a("span",{class:t.amountClass(r.wallet_total)},[t._v(t._s(t._f("currency")(r.wallet_total)))])}},{key:"transfer_in",fn:function(e){var r=e.row;return a("span",{class:t.amountClass(r.transfer_in)},[t._v(t._s(t._f("currency")(r.transfer_in)))])}},{key:"transfer_out",fn:function(e){var r=e.row;return a("span",{class:t.amountClass(r.transfer_out)},[t._v(t._s(t._f("currency")(r.transfer_out)))])}},{key:"bet_total",fn:function(e){var r=e.row;return a("span",{class:t.amountClass(r.bet_total)},[t._v(t._s(t._f("currency")(r.bet_total)))])}},{key:"bet_bonus",fn:function(e){var r=e.row;return a("span",{class:t.amountClass(r.bet_bonus)},[t._v(t._s(t._f("currency")(r.bet_bonus)))])}},{key:"bet_profit",fn:function(e){var r=e.row;return a("span",{class:t.profitClass(r.bet_profit)},[t._v(t._s(t._f("currency")(r.bet_profit)))])}}],null,!0)})],1)},o=[],l=(a("a623"),a("4160"),a("d81d"),a("13d5"),a("a9e3"),a("acd8"),a("159b"),{data:function(){return{loading:!1,items:[],columns:[{title:"用户",slot:"user",align:"left"},{title:"资金总额",key:"wallet_total",slot:"wallet_total",align:"right",sortable:!0},{title:"充值总额",key:"transfer_in",slot:"transfer_in",align:"right",sortable:!0},{title:"提现总额",key:"transfer_out",slot:"transfer_out",align:"right",sortable:!0},{title:"下注总额",key:"bet_total",slot:"bet_total",align:"right",sortable:!0},{title:"返奖总额",key:"bet_bonus",slot:"bet_bonus",align:"right",sortable:!0},{title:"投注盈亏",key:"bet_profit",slot:"bet_profit",align:"right",sortable:!0}]}},created:function(){this.getData()},methods:{getData:function(t){var e=this;this.loading=!0;var a="/lotto/bet-log/user-bet-stats";this.$get(a,t).then((function(t){if(200!==t.code)return!1;e.loading=!1,e.items=t.data.items}))},amountClass:function(t){if(0==parseFloat(t))return"color-desc"},profitClass:function(t){if(parseFloat(t)>0)return"color-red"},handleSummary:function(t){var e=this,a=t.columns,r=t.data,n={};return a.forEach((function(t,a){var s=t.key;if(0!==a){var o=r.map((function(t){return Number(t[s])}));if(o.every((function(t){return isNaN(t)})))n[s]={key:s,value:"0.000"};else{var l=o.reduce((function(t,e){var a=Number(e);return isNaN(a)?t:t+e}),0);n[s]={key:s,value:e.$options.filters.currency(l)}}}else{var i="合计"+e.items.length+"人";n[s]={key:s,value:i}}})),n}}}),i=l,u=(a("c3cc"),a("2877")),c=Object(u["a"])(i,s,o,!1,null,"4988ab20",null),f=c.exports,d={name:"userBetStats",components:{tableData:f},data:function(){return{date:null}},methods:{getData:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;this.date=t;var e=[];return t&&(e={date_start:t[0],date_end:t[1]}),this.$refs.table.getData(e)}}},_=d,b=(a("d6d6"),Object(u["a"])(_,r,n,!1,null,"04fac142",null));e["default"]=b.exports},c3cc:function(t,e,a){"use strict";var r=a("8c1a"),n=a.n(r);n.a},d58f:function(t,e,a){var r=a("1c0b"),n=a("7b0b"),s=a("44ad"),o=a("50c4"),l=function(t){return function(e,a,l,i){r(a);var u=n(e),c=s(u),f=o(u.length),d=t?f-1:0,_=t?-1:1;if(l<2)while(1){if(d in c){i=c[d],d+=_;break}if(d+=_,t?d<0:f<=d)throw TypeError("Reduce of empty array with no initial value")}for(;t?d>=0:f>d;d+=_)d in c&&(i=a(i,c[d],d,u));return i}};t.exports={left:l(!1),right:l(!0)}},d6d6:function(t,e,a){"use strict";var r=a("3489"),n=a.n(r);n.a}}]);