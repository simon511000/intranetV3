(self.webpackChunk=self.webpackChunk||[]).push([[6571],{29740:(t,e,n)=>{"use strict";n(56977),n(54678),n(91058),n(74916),n(23123);var a=n(93320),i=n(19755),r=n(19755),o=1;function s(){for(var t=0,e=0,n=0,a=0,r=0,s=0,l=0,c=0,d=1;d<=o;d++){var u=i("#cm_"+d),p=i("#td_"+d),m=i("#tp_"+d);t+=parseFloat(u.val())*parseInt(i("#gr_cm_"+d).val()),e+=parseFloat(p.val())*parseInt(i("#gr_td_"+d).val()),n+=parseFloat(m.val())*parseInt(i("#gr_tp_"+d).val()),s+=parseFloat(u.val()),l+=parseFloat(p.val()),c+=parseFloat(m.val()),r=r+parseFloat(u.val())+parseFloat(p.val())+parseFloat(m.val())}a=t+e+n,i("#totalCm").html(t.toFixed(2)),i("#totalTd").html(e.toFixed(2)),i("#totalTp").html(n.toFixed(2)),i("#totalEqTd").html(a.toFixed(2)),i("#totalEtu").html(r.toFixed(2)),i("#totalHeuresCm").html(s.toFixed(2)),i("#totalHeuresTd").html(l.toFixed(2)),i("#totalHeuresTp").html(c.toFixed(2));var v=parseFloat(i("#cmMaquette").html())-s,f=parseFloat(i("#tdMaquette").html())-l,h=parseFloat(i("#tpMaquette").html())-c;i("#diffCm").html(v.toFixed(2)),i("#diffTd").html(f.toFixed(2)),i("#diffTp").html(h.toFixed(2)),v<0?i("#diffCm").addClass("erreurPrevi").removeClass("validePrevi"):i("#diffCm").addClass("validePrevi").removeClass("erreurPrevi"),f<0?i("#diffTd").addClass("erreurPrevi").removeClass("validePrevi"):i("#diffTd").addClass("validePrevi").removeClass("erreurPrevi"),h<0?i("#diffTp").addClass("erreurPrevi").removeClass("validePrevi"):i("#diffTp").addClass("validePrevi").removeClass("erreurPrevi")}i(document).on("change",".changeIntervenantPrevi",(function(t){t.preventDefault(),t.stopPropagation(),i.ajax({url:Routing.generate("administration_previsionnel_ajax_edit",{id:i(this).data("previ")}),method:"POST",data:{value:i(this).val(),field:"personnel"},success:function(){(0,a.qX)("Modification de prévisionnel enregistrée !","success")},error:function(){(0,a.qX)("Erreur lors de la modification du prévisionnel !","danger")}})})),i(document).on("change","#previSemestre",(function(t){t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_semestre",{semestre:i(this).val(),annee:i(this).data("annee")}))})),i(document).on("click","#reloadPreviSemestre",(function(t){t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_semestre",{semestre:i(this).data("semestre"),annee:i(this).data("annee")}))})),i(document).on("change","#previMatiere",(function(t){var e=i(this).val().split("_");t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_matiere",{matiere:e[1],type:e[0],annee:i(this).data("annee")}))})),i(document).on("click","#reloadPreviMatiere",(function(t){t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_matiere",{matiere:i(this).data("matiere"),type:i(this).data("type"),annee:i(this).data("annee")}))})),i(document).on("change","#previPersonnel",(function(t){t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_personnel",{personnel:i(this).val(),annee:i(this).data("annee")}))})),i(document).on("click","#reloadPreviPersonnel",(function(t){t.preventDefault(),t.stopPropagation(),i("#blocPrevisionnel").empty().load(Routing.generate("administration_previsionnel_personnel",{personnel:i(this).data("personnel"),annee:i(this).data("annee")}))})),i(document).on("click",".previsionnel_add_change",(function(t){t.preventDefault(),t.stopPropagation(),i(".previsionnel_add_change").removeClass("active show"),i(this).addClass("active show"),i("#mainContent").empty().load(i(this).attr("href"))})),i(document).on("click","#addIntervenantPrevisionnel",(function(t){t.preventDefault(),t.stopPropagation(),o++,i.ajax({url:Routing.generate("api_enseignants_departement"),method:"POST",success:function(t){var e='<tr>\n                        <td>\n                            <select class="form-control" data-live-search="true" name="intervenant_'+o+'" id="intervenant_'+o+'">\n                                <option value="">Choisir l\'intervenant</option>\n';r.each(t,(function(t,n){e=e+'<option value="'+n.id+'">'+n.display+"</option>\n"})),e=e+'                            </select>\n                        </td>\n                        <td><input type="text" name="cm_'+o+'" id="cm_'+o+'" data-ligne="'+o+'" class="form-control chgcm" value="0"></td>\n                        <td><input type="number" name="gr_cm_'+o+'" id="gr_cm_'+o+'" value="0" data-ligne="'+o+'" class="form-control chgcm">\n                        </td>\n                        <td id="ind_cm_'+o+'">0</td>\n                        <td style="background-color: #68C39F"><input type="text" value="0" name="td_'+o+'" id="td_'+o+'" data-ligne="'+o+'"\n                                                                     class="form-control chgtd"></td>\n                        <td style="background-color: #68C39F"><input type="number" value="0" name="gr_td_'+o+'" id="gr_td_'+o+'"\n                                                                     data-ligne="'+o+'" class="form-control chgtd"></td>\n                        <td style="background-color: #68C39F" id="ind_td_'+o+'">0</td>\n                        <td style="background-color: #FFC052"><input type="text" value="0" name="tp_'+o+'" id="tp_'+o+'" data-ligne="'+o+'"\n                                                                     class="form-control chgtp"></td>\n                        <td style="background-color: #FFC052"><input type="number" value="0" name="gr_tp_'+o+'" id="gr_tp_'+o+'"\n                                                                     data-ligne="'+o+'" class="form-control chgtp"></td>\n                        <td style="background-color: #FFC052" id="ind_tp_'+o+'">0</td>\n                    </tr>',i("#nbLigne").val(o),i("#ligneAdd").before(e),i("#intervenant_"+o).selectpicker()}})})),i(document).on("change",".chgcm",(function(t){var e=i(this).data("ligne"),n=parseFloat(i("#cm_"+e).val())/1.5;i("#ind_cm_"+e).html(n.toFixed(2)),s()})),i(document).on("change",".chgtd",(function(t){var e=i(this).data("ligne"),n=parseFloat(i("#td_"+e).val())/1.5;i("#ind_td_"+e).html(n.toFixed(2)),s()})),i(document).on("change",".chgtp",(function(t){var e=i(this).data("ligne"),n=parseFloat(i("#tp_"+e).val())/1.5;i("#ind_tp_"+e).html(n.toFixed(2)),s()})),i(document).on("change","#previsionnel_matiere",(function(){var t=i("#volumeMatiere");""===i(this).val()?t.html("Choisir d'abord une matière"):i.ajax({url:Routing.generate("api_matiere",{matiere:i(this).val()}),success:function(e){var n="PPN Officiel => CM "+e.cmFormation+" heure(s); TD "+e.tdFormation+" heure(s); TP "+e.tpFormation+" heure(s); PPN Réalisé/departement => CM "+e.cmPpn+" heure(s); TD "+e.tdPpn+" heure(s); TP "+e.tpPpn+" heure(s);";t.html(n),i("#cmMaquette").html(e.cmFormation),i("#totalHeuresCm").html(0),i("#diffCm").html(0),i("#tdMaquette").html(e.tdFormation),i("#totalHeuresTd").html(0),i("#diffTd").html(0),i("#tpMaquette").html(e.tpFormation),i("#totalHeuresTp").html(0),i("#diffTp").html(0),i("#tabheures").show()}})})),i(document).on("click","#btnGenereFichier",(function(t){t.preventDefault(),t.stopPropagation();var e=[];i("input:checkbox[name=exportChamps]:checked").each((function(){e.push(i(this).val())})),i.ajax({url:Routing.generate("export_listing.fr"),method:"POST",data:{matiere:i(this).data("matiere"),exportTypeDocument:i("input[type=radio][name=exportTypeDocument]:checked").val(),exportChamps:e,exportFormat:i("input[type=radio][name=exportFormat]:checked").val(),exportFiltre:i("input[type=radio][name=exportFiltre]:checked").val()},success:function(t){}})})),i(document).on("change","#change_annee_temp_hrs",(function(t){window.location=Routing.generate("administration_hrs_index",{annee:i(this).val()})})),i(document).on("change","#change_annee_temp_previsionnel",(function(t){window.location=Routing.generate("administration_previsionnel_index",{annee:i(this).val()})}))},93320:(t,e,n)=>{"use strict";n.d(e,{qX:()=>p,xQ:()=>g,XQ:()=>y,zl:()=>b,FX:()=>_});n(3843),n(83710),n(9653),n(74916),n(15306),n(23123),n(73210);var a,i,r,o=n(19755),s=n.n(o),l=n(86455),c=n.n(l),d=n(19755),u=!1;function p(t,e){console.log("callout");var n=new Array;n.success="Succès",n.danger="Erreur",n.warning="Attention";var a='<div class="callout callout-'+e+'" role="alert">\n                    <button type="button" class="close" data-dismiss="callout" aria-label="Close">\n                        <span>&times;</span>\n                    </button>\n                    <h5>'+n[e]+"</h5>\n                    <p>"+t+"</p>\n                </div>";s()("#mainContent").prepend(a).slideDown("slow"),s()(".callout").delay(5e3).slideUp("slow")}a=s()(location).attr("pathname"),i=a.split("/"),r=2,"index.php"===i[1]&&i.length>1&&(r=3),"super-administration"===i[r]&&(r+=1),""===i[i.length-1]&&i.pop(),s()(".menu-item").removeClass("active"),s()("#menu-"+i[r]).addClass("active"),c().mixin({customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),s()(document).on("click",".supprimer",(function(t){t.preventDefault();var e=s()(this).attr("href"),n=s()(this).data("csrf");c().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(t){t.value?s().ajax({url:e,type:"DELETE",data:{_token:n},success:function(t){t.hasOwnProperty("redirect")&&t.hasOwnProperty("url")?document.location.href=t.url:(s()("#ligne_"+t).closest("tr").remove(),p("Suppression effectuée avec succès","success"),c().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}))},error:function(t,e,n){c().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),p("Erreur lors de la tentative de suppression","danger")}}):"cancel"===t.dismiss&&c().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))}));var m="",v="text",f=!1;function h(t){var e=s()("#myedit-input-"+t).val();s().ajax({url:m.attr("href"),data:{field:m.data("field"),value:e,type:v},method:"POST",success:function(){m.html(e),s()("#myEdit-zone-"+t).replaceWith(m),f=!1}})}function g(t,e){var n={};return s().each(s()(t).data(),(function(t,a){if("provide"!=(t=t.replace(/-([a-z])/g,(function(t){return t[1].toUpperCase()})))){if(null!=e)switch(e[t]){case"bool":a=Boolean(a);break;case"num":a=Number(a);break;case"array":a=a.split(",")}n[t]=a}})),n}function y(t){t.removeClass("is-valid").addClass("is-invalid")}function b(t){t.removeClass("is-invalid").addClass("is-valid")}function _(t){t.removeClass("is-invalid").removeClass("is-valid")}s()(document).on("click",".myedit",(function(t){t.preventDefault(),m=s()(this);var e,n,a="";f=!0,void 0!==s()(this).data("type")&&(v=s()(this).data("type")),"select"===s()(this).data("type")||("textarea"===s()(this).data("type")?(e=s()(this),n=Date.now(),u=!0,a='<div id="myEdit-zone-'+n+'">\n                      <textarea rows="5" class="form-control" id="myedit-input-'+n+'">'+e.html().trim()+'</textarea>\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide" data-key="'+n+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+n+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'):a=function(t){var e=Date.now();return'<div id="myEdit-zone-'+e+'" class="input-group">\n                      <input type="text" class="form-control" id="myedit-input-'+e+'" value="'+t.html().trim()+'" >\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide"  data-key="'+e+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+e+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'}(s()(this))),s()(this).replaceWith(a),s()("#myedit-input").focus()})),s()(document).on("keyup","#myedit-input",(function(t){13===t.keyCode&&!1===u?h():27===t.keyCode&&s()("#myEdit-zone").replaceWith(m)})),s()(document).on("click",".myedit-valide",(function(t){u=!1,t.preventDefault(),h(s()(this).data("key"))})),s()(document).on("keypress",(function(t){!0===f&&!1===u&&13===t.which&&(t.preventDefault(),h(s()(this).data("key"))),!0===f&&!1===u&&27===t.which&&(t.preventDefault(),s()("#myEdit-zone-"+s()(this).data("key")).replaceWith(m))})),s()(document).on("click",".myedit-annule",(function(t){t.preventDefault(),s()("#myEdit-zone-"+s()(this).data("key")).replaceWith(m)})),d.fn.dataAttr=function(t,e){return s()(this)[0].getAttribute("data-"+t)||e},d.fn.hasDataAttr=function(t){return s()(this)[0].hasAttribute("data-"+t)}},2814:(t,e,n)=>{var a=n(17854),i=n(53111).trim,r=n(81361),o=a.parseFloat,s=1/o(r+"-0")!=-1/0;t.exports=s?function(t){var e=i(String(t)),n=o(e);return 0===n&&"-"==e.charAt(0)?-0:n}:o},83009:(t,e,n)=>{var a=n(17854),i=n(53111).trim,r=n(81361),o=a.parseInt,s=/^[+-]?0[Xx]/,l=8!==o(r+"08")||22!==o(r+"0x16");t.exports=l?function(t,e){var n=i(String(t));return o(n,e>>>0||(s.test(n)?16:10))}:o},38415:(t,e,n)=>{"use strict";var a=n(99958),i=n(84488);t.exports="".repeat||function(t){var e=String(i(this)),n="",r=a(t);if(r<0||r==1/0)throw RangeError("Wrong number of repetitions");for(;r>0;(r>>>=1)&&(e+=e))1&r&&(n+=e);return n}},50863:(t,e,n)=>{var a=n(84326);t.exports=function(t){if("number"!=typeof t&&"Number"!=a(t))throw TypeError("Incorrect invocation");return+t}},56977:(t,e,n)=>{"use strict";var a=n(82109),i=n(99958),r=n(50863),o=n(38415),s=n(47293),l=1..toFixed,c=Math.floor,d=function(t,e,n){return 0===e?n:e%2==1?d(t,e-1,n*t):d(t*t,e/2,n)};a({target:"Number",proto:!0,forced:l&&("0.000"!==8e-5.toFixed(3)||"1"!==.9.toFixed(0)||"1.25"!==1.255.toFixed(2)||"1000000000000000128"!==(0xde0b6b3a7640080).toFixed(0))||!s((function(){l.call({})}))},{toFixed:function(t){var e,n,a,s,l=r(this),u=i(t),p=[0,0,0,0,0,0],m="",v="0",f=function(t,e){for(var n=-1,a=e;++n<6;)a+=t*p[n],p[n]=a%1e7,a=c(a/1e7)},h=function(t){for(var e=6,n=0;--e>=0;)n+=p[e],p[e]=c(n/t),n=n%t*1e7},g=function(){for(var t=6,e="";--t>=0;)if(""!==e||0===t||0!==p[t]){var n=String(p[t]);e=""===e?n:e+o.call("0",7-n.length)+n}return e};if(u<0||u>20)throw RangeError("Incorrect fraction digits");if(l!=l)return"NaN";if(l<=-1e21||l>=1e21)return String(l);if(l<0&&(m="-",l=-l),l>1e-21)if(n=(e=function(t){for(var e=0,n=t;n>=4096;)e+=12,n/=4096;for(;n>=2;)e+=1,n/=2;return e}(l*d(2,69,1))-69)<0?l*d(2,-e,1):l/d(2,e,1),n*=4503599627370496,(e=52-e)>0){for(f(0,n),a=u;a>=7;)f(1e7,0),a-=7;for(f(d(10,a,1),0),a=e-1;a>=23;)h(1<<23),a-=23;h(1<<a),f(1,1),h(2),v=g()}else f(0,n),f(1<<-e,0),v=g()+o.call("0",u);return v=u>0?m+((s=v.length)<=u?"0."+o.call("0",u-s)+v:v.slice(0,s-u)+"."+v.slice(s-u)):m+v}})},54678:(t,e,n)=>{var a=n(82109),i=n(2814);a({global:!0,forced:parseFloat!=i},{parseFloat:i})},91058:(t,e,n)=>{var a=n(82109),i=n(83009);a({global:!0,forced:parseInt!=i},{parseInt:i})}},0,[[29740,3666,9755,2109,6208,9904]]]);