(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{wq26:function(a,t,i){"use strict";i.r(t),i.d(t,"ShopModule",(function(){return Ma}));var e=i("2kYt"),o=i("nIj0"),r=i("s2Ay"),s=i("ZTXN"),n=i("ROBh"),c=i("YtkY"),l=i("TLy2");function b(a){return a?[...b(a.parent),a]:[]}var d=i("EM62"),u=i("sEIs"),p=i("P2kn"),g=i("k944"),h=i("jOSP"),f=i("FN8K");function y(a,t){if(1&a&&d.Nb(0,"app-shop-sidebar",10),2&a){const a=d.dc();d.kc("offcanvas",a.offCanvasSidebar)}}function v(a,t){1&a&&d.Ob(0)}function k(a,t){if(1&a&&(d.Qb(0),d.Fc(1,v,1,0,"ng-container",11),d.Pb()),2&a){d.dc();const a=d.vc(3);d.zb(1),d.kc("ngTemplateOutlet",a)}}function m(a,t){1&a&&d.Ob(0)}function S(a,t){if(1&a&&(d.Qb(0),d.Sb(1,"div",12),d.Fc(2,m,1,0,"ng-container",11),d.Rb(),d.Pb()),2&a){d.dc();const a=d.vc(3);d.zb(2),d.kc("ngTemplateOutlet",a)}}function P(a,t){1&a&&d.Ob(0)}function O(a,t){if(1&a&&(d.Qb(0),d.Sb(1,"div",12),d.Fc(2,P,1,0,"ng-container",11),d.Rb(),d.Pb()),2&a){d.dc();const a=d.vc(3);d.zb(2),d.kc("ngTemplateOutlet",a)}}let w=(()=>{class a{constructor(a,t,i,e,o,r,n,c){this.router=a,this.route=t,this.page=i,this.shop=e,this.location=o,this.url=r,this.language=n,this.translate=c,this.destroy$=new s.a,this.layout="grid",this.gridLayout="grid-4-sidebar",this.sidebarPosition="start"}get offCanvasSidebar(){return["grid-4-full","grid-5-full","grid-6-full"].includes(this.gridLayout)?"always":"mobile"}get hasSidebar(){return this.sidebarPosition&&["grid-3-sidebar","grid-4-sidebar"].includes(this.gridLayout)}ngOnInit(){const a=this.route.data.pipe(Object(c.a)(a=>a.category));this.pageTitle$=a.pipe(Object(l.a)(a=>a?Object(n.a)(a.name):this.translate.stream("HEADER_SHOP"))),this.breadcrumbs$=this.language.current$.pipe(Object(l.a)(()=>a.pipe(Object(c.a)(a=>[{label:this.translate.instant("LINK_HOME"),url:this.url.home()},{label:this.translate.instant("LINK_SHOP"),url:this.url.shop()},...b(a).map(a=>({label:a.name,url:this.url.category(a)}))])))),this.route.data.subscribe(a=>{this.layout=a.layout,this.gridLayout=a.gridLayout,this.sidebarPosition=a.sidebarPosition})}ngOnDestroy(){this.destroy$.next(),this.destroy$.complete()}}return a.\u0275fac=function(t){return new(t||a)(d.Mb(u.g),d.Mb(u.a),d.Mb(p.a),d.Mb(g.a),d.Mb(e.n),d.Mb(h.a),d.Mb(f.a),d.Mb(r.f))},a.\u0275cmp=d.Gb({type:a,selectors:[["app-page-shop"]],decls:14,vars:12,consts:[[3,"pageTitle","breadcrumb"],["sidebar",""],[1,"block-split"],[4,"ngIf"],[1,"container"],[1,"block-split__row","row","no-gutters"],[1,"block-split__item","block-split__item-content","col-12"],[1,"block"],[3,"layout","gridLayout","offCanvasSidebar"],["layout","before-footer"],[3,"offcanvas"],[4,"ngTemplateOutlet"],[1,"block-split__item","block-split__item-sidebar","col-12"]],template:function(a,t){1&a&&(d.Nb(0,"app-block-header",0),d.ec(1,"async"),d.Fc(2,y,1,1,"ng-template",null,1,d.Gc),d.Sb(4,"div",2),d.Fc(5,k,2,1,"ng-container",3),d.Sb(6,"div",4),d.Sb(7,"div",5),d.Fc(8,S,3,1,"ng-container",3),d.Sb(9,"div",6),d.Sb(10,"div",7),d.Nb(11,"app-products-view",8),d.Rb(),d.Rb(),d.Fc(12,O,3,1,"ng-container",3),d.Rb(),d.Rb(),d.Rb(),d.Nb(13,"app-block-space",9)),2&a&&(d.kc("pageTitle","")("breadcrumb",d.fc(1,10,t.breadcrumbs$)),d.zb(4),d.Db("block-split--has-sidebar",t.hasSidebar),d.zb(1),d.kc("ngIf","always"===t.offCanvasSidebar),d.zb(3),d.kc("ngIf","start"===t.sidebarPosition&&t.hasSidebar),d.zb(3),d.kc("layout",t.layout)("gridLayout",t.gridLayout)("offCanvasSidebar",t.offCanvasSidebar),d.zb(1),d.kc("ngIf","end"===t.sidebarPosition&&t.hasSidebar))},styles:[""]}),a})();var M=i("PCNd"),z=i("bsXi"),L=i("gz/M"),T=i("8zzx"),j=i("AE56"),N=i("zd0V"),_=i("9vQT"),C=i("xxot"),F=i("0njA"),J=i("lKnz"),R=i("SFJx"),B=i("NhFE"),I=i("nS2t"),x=i("5Jae"),E=i("h4zV"),G=i("UBBk"),D=i("gf8J"),V=i("22k7"),$=i("VT9t"),A=i("CqBB"),Q=i("JBP5"),q=i("Ikue"),H=i("XR8f"),K=i("akOO"),X=i("6+Ds"),Y=i("p5Qb"),W=i("TXfE"),U=i("J+Gm"),Z=i("Bp9c"),aa=i("V07N"),ta=i("0nhT"),ia=i("jdus"),ea=i("BVsP"),oa=i("apXc"),ra=i("HW4V"),sa=i("tm5e"),na=i("JTYT"),ca=i("vr2D"),la=i("uPJy"),ba=i("cS6t"),da=i("hoDN"),ua=i("qepu"),pa=i("zQc6"),ga=i("lxS/"),ha=i("lts/"),fa=i("fsgG"),ya=i("7a6c"),va=i("1waV"),ka=i("MgwG"),ma=i("paPW"),Sa=i("cvNe"),Pa=i("wT+R"),Oa=i("JGxj");const wa=[{path:"",component:w,data:{layout:"grid",gridLayout:"grid-4-full",sidebarPosition:"start"},resolve:[z.a],pathMatch:"full"},{path:"category/:categorySlug",component:w,data:{layout:"grid",gridLayout:"grid-4-full",sidebarPosition:"start"},resolve:[z.a],pathMatch:"full"},{path:"products/:productSlug",component:w,data:{layout:"grid",gridLayout:"grid-4-full",sidebarPosition:"start"},resolve:[z.a],pathMatch:"full"},{path:"category/:categorySlug/products",component:w,data:{layout:"grid",gridLayout:"grid-4-full",sidebarPosition:"start"},resolve:[z.a],pathMatch:"full"},{path:"category/:categorySlug/products/:productSlug",component:w,data:{layout:"grid",gridLayout:"grid-4-full",sidebarPosition:"start"},resolve:[z.a],pathMatch:"full"}];let Ma=(()=>{class a{}return a.\u0275mod=d.Kb({type:a}),a.\u0275inj=d.Jb({factory:function(t){return new(t||a)},providers:[],imports:[[e.c,o.l,o.A,r.d.forChild(),M.a,u.k.forChild(wa)]]}),a})();d.Bc(w,[e.q,e.r,e.s,e.t,e.A,e.w,e.x,e.y,e.z,e.u,e.v,o.G,o.v,o.F,o.c,o.w,o.z,o.a,o.C,o.D,o.y,o.q,o.r,o.B,o.n,o.m,o.x,o.b,o.d,o.t,o.u,o.s,o.h,o.j,o.i,o.k,o.e,r.b,L.a,T.a,j.a,N.a,_.a,C.a,F.a,J.a,R.a,B.a,I.a,x.a,E.a,G.a,D.a,V.a,$.a,A.a,Q.a,q.a,H.a,K.a,X.a,Y.a,W.a,U.a,Z.a,aa.a,ta.a,ia.a,ea.a,oa.a,ra.a,sa.a,na.a,ca.a,la.a,ba.a,da.a,ua.a,pa.a,ga.a,ha.a,fa.a,ya.a,va.a,ka.a,ma.a,u.l,u.h,u.j,u.i,u.n,w],[e.b,e.G,e.p,e.k,e.E,e.g,e.C,e.F,e.d,e.f,e.i,e.j,e.l,r.e,Sa.a,Pa.a,Oa.a])}}]);