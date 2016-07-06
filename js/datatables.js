/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('#example_alumnos').DataTable( {
        "pagingType": "full_numbers",
        "paging":false,
        "order": [[ 1, "desc" ]],
        "language": {
        "lengthMenu": "Mostrando _MENU_ datos por página.",
        "zeroRecords": "No se encuentran registros.",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "search": "Buscar:",
        "paginate": {
            "first":      "Primera",
            "last":       "Última",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "infoEmpty": "No hay registros disponibles.",
        "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
        }
    } );
    
    $('#historico_alumno').DataTable( {
        "pagingType": "full_numbers",
        "paging":false,
        "order": [[ 1, "desc" ]],
        "language": {
        "lengthMenu": "Mostrando _MENU_ datos por página.",
        "zeroRecords": "No se encuentran registros.",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "search": "Buscar:",
        "paginate": {
            "first":      "Primera",
            "last":       "Última",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "infoEmpty": "No hay registros disponibles.",
        "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
        }
    } );
       
    $('#example_asignaturas').DataTable( {
        "pagingType": "full_numbers",
        "paging":false,
        "order": [[ 2, "desc" ]],
        "language": {
        "lengthMenu": "Mostrando _MENU_ datos por página.",
        "zeroRecords": "No se encuentran registros.",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "search": "Buscar:",
        "paginate": {
            "first":      "Primera",
            "last":       "Última",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "infoEmpty": "No hay registros disponibles.",
        "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
            }
        } );
    } );
    
    
function selectAlumno(rut){
    window.location= 'alumno.php?rut='+rut;
}

function selectAsignatura(cod){
    window.location= 'asignatura.php?codigo='+cod;
}

//Alumno

function escondeElectivos(){
    $('#electivos').fadeToggle('fast');
}


