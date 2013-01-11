/*
 * FeedEk jQuery RSS/ATOM Feed Plugin
 * http://jquery-plugins.net/FeedEk/FeedEk.html
 * Author : Engin KIZIL
 * http://www.enginkizil.com
 */

(function ($) {
	$.fn.FeedEk = function (opt) {
		var def = {FeedUrl:'', MaxCount:5, ShowDesc:true, ShowPubDate:true, callBack:function(){}};
		if (opt) {$.extend(def, opt)}
		var idd = $(this).attr('id');
		if (def.FeedUrl == null || def.FeedUrl == '') {
			$('#' + idd).empty();
			return
		}
		var pubdt;
		$('#' + idd).empty();
		$.ajax({url:'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=' + def.MaxCount + '&output=json&q=' + encodeURIComponent(def.FeedUrl) + '&callback=?', dataType:'json', success:function (data) {
			$('#' + idd).empty();
			var last_date = "";
			$.each(data.responseData.feed.entries, function (i, entry) {

				var data = $(entry.content).last("<pre>").html();
				data = "<pre>" + data +"</pre>";
				pubdt = new Date(entry.publishedDate);
				pubdt = pubdt.toLocaleDateString()
				if (last_date!= pubdt) {
					$('#' + idd).append('<h3 class="ItemDate">' + pubdt + '</h3>')
				}
				last_date = pubdt;
				if (def.ShowDesc)$('#' + idd).append('<div class="ItemContent">' + data + '</div>')
			});
			def.callBack();
		}})
	}
})(jQuery);