Dash.KeyBinder = new Object();
Dash.Console = new Object();
Dash.D3 = new Object();

Dash.KeyBinder.StoredKeys = [];
Dash.KeyBinder.BindingArray = [];
Dash.KeyBinder.KeysDown = 0;
Dash.KeyBinder.Log = false;

Dash.DialogCallback = null;


//Generates an order independent, unique key
Dash.KeyBinder.KeyGen = function(keys) {
  var key_string = 0;
  for (var i = 0; i < keys.length; i++) {
    key_string += keys[i];
  }
  return keys.length + ' | ' + key_string;
};

//Watches and creates appropriate variables for selected element
Dash.KeyBinder.Observe = function(item) {
  Dash.KeyBinder.StoredKeys[item] = [];
  $(item).on("keyup", function(e) {
    //Get the function
    Dash.KeyBinder.KeysDown--;
    var key = Dash.KeyBinder.KeyGen(Dash.KeyBinder.StoredKeys[item]);
    var result = Dash.KeyBinder.BindingArray[item][key];
    if (result !== undefined) {
      result();
    }

    if (Dash.KeyBinder.KeysDown == 0) {
      Dash.KeyBinder.StoredKeys[item] = [];
    } else {
      var index = Dash.KeyBinder.StoredKeys[item].indexOf(e.which);
      Dash.KeyBinder.StoredKeys[item].splice(index, 1);
    }

  });
  $(item).on("keydown", function(e) {
    if (Dash.KeyBinder.Log) {
      console.log(item + " " + e.which + ' [' + Dash.KeyBinder.TempStrings[item] + ']');
    }
    Dash.KeyBinder.StoredKeys[item].push(e.which);
    Dash.KeyBinder.KeysDown++;
  });
};

Dash.KeyBinder.Bind = function(item, keys, func) {
  if (Dash.KeyBinder.BindingArray[item] == undefined) {
    Dash.KeyBinder.BindingArray[item] = [];
  }
  var key = Dash.KeyBinder.KeyGen(keys);
  Dash.KeyBinder.BindingArray[item][key] = func;
};


//Basic dialog box
Dash.showDialog = function(title, content, callback) {

  Dash.DialogCallback = callback;

  $("body").addClass("dialog_shown");
  $("#dialog_wrapper").fadeIn();
  $("#container").addClass("show_dialog");
  $("#dialog_title").text(title);
  $("#dialog_content").html(content);
  $("#dialog_wrapper").css({
    "z-index": "100"
  });
  $("#dialog").css({
    "top": "-100px",
    "opacity": "0",
    "height": $("#dialog_inner").height() + 38
  }).animate({
    "top": "0",
    "opacity": "1"
  });

  //If enter key is pressed, first button will be pressed
  $("#dialog button, #dialog a.button").eq(0).focus();

};

