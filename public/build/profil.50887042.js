(self.webpackChunkintranetv3=self.webpackChunkintranetv3||[]).push([[4686],{5979:(t,e,n)=>{"use strict";n.d(e,{v_:()=>i,zD:()=>o});n(3710),n(1539),n(9714),n(6992),n(8783),n(3948),n(285),n(8674);var a=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"GET";console.log(e);var a={method:n};return"GET"===n?t+="?"+new URLSearchParams(e).toString():(a.body=JSON.stringify(e),a.headers={"Content-Type":"application/json;charset=utf-8"}),fetch(t,a).then((function(t){return t.json()}))},i=function(t,e){return a(t,e,"POST")},o=function(t,e,n){n.innerHTML="",fetch(Routing.generate(t,e)).then((function(t){return t.text()})).then((function(t){n.innerHTML=t}))}},3480:(t,e,n)=>{"use strict";var a=n(2396),i=n(5979),o=n(9755);o(document).on("change","#chgt_etudiant_departement",(function(){o.ajax({url:Routing.generate("user_change_departement",{etudiant:o(this).data("etudiant"),departement:o(this).val()}),method:"POST",success:function(t){o("#liste_groupes").empty().append('<tr><td colspan="3">Aucun groupe</td></tr>'),(0,a.qX)("Mofification enregistrée !","success")},error:function(t){(0,a.qX)("Erreur lors de la sauvegarde de la modification !","danger")}})})),o(document).on("change","#chgt_etudiant_fin",(function(){o.ajax({url:Routing.generate("user_change_annee_sortie",{etudiant:o(this).data("etudiant"),annee:o(this).val()}),method:"POST",success:function(t){(0,a.qX)("Mofification enregistrée !","success")},error:function(t){(0,a.qX)("Erreur lors de la sauvegarde de la modification !","danger")}})})),o(document).on("click",".changeprofil",(function(t){t.preventDefault(),t.stopPropagation();var e=o(this);o(".changeprofil").removeClass("active show"),o(this).addClass("active show"),o("#profilContent").empty().load(o(this).attr("href"),(function(){if("profil-about"===e.attr("id")){var t=document.querySelector("#valideCommentaire");t.addEventListener("click",(function(e){e.preventDefault(),e.stopPropagation(),(0,i.v_)(Routing.generate("profil_etudiant_ajout_commentaire",{slug:t.getAttribute("data-slug")}),{commentaire:document.querySelector("#texteCommentaire").value}).then((function(t){var e=document.createElement("p");e.textContent=t.commentaire,document.querySelector("#listeCommentaire").appendChild(e),(0,a.qX)("Commentaire ajouté avec succès.","success"),document.querySelector("#texteCommentaire").value=""})).catch((function(t){(0,a.qX)("Erreur lors de l'ajout du commentaire.","error")}))}))}e.attr("id")}))})),o(document).on("change",".addfavori",(function(t){t.preventDefault(),t.stopPropagation(),o.ajax({url:Routing.generate("user_add_favori"),method:"POST",data:{user:o(this).val(),etat:o(this).prop("checked")}})})),o(document).on("click","#btnabs",(function(t){o.ajax({url:Routing.generate("administration_absences_ajax_add"),data:{etudiant:o(this).data("etudiant"),date:o("#jourabs").val(),matiere:o("#moduleabs").val(),heure:o("#heureabs").val(),justif:o("input[type=radio][name=justifabs]:checked").val()},method:"POST",success:function(t){var e="",n="",a="";!0===t.justifie?(e="bg-pale-success",n="Oui",a="checked"):(e="bg-pale-warning",n="Non",a="");var i='<tr class="'+e+'" id="ligne_'+t.id+'">\n                    <td>'+t.date+" à "+t.heure+"</td>\n                    <td>"+t.codeMatiere+"</td>\n                    <td>"+n+'</td>\n                    <td class="hide">'+t.personnel+'</td>\n                    <td>\n                        <button data-provide="modaler tooltip"\n                                data-url="'+Routing.generate("app_etudiant_absence_detail",{uuid:t.uuidString})+'"\n                                class="btn btn-info btn-outline btn-square btn-xs"\n                                data-placement="bottom"\n                                title="Détails"\n                                data-title="Détails de l\'absence"\n                        >\n                            <i class="fas fa-info"></i>\n                        </button>\n                    </td>\n                        <td>\n                            <div class="custom-control custom-control-lg custom-checkbox">\n                                <input type="checkbox" class="custom-control-input checkAbsence" id="check_'+t.id+'"\n                                       data-absence="'+t.id+'" '+a+'>\n                                <label class="custom-control-label" for="check_'+t.id+'">Justifiée*</label>\n                            </div>\n                        </td>\n                </tr>';console.log(i),o("#tableAbsence > tbody:last").append(i)}})})),o(document).on("click",".checkAbsence",(function(t){var e=o(this).data("absence"),n=0;o(this).is(":checked")&&(n=1),o.ajax({url:Routing.generate("administration_absences_justifie",{absence:e,etat:n}),type:"GET",success:function(t){t?o("#ligne_"+e).removeClass("bg-pale-warning").addClass("bg-pale-success"):o("#ligne_"+e).removeClass("bg-pale-success").addClass("bg-pale-warning")}})})),o(document).on("click","#btnInit",(function(){console.log("init"),o.ajax({url:Routing.generate("security_password_init",{user:o(this).data("personnel")}),method:"POST",success:function(t){(0,a.qX)("Mot de passe initialisé !","success")},error:function(t){(0,a.qX)("Erreur lors l'initialisation du mot de passe !","danger")}})})),o(document).on("change","#chgt_etudiant_semestre",(function(){o.ajax({url:Routing.generate("user_change_semestre",{etudiant:o(this).data("etudiant"),semestre:o(this).val()}),method:"POST",success:function(t){o("#liste_groupes").empty().append('<tr><td colspan="3">Aucun groupe</td></tr>'),(0,a.qX)("Mofification enregistrée !","success")},error:function(t){(0,a.qX)("Erreur lors de la sauvegarde de la modification !","danger")}})}))},2396:(t,e,n)=>{"use strict";n.d(e,{qX:()=>y,FX:()=>S,xQ:()=>_,bV:()=>g,XQ:()=>x,zl:()=>j});n(4916),n(3123),n(3843),n(3710),n(3210),n(5306),n(9653);var a=n(6455),i=n.n(a),o=(n(9070),n(7941),n(2526),n(7327),n(5003),n(9554),n(4747),n(9337),n(3321),n(8588)),r=n.n(o);function s(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?s(Object(n),!0).forEach((function(e){l(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function u(t,e){for(var n=0;n<e.length;n++){var a=e[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}function l(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}const d=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),l(this,"defaultOptions",{close:!0,duration:3e3,className:"toast",escapeMarkup:!1,gravity:"top",position:"right",stopOnFocus:!0})}var e,n,a;return e=t,(n=[{key:"show",value:function(t,e){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{};(a=c(c({},this.defaultOptions),a)).className+=" toast-"+t;var i='<div class="toast-wrapper">';n&&(i+='<div class="toast-head">'+n+"</div>"),i+='<div class="toast-body">'+e+"</div>",i+="</div>",a.text=i,r()(a).showToast()}},{key:"error",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("error",t,e,n)}},{key:"warning",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("warning",t,e,n)}},{key:"success",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("success",t,e,n)}},{key:"info",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("info",t,e,n)}}])&&u(e.prototype,n),a&&u(e,a),t}());var p,f,m,h=n(9755),v=n(9755),b=!1;function g(t,e){for(e=e.toLowerCase();t&&t.nodeName.toLowerCase()!=e;)t=t.parentNode;return t||null}function y(t,e){switch(e){case"success":d.success(t);break;case"danger":d.error(t);break;case"warning":d.warning(t);break;case"info":d.info(t)}}p=h(location).attr("pathname"),f=p.split("/"),m=2,"index.php"===f[1]&&f.length>1&&(m=3),"super-administration"===f[m]&&(m+=1),""===f[f.length-1]&&f.pop(),h(".menu-item").removeClass("active"),h("#menu-"+f[m]).addClass("active"),i().mixin({customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),h(document).on("click",".supprimer",(function(t){t.preventDefault();var e=h(this).attr("href"),n=h(this).data("csrf");i().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(a){a.value?h.ajax({url:e,type:"DELETE",data:{_token:n},success:function(e){if(e.hasOwnProperty("redirect")&&e.hasOwnProperty("url"))document.location.href=e.url;else{var n=g(t.target,"tr");n.parentNode.removeChild(n),y("Suppression effectuée avec succès","success"),i().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}},error:function(t,e,n){i().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),y("Erreur lors de la tentative de suppression","danger")}}):"cancel"===a.dismiss&&i().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))}));var k="",w="text",C=!1;function O(t){var e=h("#myedit-input-"+t).val();h.ajax({url:k.attr("href"),data:{field:k.data("field"),value:e,type:w},method:"POST",success:function(){k.html(e),h("#myEdit-zone-"+t).replaceWith(k),C=!1}})}function _(t,e){var n={};return h.each(h(t).data(),(function(t,a){if("provide"!=(t=t.replace(/-([a-z])/g,(function(t){return t[1].toUpperCase()})))){if(null!=e)switch(e[t]){case"bool":a=Boolean(a);break;case"num":a=Number(a);break;case"array":a=a.split(",")}n[t]=a}})),n}function x(t){t.removeClass("is-valid").addClass("is-invalid")}function j(t){t.removeClass("is-invalid").addClass("is-valid")}function S(t){t.removeClass("is-invalid").removeClass("is-valid")}h(document).on("click",".myedit",(function(t){t.preventDefault(),k=h(this);var e,n,a="";C=!0,void 0!==h(this).data("type")&&(w=h(this).data("type")),"select"===h(this).data("type")||("textarea"===h(this).data("type")?(e=h(this),n=Date.now(),b=!0,a='<div id="myEdit-zone-'+n+'">\n                      <textarea rows="5" class="form-control" id="myedit-input-'+n+'">'+e.html().trim()+'</textarea>\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide" data-key="'+n+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+n+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'):a=function(t){var e=Date.now();return'<div id="myEdit-zone-'+e+'" class="input-group">\n                      <input type="text" class="form-control" id="myedit-input-'+e+'" value="'+t.html().trim()+'" >\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide"  data-key="'+e+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+e+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'}(h(this))),h(this).replaceWith(a),h("#myedit-input").focus()})),h(document).on("keyup","#myedit-input",(function(t){13===t.keyCode&&!1===b?O():27===t.keyCode&&h("#myEdit-zone").replaceWith(k)})),h(document).on("click",".myedit-valide",(function(t){b=!1,t.preventDefault(),O(h(this).data("key"))})),h(document).on("keypress",(function(t){!0===C&&!1===b&&13===t.which&&(t.preventDefault(),O(h(this).data("key"))),!0===C&&!1===b&&27===t.which&&(t.preventDefault(),h("#myEdit-zone-"+h(this).data("key")).replaceWith(k))})),h(document).on("click",".myedit-annule",(function(t){t.preventDefault(),h("#myEdit-zone-"+h(this).data("key")).replaceWith(k)})),v.fn.dataAttr=function(t,e){return h(this)[0].getAttribute("data-"+t)||e},v.fn.hasDataAttr=function(t){return h(this)[0].hasAttribute("data-"+t)}}},t=>{"use strict";t.O(0,[9755,2109,4093,1870,8771,5322],(()=>{return e=3480,t(t.s=e);var e}));t.O()}]);