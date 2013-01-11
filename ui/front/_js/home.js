/*
 * Date: 2013/01/10 - 4:21 PM
 */
$(document).ready(function(){
	for (var i = 0; i < listRequest.length; i++) listRequest[i].abort();
	listRequest.push($.getJSON("/data/front/test/testing", function (data) {

		console.log(data);
		$("#output").jqotesub($("#template-home"), data['data']);

		//$("a[href='#systemTimers-container']").trigger("click");

	}));
});

