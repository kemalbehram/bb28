(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0a4a8093"],{"150d":function(t,a,e){"use strict";var s=e("25fd"),i=e.n(s);i.a},"15fa":function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"relative"},[e("Card",{staticClass:"mb-20",attrs:{"dis-hover":""}},[e("table-header-new",{attrs:{title:"平台总数据"}},[e("div",{staticClass:"header-action"},[e("Button",{staticClass:"ml-10",on:{click:function(a){return t.$refs.total.getData()}}},[t._v("刷新")])],1),e("div",{staticClass:"header-intro"},[t._v("该组数据为平台开站以来的总数据，不可根据时间戳查询指定时间的数据。")])]),e("total",{ref:"total"})],1),e("Card",{attrs:{"dis-hover":""}},[e("table-header-new",{attrs:{title:"综合数据统计"}},[e("div",{staticClass:"header-action"},[e("Checkbox",{staticClass:"checkbox",attrs:{border:""},model:{value:t.is_all,callback:function(a){t.is_all=a},expression:"is_all"}},[t._v("详细版")]),e("DatePicker",{staticClass:"date-picker",attrs:{options:t.$store.state.date_picker,format:"yyyy-MM-dd",placeholder:"请选择日期",placement:"bottom-end",type:"daterange"},on:{"on-change":t.getDateData}}),e("Button",{staticClass:"ml-10",on:{click:function(a){return t.$refs.date.getData(t.date,t.user_id)}}},[t._v("刷新")])],1),e("div",{staticClass:"header-intro"},[t._v("为保证正常运行，请尽量避免频繁刷新。默认查询为今天的实时数据。")])]),e("keep-alive",[t.is_all?e("date-all",{ref:"date"}):e("date-simple",{ref:"date"})],1)],1)],1)},i=[],n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"relative"},[t.loading?e("div",{staticClass:"loading-container mb-20"},[e("lett-loading",{staticClass:"border-all",attrs:{visible:t.loading},on:{click:function(a){return t.getData()}}})],1):t._l(t.$store.state.config.stats_total,(function(a,s){return e("stats-items",{key:s,staticClass:"mb-20",attrs:{fields:a,stats:t.stats}})}))],2)},l=[],c=e("c7fd"),r={components:{statsItems:c["a"]},data:function(){return{loading:!1,stats:{}}},created:function(){this.getData()},methods:{getData:function(){var t=this;this.loading=!0,this.$get("/data-stats/total").then((function(a){if(200!==a.code)return t.loading=a.message;t.stats=a.data}))}}},o=r,d=(e("48a3"),e("2877")),u=Object(d["a"])(o,n,l,!1,null,"5881ffcc",null),f=u.exports,h=e("e4f1"),g=e("3d2b"),v={name:"dataStatsHome",components:{total:f,dateAll:h["a"],dateSimple:g["a"]},data:function(){return{user_id:null,is_all:!1,is_change:!1,date:[]}},watch:{is_all:function(t){!0===this.is_change&&this.$nextTick((function(){this.getDateData(this.date),this.is_change=!1}))},date:function(t){this.is_change=!0}},methods:{getDateData:function(t){return this.date=t,this.$refs.date.getData(this.date,this.user_id)}}},_=v,m=(e("150d"),Object(d["a"])(_,s,i,!1,null,"332bc064",null));a["default"]=m.exports},"25fd":function(t,a,e){},4555:function(t,a,e){},"48a3":function(t,a,e){"use strict";var s=e("4555"),i=e.n(s);i.a}}]);