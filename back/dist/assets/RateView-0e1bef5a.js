import{_ as b,d as k,g as v,h as u,r as c,i as R,o as r,c as n,a as s,e as d,b as o,j as w,t as l,w as D,v as V,n as h,F as m,k as g,f as P}from"./index-5758cdee.js";import{o as C}from"./vue-datepicker-be1eeb2c.js";import{d as f}from"./main-f1c8270c.js";const A=k({name:"RateView",components:{VueDatePicker:C},data(){return{month:{month:new Date().getMonth()+1,year:new Date().getFullYear()},rates:{data:null,links:null},errors:{price:null,period:null},price:null,addingRate:!1}},computed:{minPeriod(){const e=new Date(2022,1,1),t=new Date(new Date().getFullYear(),new Date().getMonth(),1);return f(t,e)+2},period(){const e=new Date(2022,1,1),t=new Date(this.month.year,this.month.month,1);return f(t,e)+2},isValidPeriod(){return this.period>this.minPeriod}},mounted(){this.getRates()},methods:{...v(P,{storeError:"storeError"}),changeAddingRate(){this.addingRate=!this.addingRate},async getRates(e=1){try{const t=await u.get(`/api/admin/rates?page=${e}`);t.status===200&&t.data&&(this.rates.data=t.data.data,this.rates.links=t.data.meta.links,this.rates.links.forEach(i=>{if(i.url){const p=new URL(i.url);i.page=p.searchParams.get("page")}else i.page=null}))}catch(t){this.storeError(t),c.push({name:"error"})}},async addRate(){try{const e=await u.post("/api/admin/rates",{price:this.price,period:this.period});(e.status===200||e.status===201)&&(await this.getRates(),this.errors.period=null,this.errors.price=null,this.changeAddingRate())}catch(e){e.response&&e.response.status===422?this.errors=e.response.data.errors:(this.storeError(e),c.push({name:"error"}))}}}}),E={class:"row"},M={class:"row d-flex justify-content-between"},_=s("h2",{class:"col-2"},"Список",-1),$={key:0},F=s("div",{class:"form-label mt-2"},"Период",-1),L={key:0,class:"text-danger mt-2"},j={key:1,class:"text-danger mt-2"},B=s("div",{class:"form-label mt-2"},"Цена",-1),N={key:2,class:"text-danger mt-2"},T={key:1},U={class:"table"},H=s("thead",null,[s("tr",null,[s("th",{scope:"col"},"Год"),s("th",{scope:"col"},"Месяц"),s("th",{scope:"col"},"Тариф")])],-1),S={"aria-label":"Page navigation example"},Y={class:"pagination justify-content-center"},z=["onClick","innerHTML"];function I(e,t,i,p,X,q){const y=R("VueDatePicker");return r(),n("div",E,[s("div",M,[_,e.addingRate?o("",!0):(r(),n("button",{key:0,onClick:t[0]||(t[0]=d((...a)=>e.changeAddingRate&&e.changeAddingRate(...a),["prevent"])),class:"btn btn-primary col-2"},"+")),e.addingRate?(r(),n("button",{key:1,onClick:t[1]||(t[1]=d((...a)=>e.changeAddingRate&&e.changeAddingRate(...a),["prevent"])),class:"btn btn-primary col-2"},"X")):o("",!0)]),e.addingRate?(r(),n("div",$,[F,w(y,{clas:"mt-2",modelValue:e.month,"onUpdate:modelValue":t[2]||(t[2]=a=>e.month=a),"month-picker":"","auto-apply":""},null,8,["modelValue"]),e.errors.period?(r(),n("div",L,l(e.errors.period[0]),1)):o("",!0),e.isValidPeriod?o("",!0):(r(),n("div",j,"Разрешено изменть цену на тариф только для будущих периодов")),B,D(s("input",{type:"number","onUpdate:modelValue":t[3]||(t[3]=a=>e.price=a),class:"form-control mt-2",step:"0.01",placeholder:"Цена"},null,512),[[V,e.price]]),e.errors.price?(r(),n("div",N,l(e.errors.price[0]),1)):o("",!0),s("input",{type:"submit",onClick:t[4]||(t[4]=d((...a)=>e.addRate&&e.addRate(...a),["prevent"])),class:h("form-control btn btn-primary mt-2 "+(e.isValidPeriod?"":"disabled")),value:"Изменить тариф"},null,2)])):o("",!0),e.rates.data&&e.rates.data.length>0?(r(),n("div",T,[s("table",U,[H,s("tbody",null,[(r(!0),n(m,null,g(e.rates.data,a=>(r(),n("tr",{key:a.id},[s("th",null,l(a.year),1),s("td",null,l(a.month),1),s("td",null,l(a.price),1)]))),128))])]),s("nav",S,[s("ul",Y,[(r(!0),n(m,null,g(e.rates.links,a=>(r(),n("li",{key:a.label,class:h("page-item "+(a.active?"active ":"")+(a.url?"":"disabled"))},[s("a",{onClick:d(G=>e.getRates(a.page),["prevent"]),class:"page-link",href:"#",innerHTML:a.label},null,8,z)],2))),128))])])])):o("",!0)])}const Q=b(A,[["render",I]]);export{Q as default};