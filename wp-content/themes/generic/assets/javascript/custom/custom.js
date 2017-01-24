/**********************************
*  Add menu toggle to mobile nav
***********************************/
window.addEventListener('load', toggleMobileNav, false);
function toggleMobileNav(){
  var navItems = document.getElementsByClassName('menu-item');
  var a = 0;
  if(window.innerWidth < 650){
    for(a;a<navItems.length;a++){
      navItems[a].setAttribute("aria-controls", "mobile-menu");
      navItems[a].setAttribute("data-toggle", "mobile-menu");
    }
  }
  else{
    return;
  }
}

/********************************
*  Scroll to navigation element
*********************************/
var links = document.getElementsByTagName('a');
for(var i=0;i<12;i++){
  links[i].addEventListener('click', scrollTo, false);
}

function scrollTo(e){
  e.preventDefault();
  var origin = $(this);
  var dest = $(this).attr('href');
  if(window.innerWidth < 650){
    var timeout = setTimeout(function(){
      $('#return').remove();
      $('body, html').animate({ scrollTop : $(dest).offset().top+'px'}, 2000);
      $(dest).append('<a href="#masthead" id="return"><i class="fa fa-arrow-up"></i> back to top</a>');
      document.getElementById('return').addEventListener('click', backToTop, false);
    }, 500);
  }
  else{
    $('#return').remove();
    $('body, html').animate({ scrollTop : $(dest).offset().top+40+'px'}, 1000);
    $(dest).append('<a href="#masthead" id="return"><i class="fa fa-arrow-up"></i> back to top</a>');
    document.getElementById('return').addEventListener('click', backToTop, false);
  }

}

function backToTop(event){
  event.preventDefault();
  var id = event.target.getAttribute('id');
  if(id == 'return'){
    document.getElementById('return').remove();
    $('body, html').animate({scrollTop : '0px'}, 1000);
  }
  else{
    $('body, html').animate({scrollTop : '0px'}, 1000);
  }
}

/********************************
*  Video player controls
*  this may be reactivate at a later time
*********************************/

/*animate control lights*/
    var controls = document.getElementsByClassName('control');
    for(i=0;i<controls.length;i++) {
      var a = controls[i];
      var play = document.querySelector('#play');
      var pause = document.querySelector('#pause');
      a.addEventListener('click', controlToggle, false);
      //a.setAttribute('style' , 'opacity : 0');
      play.setAttribute('style' , 'display : inline');
      pause.setAttribute('style' , 'display : none');
    }
function controlToggle(){
  if(play.attributes['style'].value == 'display:inline'){
    play.attributes['style'].value = 'display:none';
    pause.attributes['style'].value = 'display:inline'
  }
  else{
    play.attributes['style'].value = 'display:inline';
    pause.attributes['style'].value = 'display:none';
  }
}


