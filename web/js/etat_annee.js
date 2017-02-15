$(document).ready(function() {
    function changeDetails(btn,annee_id) {

        //Déselection des buttons
        $(".etat-not-select" + annee_id).each(function(index){
            var btn_selected = $(this);
            btn_selected.removeClass('btn-clicked');
            btn_selected.removeClass('active');
        });

        //Selection du bouton courant
        btn.addClass("btn-clicked active");

        $("#open" + annee_id).html(btn.data('open'));
        $("#close" + annee_id).html(btn.data('close'));
        $("#state" + annee_id).html(btn.data('state'));
        $("#description" + annee_id).html(btn.data('description'));
    }


    $('.btn-custom').click(function(){
        var btn = $(this);
        var id = btn.data('annee');
        changeDetails(btn,id);
    });

    //Séléection de l'année courante
    $('#btn-global-annee').data('annee-id',$('#annee-first').data('annee-id'));


    $('.btn-annee-select').click(function(){
        //Affichage du panel de base
        $('#panel-chrono').removeClass('hide');

        var btn = $(this);

        //On cache tous les blocs années
        $('.bloc-info').each(function(){
            $(this).addClass('hide');
        });

        //Modif valeur annee selectionnée
        var annee_selectionnee = $(this).html();
        $('#btn-global-annee').html(annee_selectionnee +" <span class='caret'></span>");
        var annee_id = btn.data('annee-id');
        $('#btn-global-annee').data('annee-id',annee_id);

        //On affiche le panel de l'année
        $("#bloc-info-" + annee_id).removeClass('hide');

        //On sélectionne la période courante
        if($('#etat-active-' + annee_id).length == 0){
            $('#etat-first-' + annee_id).click();
        }
        else{
            $('#etat-active-' + annee_id).click();
        }

    });

    $('#annee-first').get(0).click();

});