Dash.showAlert = function(title, content, callback) {
  $("#dialog").addClass("small");
  Dash.showDialog(title, content + '<div style="margin-top:15px;text-align:center"><button class="button close">Okay</button></div>', callback);
};
Dash.showConfirm = function(title, content, callback) {
  $("#dialog").addClass("small");
  var res = false;
  Dash.showDialog(title, content + '<div style="margin-top:15px;text-align:center"><button class="button close" data-answer="Yes">Yes</button>&nbsp;<button class="button close" data-answer="No">No</button></div>', function(e) {
    res = $(e).attr("data-answer") === "Yes";
    if (callback !== null && callback instanceof Function) {
      callback(res);
    }
  });
};
Dash.showAjaxLoader = function() {
  $("#ajax_loader").show();
  $("#ajax_loader img").css({
    "height": "5px",
    "width": "5px"
  }).animate({
    "height": "50px",
    "width": "50px"
  }, 400);
};
Dash.hideAjaxLoader = function() {
  $("#ajax_loader").hide();
};
$(document).ready(function() {

  /*$("#menu_bar .item_group:not(.open)").find(".items").slideUp(0, function(){
    $("#menu_bar .item_group").height("auto");
  });*/

  var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var date = new Date();

  function setTime() {
    var date = new Date();
    var hrs = date.getHours();
    if (hrs < 10) {
      hrs = "0" + hrs;
    }
    var mins = date.getMinutes();
    if (mins < 10) {
      mins = "0" + mins;
    }
    $("#date").html(date.getDate() + " " + monthNames[date.getMonth()] + " " + date.getFullYear() + ' | <span style="font-weight:bold">' + hrs + ":" + mins + "</span>");
  }

  //Responsive table
  $('.table_flow').each(function(){
    $(this).wrap('<div class="table_wrapper">');
  })

  setTimeout(function(){
    $('label.placeholder').each(function(){
      if($(this).find('input, textarea').val() != ""){
        $(this).find('span').stop().css({"top" : "3px", "font-size" : "12px"});
        $(this).addClass("active");
      }
    });
  }, 300);



  $('label.placeholder').on("click", function(){
    var l = this;
    if(!$(l).hasClass("active")){
      $(this).find('span').stop().animate({"top" : "3px", "font-size" : "12px"}, function(){
        $(l).addClass("active");
      });
    }
  });

  //This may not be the most efficient way of doing this, but it works well
  if($('label.placeholder input, label.placeholder textarea').length > 0){
    setInterval(function(){
      $('label.placeholder input, label.placeholder textarea').each(function(){
        var p = $(this).parent();
        if($(this).val() != ""){
          $(p).find('span').stop().css({"top" : "3px", "font-size" : "12px"});
          $(p).addClass("active");
        } else{
          if($(this).val() == "" && document.activeElement != this && $(p).hasClass("active") && !$(p).hasClass("transitioning")){
            //The transition class is used to stop constant polling whilst transitioning
            $(p).addClass("transitioning");
            $(p).find('span').stop().animate({"top" : "11px", "font-size" : "20px"}, function(){
              $(p).removeClass("active").removeClass("transitioning");
            });
          }
        }
      });
    }, 100);
  }

  $(".progress_bar").each(function() {
    var percent = $(this).attr("percent");
    if($(this).hasClass("negative")){
      if(percent > 50){
        $(this).addClass("okay");
      }
      if(percent > 60){
        $(this).addClass("bad");
      }

    } else{
      if(percent > 50){
        $(this).addClass("okay");
      }
      if(percent > 60){
        $(this).addClass("good");
      }

    }

    $(this).find(".value").each(function(){
      $(this).find(".background").each(function(){
        var bg = this;
        setTimeout(function(){
          $(bg).animate({"opacity": 1}, 1000);
        }, 1000);
      });
      $(this).animate({"width": percent + "%", "opacity": 1}, 2000);
    });


  });

  setTime();
  var wait = (60 - date.getSeconds()) * 1000;
  setTimeout(function() {
    setTime();
    setInterval(function() {
      setTime();
    }, 60000);
  }, wait);

  $('.bound').on("keyup", function() {
    $($(this).attr("data-binding")).html($(this).html());
  });

  Dash.KeyBinder.Observe('html');
  Dash.KeyBinder.Bind('html', [17, 192], function() {
    $("#container").toggleClass('console_out');
    $("#console_input").focus();
  });

  Dash.KeyBinder.Observe('#console_input');
  Dash.KeyBinder.Bind("#console_input", [13], function() {
    var html = $('#console_input').text();
    var result = Dash.Console.Execute(html);
    $('#console_result').html("&lt; " + result + "<br/> &gt; " + html + "<br/>" + $('#console_result').html());
    $('#console_input').text("");
  });

});
//Tabs
$(".tab_container").each(function() {
  var tab_contents = $(this).find(".tab_content");
  var tabs = $(this).find('ul.tabs li');

  tab_contents.hide().eq(0).show();

  $(tabs).on("click", function(e) {
    e.preventDefault();

    $(tabs).removeClass("active");
    var href = $(this).find('a').attr('href');
    if (typeof href !== typeof undefined && href !== false) {
      window.location = href;
    } else {
      var tab = $(tab_contents).hide().eq($(this).index());
      var ajax = $(tab).attr('data-ajax-tab');
      if (typeof ajax !== typeof undefined && ajax !== false && $(tab).attr("data-ajax-loaded") !== true) {
        $(tab).load(ajax, function() {
          $(tab).attr("data-ajax-loaded", true);
        });
      }
      $(this).addClass("active");
      $(tab).show();
    }
  }).eq(0).addClass("active").show();
});
$("#menu_bar li.title").on("click", function() {
  var p = $(this).parent();
  $(p).toggleClass("open");
  $(p).find(".items").slideToggle();

  /*if ($(p).hasClass("open")) {
    $(p).removeClass("open");
    $(p).animate({
      "height": "38px"
    }, function() {
      $(p).removeClass("open");
    });
  } else {
    $(p).addClass("opening");
    var nHeight = (($(p).children().length - 1) * 30) + 39;
    $(p).animate({
      "height": nHeight + "px"
    }, function() {
      $(p).removeClass("opening").addClass("open");
    });
  }*/
});

$(document).on("ready", function() {

  //Bind dialog clicks to document click event
  $(document).on("click", "#dialog_close, #dialog .close", function() {
    var btn = $(this);
    $("#dialog").animate({
      "top": "-100px",
      "opacity": "0"
    }, function() {
      $("#dialog_wrapper").css({
        "z-index": "-1"
      });
      $("#container").removeClass("show_dialog");
      $("body").removeClass("dialog_shown");
      if (Dash.DialogCallback !== null && Dash.DialogCallback instanceof Function) {
        Dash.DialogCallback(btn);
        Dash.DialogCallback = null;
      }
    });
  });
  $('.pill select').parent().addClass('select');

});

$("#main_menu_button").on("click", function() {
  if (!($('html').hasClass("menu_out") && $('html').hasClass("opening_menu"))) {
    if (!$('html').hasClass("menu_out")) {
      $('html').addClass("opening_menu");
      $("#menu_bar").animate({
        "left": "0"
      }, function() {
        $('html').addClass("menu_out");
        $('html').removeClass("opening_menu");
      });
    } else {
      $("#menu_bar").animate({
        "left": "-100%"
      }, function() {
        $('html').removeClass("menu_out");
      });
    }
  }
});

function submitAjax(form, action){
  Dash.showAjaxLoader();
  tinyMCE.triggerSave(true, true);
  $.ajax({
    url : action,
    method : "post",
    data : $(form).serialize(),
    success : function(d){
      console.log(d);
      Dash.hideAjaxLoader();
      var data = $.parseJSON(d);
      if(data.response == 1){
        Dash.showAlert("Success", data.message, function(){
          window.location = data.location;
        });
      } else{
        Dash.showAlert("Failure", data.message, function(){

        });
      }

    }
  });
}

$("form.ajax").each(function(){
  var form = $(this);

  var action = $(this).attr("action") + "?response=json";
  $(form).find("button[type=submit]").on("click", function(e){
    e.preventDefault();
    var goAhead = true;
    if($(this).hasClass("confirm_btn")){
      Dash.showConfirm("Confirm", $(this).attr("data-confirmation"), function(e){
        if(e == true){
          submitAjax(form, action);
        }        
      });
    } else{
      submitAjax(form, action);
    }
  });
});