/**************************************************
*  ANITMATE TEXT
***************************************************/
  var boxOne = document.querySelector('.animateBox_1');
  var boxTwo = document.querySelector('.animateBox_2');
  var animatedItems = document.getElementsByClassName('textItem');
  var animatedItemsNum = animatedItems.length;
  var index = 0;
  var rewind = document.querySelector('#rwd');
  var advance = document.querySelector('#fwd');
  var scrollButton = document.querySelector('#scrollto');
  var highlightedItems = document.getElementsByClassName('counted');
  var hl_index = 0;
  /* event listeners to animate containers */
  window.addEventListener('scroll', animateBoxOneScroll, false);
  window.addEventListener('load', animateBoxOneLoad, false);
  window.addEventListener('scroll', animateBoxTwoScroll, false);
  window.addEventListener('load', animateBoxTwoLoad, false);

  /* event listeners to animate text */
    rewind.addEventListener('click', function(){rewindTextClick();}, false);
    advance.addEventListener('click', function(){advanceTextClick();}, false);
    play.addEventListener('click', function(){playTextClick();}, false);
    pause.addEventListener('click', function(){pauseTextClick();}, false);
    scrollButton.addEventListener('click', scrollTo, false);

  /********************************
  * function 1: animateTextScroll
  * function activate onscroll
  *   - set interval
  *   - remove scroll event listener
  *   - clear interval if "index" is greater than a given value
  *   - call the "animate" function
  *********************************/
    function rewindTextClick(){
      console.log('function name: rewindTextClick() - line 157 int is: '+index);
        if( index == 0){
          index = 0;
          fwd.setAttribute('style' , 'display:inline');
          rwd.setAttribute('style' , 'display:none');
          return index;
        }
        else{
          console.log( 'function name: rewindTextClick() - line 163: index = '+index);
          index-=1;
          animateReset(index);
          console.log( 'function name: rewindTextClick() - line 168: index = '+index);
          fwd.setAttribute('style' , 'display:inline');
          return index;
        }
      }


    function advanceTextClick(){
      console.log('function name: advanceTextClick() - line 175 int is: '+index);
        if( index > animatedItemsNum-1){
          index = 7;
          fwd.setAttribute('style' , 'display:none');
          rwd.setAttribute('style' , 'display:inline');
          return index;
        }
        else{
            if( index == 0){
              animateIn(index);
            }
            else{
              animateOut(index-1);
              animateIn(index);
              console.log( 'function name: advanceTextClick() - line 187: index = '+index);
            }
            index+=1;
            rwd.setAttribute('style' , 'display:inline');
            var timeout = setTimeout( function(){highlightedItems[hl_index].className += ' highlighted'; hl_index++; return hl_index;}, 1000);
            console.log( 'function name: advanceTextClick() - line 189: index = '+index);
            return index;
        }
    }

    function playTextClick(){
      console.log('test');
      advanceTextClick();
       var interval = window.setInterval( function(){
         if(index == animatedItems.length){
           console.log('stopped line: 209');
           window.clearInterval(interval);
           fwd.setAttribute('style' , 'display:none');
           rwd.setAttribute('style' , 'display:inline');
           return index =7;
         }
         advanceTextClick();}, 3000);
    }

    function pauseTextClick(){
        window.clearInterval(interval);
    }
      /* B. Add classes "move" and "moveOut" respectively with a 1500sms timeout*/
      function animateIn(int){
        if(index == 0){
          animatedItems[index].className += ' moved';
          // int +=1;
          // index = int;
        }
        else{
          console.log('line 237: index= '+int);
          animatedItems[int].className += ' moved';
          animatedItems[int-1].className = 'textItem movedOut';
          // int +=1;
          // index = int;
        }
      }

      function animateOut(int){
        if(int == 0){
          return;
        }
        else{
          animatedItems[int].className += ' movedOut';
          animatedItems[int].className = 'textItem moved';
        }
      }

      function animateReset(int){
        console.log('line 221: int = '+int);
        if(int == 0){
          animatedItems[int].className = 'textItem';
        }
        else{
          animatedItems[int].className = 'textItem';
          animatedItems[int-1].className = 'textItem moved';
        }
      }

  /********************************
  * ANIMATE LAST TEXT ITEM
  *********************************/
  function displayText(param){
    var lastIndex = param-1;
    console.log('function name: displayText(); line 201 - index is: '+param)
    animatedItems[lastIndex].className = 'textItem moved';
  }

  /********************************
  * ANIMATE CONTAINERS
  *********************************/

    /** A. ANIMATE BOX ONSCROLL **/
    function animateBoxOneScroll(){
      if( scrollY > 3600 ){
        window.removeEventListener('scroll', animateBoxOneScroll);
        //console.log(scrollY);
        var timeout = setTimeout(moveBoxOne, 2000);
        function moveBoxOne(){
          boxOne.getElementsByClassName('large_text')[0].className += ' moved';
        }
      }
      else{ return; }
    }

    function animateBoxTwoScroll(){
      if( scrollY > 3600 ){
        window.removeEventListener('scroll', animateBoxTwoScroll);
        var timeout = setTimeout(function(){ boxTwo.className += ' moved'; }, 200);
        var timeout = setTimeout( animateControls, 1500);
        setTimeout( highlightPlay, 2500);;
      }
      else{ return; }
    }

    function highlightPlay(){
      play.setAttribute('style', 'box-shadow:0px 0px 7px 5px rgb(255, 255, 255)');
    }
    /** B. ANIMATE BOX ONLOAD  **/
    function animateBoxOneLoad(){
      if( scrollY > 3600 ){
        console.log('move box one');
        var timeout = setTimeout(moveBoxOne, 1000);
        function moveBoxOne(){
          boxOne.getElementsByClassName('large_text')[0].className += ' moved';
        }
      }
      else{ return; }
    }

    function animateBoxTwoLoad(){
      if( scrollY > 3600 ){
        console.log('move box two');
        boxTwo.className += ' moved';
        var timeout = setTimeout( highlightPlay, 2500);
      }
      else{ return; }
    }

    /** C. ANIMATE CONTROLS **/
    function animateControls(){
      var controls = document.querySelector('.controls_container');
      controls.className += ' moved';
    }
  /********************************
  * function 2: stopTextAnimation
  *********************************/
    function animateBoxTwo(){}

function refresh(){
  location.reload();
}
