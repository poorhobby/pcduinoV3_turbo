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
				location.href = "http://192.168.254.136/pcduino/index.php?r=pcduino/Setting";
				
			} else {
				$("#waitTime").html(left - 1);
			}
		},
		1000);
}
