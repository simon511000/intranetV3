(self.webpackChunkintranetv3=self.webpackChunkintranetv3||[]).push([[6497],{1302:(t,e,n)=>{"use strict";n(54678);var a=n(1128),i=n(19755);i(".savegroupe").click((function(){var t=i(this).attr("id"),e={notes:[]},n=!1;i(".noteetudiant:input").each((function(){if(i(this).hasClass(t)){var a=i(this).data("etudiant"),s=i("#com_"+a);s.val().length>255&&(n=!0,s.addClass("is-invalid"));var r={id:a,note:i(this).val(),absence:i("#abs_"+a).prop("checked"),commentaire:s.val()};e.notes.push(r)}})),!1===n?i.ajax({url:Routing.generate("application_personnel_note_ajax_saisie",{uuid:i(this).data("evaluation")}),type:"POST",data:{notes:e},success:function(t){(0,a.qX)("Les notes de <strong>ce groupe</strong> ont été enregistrées avec succés ! Vous pouvez les modifier !","success")},error:function(){(0,a.qX)("Une erreur est survenue pendant l'envoi... <br>Veuillez réessayer","danger")}}):(0,a.qX)("Le formulaire contient des erreurs. Veuillez corriger et valider de nouveau","danger")})),i(document).on("keyup",".noteetudiant",(function(t){var e=i(this).val();"-0.01"===e?i(this).removeClass("is-invalid").removeClass("is-valid"):parseFloat(e)>=0&&parseFloat(e)<=20?i(this).removeClass("is-invalid").addClass("is-valid"):i(this).removeClass("is-valid").addClass("is-invalid")}))},1128:(t,e,n)=>{"use strict";n.d(e,{qX:()=>p,xQ:()=>h,XQ:()=>y,zl:()=>g,FX:()=>C});n(74916),n(23123),n(3843),n(83710),n(73210),n(15306),n(9653);var a,i,s,r=n(19755),o=n.n(r),c=n(86455),u=n.n(c),l=n(19755),d=!1;function p(t,e){switch(e){case"success":window.umbrella.Toast.success(t);break;case"danger":window.umbrella.Toast.error(t);break;case"warning":window.umbrella.Toast.warning(t);break;case"info":window.umbrella.Toast.info(t)}}a=o()(location).attr("pathname"),i=a.split("/"),s=2,"index.php"===i[1]&&i.length>1&&(s=3),"super-administration"===i[s]&&(s+=1),""===i[i.length-1]&&i.pop(),o()(".menu-item").removeClass("active"),o()("#menu-"+i[s]).addClass("active"),u().mixin({customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),o()(document).on("click",".supprimer",(function(t){t.preventDefault();var e=o()(this).attr("href"),n=o()(this).data("csrf");u().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(t){t.value?o().ajax({url:e,type:"DELETE",data:{_token:n},success:function(t){t.hasOwnProperty("redirect")&&t.hasOwnProperty("url")?document.location.href=t.url:(o()("#ligne_"+t).closest("tr").remove(),p("Suppression effectuée avec succès","success"),u().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}))},error:function(t,e,n){u().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),p("Erreur lors de la tentative de suppression","danger")}}):"cancel"===t.dismiss&&u().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))})),o()(document).on("click",".confirm-delete",(function(t){t.preventDefault();var e=o()(this).data("href"),n=o()(this).data("uuid"),a=o()(this).data("csrf");console.log(n),console.log(a),u().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(t){t.value?o().ajax({url:Routing.generate(e,{uuid:n}),type:"DELETE",data:{_token:a},success:function(t){t.hasOwnProperty("redirect")&&t.hasOwnProperty("url")?document.location.href=t.url:(o()("#ligne_"+t).closest("tr").remove(),p("Suppression effectuée avec succès","success"),u().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}))},error:function(t,e,n){u().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),p("Erreur lors de la tentative de suppression","danger")}}):"cancel"===t.dismiss&&u().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))}));var m="",f="text",v=!1;function b(t){var e=o()("#myedit-input-"+t).val();o().ajax({url:m.attr("href"),data:{field:m.data("field"),value:e,type:f},method:"POST",success:function(){m.html(e),o()("#myEdit-zone-"+t).replaceWith(m),v=!1}})}function h(t,e){var n={};return o().each(o()(t).data(),(function(t,a){if("provide"!=(t=t.replace(/-([a-z])/g,(function(t){return t[1].toUpperCase()})))){if(null!=e)switch(e[t]){case"bool":a=Boolean(a);break;case"num":a=Number(a);break;case"array":a=a.split(",")}n[t]=a}})),n}function y(t){t.removeClass("is-valid").addClass("is-invalid")}function g(t){t.removeClass("is-invalid").addClass("is-valid")}function C(t){t.removeClass("is-invalid").removeClass("is-valid")}o()(document).on("click",".myedit",(function(t){t.preventDefault(),m=o()(this);var e,n,a="";v=!0,void 0!==o()(this).data("type")&&(f=o()(this).data("type")),"select"===o()(this).data("type")||("textarea"===o()(this).data("type")?(e=o()(this),n=Date.now(),d=!0,a='<div id="myEdit-zone-'+n+'">\n                      <textarea rows="5" class="form-control" id="myedit-input-'+n+'">'+e.html().trim()+'</textarea>\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide" data-key="'+n+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+n+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'):a=function(t){var e=Date.now();return'<div id="myEdit-zone-'+e+'" class="input-group">\n                      <input type="text" class="form-control" id="myedit-input-'+e+'" value="'+t.html().trim()+'" >\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide"  data-key="'+e+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+e+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'}(o()(this))),o()(this).replaceWith(a),o()("#myedit-input").focus()})),o()(document).on("keyup","#myedit-input",(function(t){13===t.keyCode&&!1===d?b():27===t.keyCode&&o()("#myEdit-zone").replaceWith(m)})),o()(document).on("click",".myedit-valide",(function(t){d=!1,t.preventDefault(),b(o()(this).data("key"))})),o()(document).on("keypress",(function(t){!0===v&&!1===d&&13===t.which&&(t.preventDefault(),b(o()(this).data("key"))),!0===v&&!1===d&&27===t.which&&(t.preventDefault(),o()("#myEdit-zone-"+o()(this).data("key")).replaceWith(m))})),o()(document).on("click",".myedit-annule",(function(t){t.preventDefault(),o()("#myEdit-zone-"+o()(this).data("key")).replaceWith(m)})),l.fn.dataAttr=function(t,e){return o()(this)[0].getAttribute("data-"+t)||e},l.fn.hasDataAttr=function(t){return o()(this)[0].hasAttribute("data-"+t)}},2814:(t,e,n)=>{var a=n(17854),i=n(53111).trim,s=n(81361),r=a.parseFloat,o=1/r(s+"-0")!=-1/0;t.exports=o?function(t){var e=i(String(t)),n=r(e);return 0===n&&"-"==e.charAt(0)?-0:n}:r},54678:(t,e,n)=>{var a=n(82109),i=n(2814);a({global:!0,forced:parseFloat!=i},{parseFloat:i})}},t=>{"use strict";t.O(0,[9755,2109,6208,9904],(()=>{return e=1302,t(t.s=e);var e}));t.O()}]);