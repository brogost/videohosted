var LOCATION_STATUS = function( ){
	var locationClass = {};
	locationClass.str_serverip = "";
	locationClass.str_httpport = "7001";
	
	locationClass.ptr_cpuinfos = new Array();
	locationClass.thread_drawCpu = null;


	locationClass.setServerIp = function ( strServerip, strHttpPort ) {
		locationClass.str_serverip = strServerip;
		locationClass.str_httpport = strHttpPort;
	}
	
	locationClass.getSystemStatus = function () {
		var str_url = "http://" + locationClass.str_serverip + ":" + locationClass.str_httpport + "/grcenter.system.getStatus.nsf";
		$.ajax({
			url: str_url,
			success: function ( result ) {
				 $("#cpuinfoDiv").find(".vendor").html(result.vendor);
				 $("#cpuinfoDiv").find(".model").html(result.model + " " + result.mhz);
				 $("#cpuinfoDiv").find(".sysusage").html(result.tsys);
				 $("#cpuinfoDiv").find(".userusage").html(result.tuser);
				 $("#cpuinfoDiv").find(".combind").html(result.tcombind);
				 $("#cpuinfoDiv").find(".idle").html(result.tidle);
				 $("#cpuinfoDiv").find(".wait").html(result.twait);
				 $("#cpuinfoDiv").find(".nice").html(result.tnice);
				 
				 $("#memInfoDiv").find(".total").html( result.memory.total);
				 $("#memInfoDiv").find(".used").html( result.memory.used);
				 $("#memInfoDiv").find(".dtotal").html( result.disktotal);
				 $("#memInfoDiv").find(".dused").html( result.diskused);
			},
			error: function ( err ) {
				
			}
		});
	}
	
	locationClass.init = function () {
		if ( locationClass.thread_drawCpu == null ) {
			locationClass.thread_drawCpu = window.setInterval(locationClass.getSystemStatus, 1000);
		}
	}
	locationClass.unload = function () {
		if ( locationClass.thread_drawCpu != null ) {
			clearInterval ( locationClass.thread_drawCpu );
			locationClass.thread_drawCpu = null;
		}
	}
	
	return locationClass;
}
