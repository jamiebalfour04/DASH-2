!function(a){a.fn.extend({BalfPick:function(t){t=a.extend({},t);return this.each(function(){a(this).wrap('<span class="balfpick">');var t=a(this),n=a(this).parent();a(n).append('<span class="hidden_text"></span>'),a(n).append('<div class="bp_wrapper"><span class="text"></span><div class="arrow"></div></div>'),a(n).find(".text").html(a(t).find("option:selected").text()),a(t).on("change",function(){a(n).find(".text").html(a(t).find("option:selected").text())});var e=0,i="";a(t).find("option").each(function(){var t=a(this).text();t.length>e&&(e=t.length,i=t,0)}),a(n).find(".hidden_text").html(i)})}})}(jQuery);