import{_ as d,d as m,m as p,o as t,c as a,a as s,w as l,v as n,t as i,b as u,e as S,u as c}from"./index-5758cdee.js";const f=m({name:"RegisterView",computed:{...p(c)},unmounted(){this.userStore.resetErrors()}}),b={class:"row"},y={class:"mb-3"},v=s("label",{class:"form-label"},"Ваш email",-1),w={key:0,class:"text-danger"},h={class:"mb-3"},g=s("label",{class:"form-label"},"Ваше имя",-1),_={key:0,class:"text-danger"},V={class:"mb-3"},k=s("label",{class:"form-label"},"Ваша фамилия",-1),U={key:0,class:"text-danger"},$={class:"mb-3"},B=s("label",{class:"form-label"},"Ваше отчетсво",-1),C={key:0,class:"text-danger"},D={class:"mb-3"},E=s("label",{class:"form-label"},"Пароль",-1),M={key:0,class:"text-danger"},N={class:"mb-3"},R=s("label",{class:"form-label"},"Повторите пароль",-1),T={key:0,class:"text-danger"};function j(e,r,q,z,A,F){return t(),a("div",b,[s("div",y,[v,l(s("input",{"onUpdate:modelValue":r[0]||(r[0]=o=>e.userStore.user.email=o),type:"email",class:"form-control",placeholder:"user@maail.ru"},null,512),[[n,e.userStore.user.email]]),e.userStore.errors.email?(t(),a("div",w,i(e.userStore.errors.email[0]),1)):u("",!0)]),s("div",h,[g,l(s("input",{"onUpdate:modelValue":r[1]||(r[1]=o=>e.userStore.user.name=o),type:"text",class:"form-control",placeholder:"Иванов"},null,512),[[n,e.userStore.user.name]]),e.userStore.errors.name?(t(),a("div",_,i(e.userStore.errors.name[0]),1)):u("",!0)]),s("div",V,[k,l(s("input",{"onUpdate:modelValue":r[2]||(r[2]=o=>e.userStore.user.surname=o),type:"text",class:"form-control",placeholder:"Иван"},null,512),[[n,e.userStore.user.surname]]),e.userStore.errors.surname?(t(),a("div",U,i(e.userStore.errors.surname[0]),1)):u("",!0)]),s("div",$,[B,l(s("input",{"onUpdate:modelValue":r[3]||(r[3]=o=>e.userStore.user.patronymic=o),type:"text",class:"form-control",placeholder:"Иванович"},null,512),[[n,e.userStore.user.patronymic]]),e.userStore.errors.patronymic?(t(),a("div",C,i(e.userStore.errors.patronymic[0]),1)):u("",!0)]),s("div",D,[E,l(s("input",{"onUpdate:modelValue":r[4]||(r[4]=o=>e.userStore.user.password=o),type:"password",class:"form-control",placeholder:"password"},null,512),[[n,e.userStore.user.password]]),e.userStore.errors.password?(t(),a("div",M,i(e.userStore.errors.password[0]),1)):u("",!0)]),s("div",N,[R,l(s("input",{"onUpdate:modelValue":r[5]||(r[5]=o=>e.userStore.user.password_confirmation=o),type:"password",class:"form-control",placeholder:"password"},null,512),[[n,e.userStore.user.password_confirmation]]),e.userStore.errors.password_confirmation?(t(),a("div",T,i(e.userStore.errors.password_confirmation[0]),1)):u("",!0)]),s("input",{type:"submit",onClick:r[6]||(r[6]=S((...o)=>e.userStore.register&&e.userStore.register(...o),["prevent"])),class:"btn btn-primary",value:"Регистрация"})])}const H=d(f,[["render",j]]);export{H as default};
