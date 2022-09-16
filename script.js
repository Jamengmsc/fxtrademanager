// EXIT APPLICATION
var exitAppBg = document.querySelector(".exit_popup"),
  exitAppDialog = document.getElementById("exit_app");
  
function openExitDial(){
  exitAppBg.style.opacity = "1";
  exitAppBg.style.zIndex = "10001";

  exitAppDialog.style.opacity = "1";
  exitAppDialog.style.transform = "scale(1)";
}

function closeExitDial(){
  exitAppBg.style.opacity = "0";
  exitAppBg.style.zIndex = "-1";

  exitAppDialog.style.opacity = "0";
  exitAppDialog.style.transform = "scale(0)";
}

function exitApp(event){
  event.preventDefault();

  openExitDial();

  // Cancel Exiting Application
  document.getElementById("cancel_exit").onclick = () => {
      closeExitDial();
  }

  // Confirm Exiting Application
  document.getElementById("ok_exit").onclick = () => {
      location.href = "logout.php";

      closeExitDial();
  }

  window.onscroll = () =>{
      closeExitDial();
  }

  window.onmouseup = (ev) =>{
      if(ev.target !== exitAppDialog || ev.target.parentNode !== exitAppDialog){
        closeExitDial();
      }
  }
}


// declaring investment start and end dates
var date = new Date();
var today = date.getDate();
var month = date.getMonth();
var year = date.getFullYear();

var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]; //Array of months
var thisMonth = months[month];

if(today < 10) {
  today = "0" + today
}

//Displaying time and date
document.querySelector('#date>span:last-child').innerHTML = " " + today + "-" + thisMonth + "-" + year; //displaying date

setInterval(runningTime, 500)
function runningTime() {
  var date = new Date();
  var hour = date.getHours();
  var min = date.getMinutes()
  var sec = date.getSeconds()

  if(hour<10){hour = "0" + hour}
  if(min<10){min = "0"+min}
  if(sec<10){sec = "0"+sec}

  // document.querySelector('#stime>span:last-child').innerHTML = " " + hour + ":" + min;
}


// Open menu items on click of menu icon
var menuIcon = document.querySelector('#navbar i');

menuIcon.addEventListener("click", function(){
  var menuWrap = document.querySelector(".menu-wrap");
  var menuList = document.querySelector(".menu-list");
  
    menuWrap.classList.add("active");
    menuList.classList.add("active");

    // Close Menu
    document.querySelector(".menu_head .close_menu").addEventListener("click", function(){
      menuWrap.classList.remove("active");
      menuList.classList.remove("active");
    })

    window.onscroll = () => {
      menuWrap.classList.remove("active");
      menuList.classList.remove("active");
    }

    window.onmouseup = (ev) => {
      if(ev.target !== menuList || ev.target.parentNode !== menuList) {
        menuWrap.classList.remove("active");
        menuList.classList.remove("active");
      }
    }
    
  });

  
// Transactions Menu
var openTrans = document.querySelector(".trans_text");
  openTrans.addEventListener("click", function(){
      
      var dispTrans = document.querySelector(".disp_trans");
      var transPopup = document.querySelector(".trans_pop");

        dispTrans.classList.toggle("active");
        transPopup.classList.toggle("active");


      // On click of window, close the transaction menu list
      window.addEventListener("mouseup", function(ev){
        if(ev.target !== dispTrans || ev.target.parentNode !== dispTrans){
            dispTrans.classList.remove("active");
            transPopup.classList.remove("active");
        }
      });

      // On window scroll, remove active class
      window.onscroll = () => {
        dispTrans.classList.remove("active");
        transPopup.classList.remove("active");
      }
  });

// Confirmation popup dialog
  var affirmPopup = document.querySelector(".affirm");
  var affirmText = document.querySelector(".affirm p");

  setTimeout(function(){
      affirmPopup.classList.remove("active");
  }, 3000);



// Show Trade Item on click of item row
  function showTrade(str){
    var tradePop = document.querySelector(".trade_pop");
    var tradePopBg = document.querySelector(".trade_pop_bg");

    tradePopBg.classList.add("active");
    tradePop.classList.add("active");


    // Fill/populate edit form with data of the clicked record
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ajax/trade_details.php?trade_id="+str, true);
    xhr.onload = function(){
      if(this.status == 200){
          var myObj = JSON.parse(this.responseText);

          document.getElementById("trade_date").innerHTML = myObj[0];
          document.getElementById("pair").innerHTML = myObj[1];
          document.getElementById("position").innerHTML = myObj[2];
          document.getElementById("lotsize").innerHTML = myObj[3];
          document.getElementById("profit").innerHTML = myObj[4];
      }
    }

    xhr.send();


    // Dismiss/close box
    var dismissTradePop = document.querySelector(".dismiss_pop");
    dismissTradePop.onclick = () => {
      tradePopBg.classList.remove("active");
      tradePop.classList.remove("active");
    }
  }


var closeTodoNow = document.querySelector(".todo_now .fa-times");

closeTodoNow.addEventListener("click", function(){
  document.querySelector(".todo_now").classList.add("d-none");
})



function viewTask(str){
var xhr = new XMLHttpRequest();
  xhr.open("GET", "ajax/view-task.php?id="+str, true);
  xhr.onload = function(){
    if(this.status == 200){
      location.href = "todo.php";
    }
  }

  xhr.send();
}
