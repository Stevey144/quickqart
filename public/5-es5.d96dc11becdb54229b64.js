function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _defineProperties(e,t){for(var o=0;o<t.length;o++){var i=t[o];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function _createClass(e,t,o){return t&&_defineProperties(e.prototype,t),o&&_defineProperties(e,o),e}(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{"99Un":function(e,t,o){"use strict";o.r(t),o.d(t,"HomeModule",(function(){return P}));var i=o("2kYt"),n=o("s2Ay"),r=o("HHT8"),c=o("PCNd"),a=o("C05f"),s=o("EM62"),d=o("k944"),b=o("9vQT"),p=o("sEIs"),u=o("paPW"),l=o("p5Qb");function g(e,t){1&e&&s.Nb(0,"app-block-space",4)}function f(e,t){if(1&e&&(s.Sb(0,"div"),s.Sb(1,"a",18),s.Hc(2),s.Rb(),s.Rb()),2&e){var o=t.$implicit,i=t.index,n=s.dc(2);s.zb(1),s.Db("menu-active",i.toString()===n.activeGroup.toString()?"menu-active":""),s.kc("href","#"+i,s.Ac),s.zb(1),s.Ic(o.name)}}function v(e,t){if(1&e&&(s.Sb(0,"div",19),s.Sb(1,"div",20),s.Sb(2,"h5",21),s.Hc(3),s.Rb(),s.Rb(),s.Nb(4,"app-ordinary-products-view",22),s.Rb()),2&e){var o=t.$implicit;s.kc("id",t.index),s.zb(3),s.Ic(o.name),s.zb(1),s.kc("layout","grid")("gridLayout","grid-4-sidebar")("offCanvasSidebar","always")("products",o.products)}}var m=function(){return["DIV"]};function h(e,t){if(1&e){var o=s.Tb();s.Sb(0,"div",5),s.Sb(1,"div",6),s.Sb(2,"div",7),s.Sb(3,"div",8),s.Fc(4,f,3,4,"div",9),s.Sb(5,"div",10),s.Sb(6,"a",11),s.Hc(7,"Browse all categories"),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Sb(8,"div",12),s.Sb(9,"div",13),s.Sb(10,"div",14),s.Sb(11,"div",15),s.Sb(12,"div",16),s.ac("sectionChange",(function(e){return s.yc(o),s.dc().onSectionChange(e)})),s.Fc(13,v,5,6,"div",17),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb()}if(2&e){var i=s.dc();s.zb(4),s.kc("ngForOf",i.groups),s.zb(2),s.kc("routerLink","/shop"),s.zb(6),s.kc("spiedTags",s.oc(4,m)),s.zb(1),s.kc("ngForOf",i.groups)}}function k(e,t){if(1&e){var o=s.Tb();s.Sb(0,"div",30),s.ac("click",(function(){s.yc(o);var e=t.index;return s.dc(2).activeGroup=e})),s.Hc(1),s.Rb()}if(2&e){var i=t.$implicit,n=t.index,r=s.dc(2);s.Db("active",r.activeGroup===n),s.zb(1),s.Ic(i.name)}}var y=function(){return{name:""}},w=function(){return[]},S=function(e){return{products:e}};function x(e,t){if(1&e&&(s.Sb(0,"div",23),s.Sb(1,"div",24),s.Fc(2,k,2,3,"div",25),s.Rb(),s.Sb(3,"div",26),s.Sb(4,"div",12),s.Sb(5,"div",13),s.Sb(6,"div",27),s.Sb(7,"div",15),s.Sb(8,"div",28),s.Sb(9,"div",29),s.Sb(10,"div",20),s.Sb(11,"h5",21),s.Hc(12),s.Rb(),s.Rb(),s.Nb(13,"app-ordinary-products-view",22),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb()),2&e){var o=s.dc();s.zb(2),s.kc("ngForOf",o.groups),s.zb(10),s.Jc(" ",(o.groups[o.activeGroup]||s.oc(6,y)).name," "),s.zb(1),s.kc("layout","grid")("gridLayout","grid-4-sidebar")("offCanvasSidebar","always")("products",(o.groups[o.activeGroup]||s.pc(8,S,s.oc(7,w))).products)}}var C,R,_=[{path:"",component:(C=function(){function e(t){_classCallCheck(this,e),this.storeApiProvider=t,this.isMobileSubject=new a.a(window.innerWidth<=768),this.isMobile$=this.isMobileSubject.asObservable()}return _createClass(e,[{key:"ngAfterViewInit",value:function(){}},{key:"ngOnInit",value:function(){this.groups.length||this.fetchData()}},{key:"fetchData",value:function(){this.storeApiProvider.getHomePageData().then((function(e){}))}},{key:"onSectionChange",value:function(e){this.activeGroup=e,console.log(e)}},{key:"scrollTo",value:function(e){document.querySelector("#"+e).scrollIntoView()}},{key:"onResize",value:function(e){this.updateViewType()}},{key:"updateViewType",value:function(){this.isMobileSubject.next(window.innerWidth<=768)}},{key:"groups",get:function(){return this.storeApiProvider.groups},set:function(e){this.storeApiProvider.groups=e}},{key:"activeGroup",get:function(){return this.storeApiProvider.activeGroup},set:function(e){this.storeApiProvider.activeGroup=e}},{key:"loading",get:function(){return this.storeApiProvider.loading}},{key:"hasError",get:function(){return this.storeApiProvider.errorLoading}}]),e}(),C.\u0275fac=function(e){return new(e||C)(s.Mb(d.a))},C.\u0275cmp=s.Gb({type:C,selectors:[["app-home"]],hostBindings:function(e,t){1&e&&s.ac("resize",(function(e){return t.onResize(e)}),!1,s.xc)},decls:6,vars:7,consts:[["layout","divider-xs",4,"ngIf"],[1,"no-padding-mobile","container","mb-md-5"],["class","d-none d-md-block has-sticky-bar",4,"ngIf"],["class","d-block d-md-none",4,"ngIf"],["layout","divider-xs"],[1,"d-none","d-md-block","has-sticky-bar"],[1,"wrapper"],[1,"sidebar"],[1,"card",2,"min-height","200px"],[4,"ngFor","ngForOf"],[1,"category-item","category-item-fixed-bottom"],[2,"color","white",3,"routerLink"],[1,"mainbar"],[1,"block-split"],[1,"container"],[1,"block-split__row","row","no-gutters"],["appScrollSpy","",1,"block-split__item","block-split__item-content","col-12",3,"spiedTags","sectionChange"],["class","block mb-2 bg-white",3,"id",4,"ngFor","ngForOf"],[1,"category-item","menu-list-item",3,"href"],[1,"block","mb-2","bg-white",3,"id"],[2,"background","#2B2B2B"],[1,"px-3","py-2",2,"color","white"],[3,"layout","gridLayout","offCanvasSidebar","products"],[1,"d-block","d-md-none"],[1,"category-tab"],[3,"active","click",4,"ngFor","ngForOf"],[1,"wrapper","bg-white"],[1,"container","no-padding-mobile2"],[1,"block-split__item","block-split__item-content","col-12"],[1,"block","mb-2"],[3,"click"]],template:function(e,t){1&e&&(s.Fc(0,g,1,0,"app-block-space",0),s.Sb(1,"div",1),s.Fc(2,h,14,5,"div",2),s.ec(3,"async"),s.Fc(4,x,14,10,"div",3),s.ec(5,"async"),s.Rb()),2&e&&(s.kc("ngIf",!t.loading&&!t.hasError),s.zb(2),s.kc("ngIf",!t.loading&&!t.hasError&&!s.fc(3,3,t.isMobile$)),s.zb(2),s.kc("ngIf",s.fc(5,5,!t.loading&&!t.hasError&&t.isMobile$)))},directives:[i.t,b.a,i.s,p.j,u.a,l.a],pipes:[i.b],styles:[".category-item[_ngcontent-%COMP%]{padding:7px 25px;background:#fff;width:100%;display:block!important;border-left:5px solid transparent;border-bottom:1px solid #ccc;color:#010101}.category-item[_ngcontent-%COMP%]:not(.category-item-header):not(.menu-active):hover{background:#ededed;cursor:pointer;color:#0449b3}.category-item.active[_ngcontent-%COMP%], .category-item.menu-active[_ngcontent-%COMP%]{cursor:pointer;color:#042c59;border-left:5px solid #042c59}.category-item.category-item-header[_ngcontent-%COMP%]{font-weight:bolder!important;margin-top:15px;text-transform:uppercase}.category-item.category-item-fixed-bottom[_ngcontent-%COMP%]{position:absolute;bottom:0;display:block;left:0;width:100%;background:#042c59;cursor:pointer;color:#c1d8f3;text-align:center;transition:all .2s ease-in-out}.category-item.category-item-fixed-bottom[_ngcontent-%COMP%]:hover{background:#ccc;cursor:pointer;color:#010101}.category-tab[_ngcontent-%COMP%]{overflow-x:auto;max-width:100%;width:100%;height:50px;background:#fff;white-space:nowrap!important;position:fixed;top:47px;z-index:100;left:0;border-top:1px solid #ccc;border-bottom:1px solid #ccc}.category-tab[_ngcontent-%COMP%] > *[_ngcontent-%COMP%]{display:inline-block!important;padding:15px!important;height:50px;font-weight:700;-moz-user-select:none;user-select:none;-webkit-user-select:none;-ms-user-select:none;cursor:pointer;border-bottom:5px solid transparent}.category-tab[_ngcontent-%COMP%] > .active[_ngcontent-%COMP%]{cursor:pointer;color:#042c59;border-bottom:5px solid #042c59}"]}),C),resolve:[d.a]}],P=((R=function e(){_classCallCheck(this,e)}).\u0275mod=s.Kb({type:R}),R.\u0275inj=s.Jb({factory:function(e){return new(e||R)},imports:[[i.c,n.d.forChild(),r.a,p.k.forChild(_),c.a]]}),R)}}]);