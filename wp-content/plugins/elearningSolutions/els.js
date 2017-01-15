var $ = jQuery.noConflict();


/*********************************************
* Function Name: removeScheduledExam
* Description: Click function that removes
* scheduled exams from the DOM.
***/

  // Declare variables and add event listeners

  function removeScheduledExam(id){
    var elements = document.getElementsByClassName('admin_data');
    for(var a=0;a<elements.length;a++){
      $('.info_'+id).fadeOut('slow');
      setTimout(function(){ $('.info_'+id).remove(); }, 500);
    }
  }
