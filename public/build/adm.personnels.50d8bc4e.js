(self.webpackChunk=self.webpackChunk||[]).push([[7903],{26381:(e,t,n)=>{"use strict";n.d(t,{r:()=>a});var a={decimal:"",emptyTable:"Aucune donn&eacute;e disponible dans le tableau",info:"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",infoEmpty:"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",infoFiltered:"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",infoPostFix:"",thousands:",",lengthMenu:"Afficher _MENU_ &eacute;l&eacute;ments",loadingRecords:"Chargement en cours...",processing:"Traitement en cours...",search:"Rechercher&nbsp;:",zeroRecords:"Aucun &eacute;l&eacute;ment &agrave; afficher",paginate:{first:"Premier",last:"Dernier",next:"Suivant",previous:"Pr&eacute;c&eacute;dent"},aria:{sortAscending:": activer pour trier la colonne par ordre croissant",sortDescending:": activer pour trier la colonne par ordre d&eacute;croissant"},searchBuilder:{conditions:{date:{after:"Après le",before:"Avant le",between:"Entre",empty:"Vide",equals:"Egal à",not:"Différent de",notBetween:"Pas entre",notEmpty:"Non vide"},number:{between:"Entre",empty:"Vide",equals:"Egal à",gt:"Supérieur à",gte:"Supérieur ou égal à",lt:"Inférieur à",lte:"Inférieur ou égal à",not:"Différent de",notBetween:"Pas entre",notEmpty:"Non vide"},string:{contains:"Contient",empty:"Vide",endsWith:"Se termine par",equals:"Egal à",not:"Différent de",notEmpty:"Non vide",startsWith:"Commence par"},array:{equals:"Egal à",empty:"Vide",contains:"Contient",not:"Différent de",notEmpty:"Non vide",without:"Sans"}},add:"Ajouter une condition",button:{0:"Recherche avancée",_:"Recherche avancée (%d)"},clearAll:"Effacer tout",condition:"Condition",data:"Donnée",deleteTitle:"Supprimer la règle de filtrage",logicAnd:"Et",logicOr:"Ou",title:{0:"Recherche avancée",_:"Recherche avancée (%d)"},value:"Valeur"}}},10336:(e,t,n)=>{"use strict";var a=n(26381),r=n(19755),o=n(19755);r(document).ready((function(){r("#tableau").DataTable({langue:a.r})})),r(document).on("keyup","#login_urca",(function(){var e=r(this).val();e.length>2&&r.ajax({url:Routing.generate("api_personnel_recherche",{needle:e}),dataType:"json",success:function(e){var t="";r("#result").empty(),o.each(e,(function(e,n){t=t+"<tr><td>"+n.nom+"</td><td>"+n.prenom+"</td><td>"+n.numeroHarpege+"</td><td>"+n.username+"</td><td>"+n.mail_univ+'</td><td><a href="#" class="btn btn-success btn-outline btn-square addpersonnel" data-provide="tooltip" data-placement="bottom" title="Ajouter au departement" data-slug="'+n.slug+'"><i class="fas fa-plus"></i></a></td></tr>'})),t=t+'<tr><td colspan="6"><a href="'+Routing.generate("administration_personnel_create")+'" class="btn btn-success btn-block" data-provide="tooltip" data-placement="bottom" title="Ajouter un intervenant"><i class="fas fa-plus"></i> Si l\'internvenant n\'est pas présent dans l\'intranet vous pouvez l\'ajouter</a></td></tr>',r("#result").append(t)}})})),r(document).on("click",".addpersonnel",(function(){r.ajax({url:Routing.generate("api_personnel_add_to_departement",{slug:r(this).data("slug")}),dataType:"json",success:function(e){console.log(e),addCallout("Personnel ajouté au département !","success")}})})),r(document).on("click",".personnel_index_change",(function(){r(".personnel_index_change").removeClass("active show"),r(this).addClass("active show");var e=r(this).data("type");r("#listePersonnel").load(Routing.generate("administration_personnel_load_liste",{type:e}),(function(){r("#tableau").DataTable({language:a.r}),r("#export_csv").attr("href",Routing.generate("administration_personnel_export",{type:e,_format:"csv"})),r("#export_xlsx").attr("href",Routing.generate("administration_personnel_export",{type:e,_format:"xlsx"})),r("#export_pdf").attr("href",Routing.generate("administration_personnel_export",{type:e,_format:"pdf"}))}))})),r(document).on("change",".updateRole",(function(){r.ajax({url:Routing.generate("admin_personnel_departement_modifier_droit",{personnel:r("#personnel").val()}),method:"POST",data:{droit:r(this).val()},success:function(e){addCallout("Droits modifiés !","success")}})}))}},0,[[10336,3666,9755]]]);