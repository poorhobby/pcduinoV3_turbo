/**
 * @author Paul
 */

$(function(){
	wan_type_change();
});

function wan_type_change()
{
	var select = $("#PcduinoSetting_wan_type").val();
	if (select == 0)
	{
		// static ip
		console.info("static ip");
		$(".StaticIp").css("display", "block");
		$(".DHCP").css("display", "none");
		$(".PPPoE").css("display", "none");
	} else if (select == 1){
		//dhcp
		console.info("dhcp ip");
		$(".StaticIp").css("display", "none");
		$(".DHCP").css("display", "block");
		$(".PPPoE").css("display", "none");
	} else if (select == 2){
		//pppoe
		console.info("pppoe ip");
		$(".StaticIp").css("display", "none");
		$(".DHCP").css("display", "none");
		$(".PPPoE").css("display", "block");
	}
}
