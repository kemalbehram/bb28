(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-fbea64ae"],{a349:function(t,e,n){},da96:function(t,e,n){"use strict";var o=n("a349"),a=n.n(o);a.a},fd76:function(t,e,n){"use strict";n.r(e);var o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("Card",{attrs:{"dis-hover":""}},[n("table-header-new",{attrs:{config:t.config,title:"开奖数据"},on:{"update:config":function(e){t.config=e},"on-search":t.onSearch}},[n("Select",{attrs:{size:"small"},on:{"on-change":t.onChangeLotto},model:{value:t.lotto,callback:function(e){t.lotto=e},expression:"lotto"}},t._l(this.$store.state.config.lotto_items,(function(e){return n("Option",{key:e.name,attrs:{value:e.name}},[t._v(t._s(e.title))])})),1),t.lotto?n("Select",{staticClass:"ml-6",attrs:{size:"small"},on:{"on-change":t.onSearch},model:{value:t.config.value.status,callback:function(e){t.$set(t.config.value,"status",e)},expression:"config.value.status"}},[n("Option",{attrs:{value:"0"}},[t._v("所有状态")]),n("Option",{attrs:{value:"2"}},[t._v("已开奖")]),n("Option",{attrs:{value:"1"}},[t._v("待开奖")]),n("Option",{attrs:{value:"3"}},[t._v("异常数据")])],1):t._e(),n("Button",{staticClass:"ml-10",attrs:{size:"small",type:"primary"},on:{click:t.onReset}},[t._v("初始")]),n("Button",{staticClass:"ml-6",attrs:{size:"small",type:"primary"},on:{click:t.onSearch}},[n("Checkbox",{attrs:{size:"small"},on:{"on-change":t.autoLoad}}),n("span",{staticClass:"font-13"},[t._v("刷新")])],1)],1),n("table-data",{ref:"table",attrs:{lotto:t.lotto},on:{"on-search":t.onSearch}})],1)},a=[],i=(n("4795"),function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"relative"},[n("lett-loading",{attrs:{visible:t.loading},on:{click:t.getIndex}}),n("Table",{attrs:{columns:t.table_columns,data:t.items,height:t._f("table_height")(300),border:""},scopedSlots:t._u([{key:"win_extend",fn:function(e){var o=e.row;return[o.win_extend?n("div",{staticClass:"table-win-extend"},[o.win_extend.code_str?n("span",[t._v(t._s(o.win_extend.code_str))]):t._e(),o.win_extend.code_he?n("span",[t._v(t._s(o.win_extend.code_he))]):t._e(),o.win_extend.code_bos?n("span",{staticClass:"font-12"},[t._v(t._s(o.win_extend.code_bos))]):t._e(),o.win_extend.code_sod?n("span",{staticClass:"font-12"},[t._v(t._s(o.win_extend.code_sod))]):t._e()]):-1!=t.$store.state.config.lotto_control.indexOf(t.lotto)?n("Select",{staticClass:"control-button",staticStyle:{width:"124px"},attrs:{placeholder:"指定和值",size:"small"},on:{"on-change":function(e){return t.onLottoControl(o)}},model:{value:o.control,callback:function(e){t.$set(o,"control",e)},expression:"row.control"}},[n("Option",{attrs:{value:"cancel"}},[t._v("未控制")]),n("Option",{attrs:{value:"bet"}},[t._v("投注分配")]),n("Option",{attrs:{value:"he_00"}},[t._v("和值-00")]),t._l(27,(function(e){return n("Option",{key:e,attrs:{value:"he_"+(Array(2).join("0")+e).slice(-2)}},[t._v("和值-"+t._s((Array(2).join("0")+e).slice(-2)))])}))],2):t._e()]}},{key:"win_ext_st",fn:function(e){var o=e.row;return o.win_ext_st?n("div",{staticClass:"table-win-extend"},[o.win_ext_st.code_str?n("span",[t._v(t._s(o.win_ext_st.code_str))]):t._e(),o.win_ext_st.code_he?n("span",[t._v(t._s(o.win_ext_st.code_he))]):t._e()]):t._e()}},{key:"lhb",fn:function(e){var o=e.row;return o.win_extend?n("div",{staticClass:"table-win-extend"},[o.win_extend.code_long?n("span",[t._v("龙")]):t._e(),o.win_extend.code_hu?n("span",[t._v("虎")]):t._e(),o.win_extend.code_bao?n("span",[t._v("豹")]):t._e()]):t._e()}},{key:"bds",fn:function(e){var o=e.row;return o.win_extend?n("div",{staticClass:"table-win-extend"},[o.win_extend.code_baozi?n("span",[t._v("豹子")]):t._e(),o.win_extend.code_dui?n("span",[t._v("对子")]):t._e(),o.win_extend.code_shun?n("span",[t._v("顺子")]):t._e()]):t._e()}},{key:"win_ext_el",fn:function(e){var o=e.row;return o.win_ext_el?n("div",{staticClass:"table-win-extend"},[o.win_ext_el.code_str?n("span",[t._v(t._s(o.win_ext_el.code_str))]):t._e(),o.win_ext_el.code_he?n("span",[t._v(t._s(o.win_ext_el.code_he))]):t._e()]):t._e()}},{key:"win_ext_gyh",fn:function(e){var o=e.row;return o.win_extend?n("div",{staticClass:"table-win-extend"},[o.win_extend.code_gyh?[n("span",[t._v(t._s(o.win_extend.code_gyh.gyh))]),n("span",[t._v(t._s(o.win_extend.code_gyh.bos))]),n("span",[t._v(t._s(o.win_extend.code_gyh.sod))])]:t._e()],2):t._e()}},{key:"bet_stats",fn:function(e){var o=e.row;return n("div",{staticClass:"bet-stats-wrap"},[o.bet_stats?[n("span",{staticClass:"bet-stats-amount",class:t.colorItem(o.bet_stats.bet_people),domProps:{innerHTML:t._s(o.bet_stats.bet_total)}}),n("span",{staticClass:"bet-stats-people",class:t.colorItem(o.bet_stats.bet_people),domProps:{innerHTML:t._s(o.bet_stats.bet_people)}})]:t._e()],2)}},{key:"win_stats",fn:function(e){var o=e.row;return n("div",{staticClass:"bet-stats-wrap"},[o.bet_stats?[n("span",{staticClass:"bet-stats-amount",class:t.colorItem(o.bet_stats.win_people),domProps:{innerHTML:t._s(o.bet_stats.win_total)}}),n("span",{staticClass:"bet-stats-people",class:t.colorItem(o.bet_stats.win_people),domProps:{innerHTML:t._s(o.bet_stats.win_people)}})]:t._e()],2)}},{key:"action",fn:function(e){var o=e.row;return[n("Button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.$store.dispatch("onLottoGet",[o.id,t.lotto])}}},[t._v("详情")])]}}],null,!0)}),n("lett-page",{attrs:{page:t.page},on:{"on-change":t.onPage}})],1)}),s=[],l=n("adf6"),r={mixins:[l["a"]],props:{lotto:null},computed:{table_columns:function(){var t=[{title:"期号",width:100,key:"short_id",align:"center"},{title:"开奖时间",key:"lotto_at",align:"center",width:140},{title:"28开奖",slot:"win_extend",align:"center",width:240},{title:"龙/虎/豹",slot:"lhb",align:"center",width:100},{title:"豹子/对子/顺子",slot:"bds",align:"center",width:180},{title:"源号码",key:"open_code",align:"center",tooltip:!0},{title:"采集时间",key:"opened_at",align:"center",width:140},{title:"操作",slot:"action",width:140,align:"center"}],e=[{title:"期号",width:140,key:"short_id",align:"center"},{title:"开奖时间",key:"lotto_at",align:"center",width:200},{title:"28开奖",slot:"win_extend",align:"center",width:300},{title:"源号码",key:"open_code",align:"center",tooltip:!0},{title:"采集时间",key:"opened_at",align:"center",width:200},{title:"操作",slot:"action",width:140,align:"center"}],n=[{title:"期号",width:140,key:"short_id",align:"center"},{title:"开奖时间",key:"lotto_at",align:"center",width:200},{title:"冠亚和",slot:"win_ext_gyh",align:"center",width:300},{title:"源号码",key:"open_code",align:"center",tooltip:!0},{title:"采集时间",key:"opened_at",align:"center",width:200},{title:"操作",slot:"action",width:140,align:"center"}],o=[{title:"期号",width:140,key:"short_id",align:"center"},{title:"开奖时间",key:"lotto_at",align:"center",width:200},{title:"开奖",slot:"win_extend",align:"center",width:300},{title:"源号码",key:"open_code",align:"center",tooltip:!0},{title:"采集时间",key:"opened_at",align:"center",width:200},{title:"操作",slot:"action",width:140,align:"center"}],a={ca28:t,cw28:t,de28:t,bj28:t,pc28:e,bit28:e,pk10:n};return a.hasOwnProperty(this.lotto)?a[this.lotto]:o}},methods:{getIndex:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1?arguments[1]:void 0;if(!this.lotto)return!1;var n="/lotto/"+this.lotto+"/data/index";return this.$dataware.index(n,this,t,e)},updateData:function(t,e){var n=arguments.length>2&&void 0!==arguments[2]&&arguments[2],o=this.dataware.update.fields;return this.$dataware.update(this,t,e,n,o)},onLottoGet:function(t){this.$store.dispatch("onLottoGet",[t.id,this.lotto])},colorItem:function(t){if("0"!=t)return"color-green"},onLottoControl:function(t){if(t.lotto_name!==this.lotto)return!1;if(!t.control)return!1;var e={control:t.control,id:t.id},n="/lotto/"+this.lotto+"/data/control";this.$post(n,e)}}},c=r,_=(n("da96"),n("2877")),d=Object(_["a"])(c,i,s,!1,null,"156ec2b2",null),u=d.exports,p=n("c86f"),w={mixins:[p["a"]],name:"lottoData",components:{tableData:u},data:function(){return{config:{value:{status:"0"},fields:[{value:"id",label:"期号",default:!0}]},lotto:"ca28",loading:!1,setting:{api:"",visible:!1},query:{status:"0",id:null}}},methods:{onChangeLotto:function(t){var e=this;this.lotto=t,setTimeout((function(){e.onSearch()}),100)}}},h=w,v=Object(_["a"])(h,o,a,!1,null,null,null);e["default"]=v.exports}}]);