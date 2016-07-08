/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


window.onload = function() {
    if (window.jQuery) {  
        // jQuery is loaded  
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    } else {
        // jQuery is not loaded
        alert("Doesn't Work");
    }
}

$(window).load(function() {
    $(".loader").fadeOut("slow");
})

function ajax_cambiar_pass(){
    var pass1 = $('#pass1').val();
    var pass2 = $('#pass2').val();
    var pass3 = $('#pass3').val();
    var user = $('#user_id').val();
    
    if(pass1 === '' || pass2 === '' || pass3 === ''){
        alert('Debe completar todos los campos.');
    }else{
        if(pass2 !== pass3){
            alert('Contraseña nueva y confirmación no coinciden.');
        }else{
    
            $.ajax({
                method: "POST",
                url: "Config/Login/cambiar_password.php",
                data: { pass1: pass1,
                        pass2: pass2,
                        id_us: user}
            })
            .done(function( msg ){
                $('#pass1').val('');
                $('#pass2').val('');
                $('#pass3').val('');
                $('#cambiar_clave').modal('toggle');
                
                if(msg == 0){
                    $('#success').css("display","inline-block");
                    $('#success').append("Contraseña cambiada satisfactoriamente.");
                    setTimeout(function(){
                        $('#success').css("display","none");}, 5000);
                }else{
                    if(msg == -1){
                        $('#error').css("display","inline-block");
                        $('#error').append("Contraseña antigua no coincide con la ingresada.");
                        setTimeout(function(){
                        $('#error').css("display","none");}, 5000);
                    }else{
                        $('#error').css("display","inline-block");
                        $('#error').append("Error en ejecución de sentencia.");
                        setTimeout(function(){
                        $('#error').css("display","none");}, 5000);
                    }
                }
            })
            .fail(function(){
                alert( "Error en solicitud a servidor.");
            });
        }
    }
            
}

function ajax_enviar_registro(){
    var titulo = $('#titulo_bug').val();
    var comentario = $('#comentario_bug').val();
    
    $.ajax({
        method: "POST",
        url: "Config/enviar_correo.php",
        data: { tit: titulo,
                com: comentario}
        })
        .done(function( msg ){
            alert(msg);
        })
        .fail(function(){
            alert( "Error en solicitud a servidor.");
        });

}






