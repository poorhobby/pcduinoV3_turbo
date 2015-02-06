/**
 * @author Paul
 */

$(function(){
	add_timing_event();
});

var timer;

function add_timing_event()
{
	timer = setInterval(function(){
			var left = eval($("#waitTime").html());
			if (left == 0) {
				//cancel the interval.
				clearInterval(timer);
				// accessing the url.
				location.href = jumpUrl;
				
			} else {
				$("#waitTime").html(left - 1);
			}
		},
		1000);
}
