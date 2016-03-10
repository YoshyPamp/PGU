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






