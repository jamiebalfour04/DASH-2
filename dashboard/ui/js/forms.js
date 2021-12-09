$(document).on("click", ".prev_part_button", function(e){
  e.preventDefault();
  var form = $(this).parent().parent();
  if($(form).attr("data-prev-part") != undefined){
    var part_prev = $("#" + $(form).attr("data-prev-part"));

    if(part_prev.length > 0){
      $('#main').stop().animate({scrollTop:0}, 200);
      setTimeout(function(){
        $(form).animate({"left" : "100%"}, 1000);
        $(part_prev).animate({"left" : "0"}, 1000);
      }, 200);
    }
  }

});
$(document).on("click", ".next_part_button", function(e){
  e.preventDefault();
  var form = $(this).parent().parent();
  if($(form).attr("data-next-part") != undefined){
    var part_next = $("#" + $(form).attr("data-next-part"));

    if(part_next.length > 0){
      $('#main').stop().animate({scrollTop:0}, 200);
      setTimeout(function(){
        $(form).animate({"left" : "-100%"}, 1000);
        $(part_next).animate({"left" : "0"}, 1000);
      }, 200);
    }
  }

});

$(document).on("click", "#reload_assets", function(e){
  e.preventDefault();
  Dash.showAjaxLoader();
  var postId = $("input[name=post_id]").val();
  //Retrieves asset information from tmp dir and assets folder and updates the select box
  if(postId != undefined){
    var url = Dash.DashboardPath + 'get/assets/' + postId;
  } else{
    var url = Dash.DashboardPath + 'get/assets/';
  }

  $.ajax({
    url : url,
    success : function(d){
      var output = '<option value="">None</option>';
      var j = $.parseJSON(d);
      //Iterate all data
      $(j).each(function(){
        output += '<option value="'+this.path+'">' + this.name + '</option>';

      });

      $("select[name=banner_image]").html(output);
      Dash.hideAjaxLoader();
    }
  });
})
