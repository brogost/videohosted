var MOTIONDETECT_AREA = function( str_videoinindex ){
    var MotionClass = {};
    MotionClass.options = {
    	g_zIndex: 0,
    	mouseDownFlag: false,
    	firstLeft: 0,
    	firstTop: 0,
    	senOutFlag: true,
    	thrOutFlag: true,
    	senVar: false,
    	thrVar: false,
    	senOffsetX: 0,
    	senOffsetX: 0,
    	thrOffsetX: 0,
    	minSenLen: 0,
    	maxSenLen: 0,
    	oldMinSenLen: 0,
    	oldMaxSenLen: 0,
    	senDiffer: 0,
    	thrDiffer: 0,
    	senFlag: false,
    	thrFlag: false,
    	isChangeFlag: false,
    	isApplyFlag: false,
    	divIDArray: new Array(),
    	divIDSenThrArray: new Array(),
    	callCnt: 0,
    	videoinindex: str_videoinindex,
    	isEnableDraw: false,
    	serverIp: "127.0.0.1",
    	httpPort: "9090",
    	spin_opts: {
	  		lines: 13, // The number of lines to draw
	  		length: 20, // The length of each line
	  		width: 10, // The line thickness
	  		radius: 30, // The radius of the inner circle
	  		corners: 1, // Corner roundness (0..1)
	  		rotate: 0, // The rotation offset
	  		direction: 1, // 1: clockwise, -1: counterclockwise
	  		color: '#333', // #rgb or #rrggbb or array of colors
	  		speed: 1, // Rounds per second
	  		trail: 60, // Afterglow percentage
	  		shadow: false, // Whether to render a shadow
	  		hwaccel: false, // Whether to use hardware acceleration
	  		className: 'spinner', // The CSS class to assign to the spinner
	  		zIndex: 2e9, // The z-index (defaults to 2000000000)
	  		top: '50%', // Top position relative to parent in px
	  		left:'50%' // Left position relative to parent in px
  		},
  		obj_spinner: null,
  		spinner_target: null,
  		str_baseurl: "",
  		threadFramerate: null,
  		isRunning: false,
  		ptr_arealist: new Array()
    };
    
    MotionClass.showSpinBar = function () {
    	MotionClass.options.spinner_target = document.getElementById('searching_spinner_center');
    	if ( MotionClass.options.obj_spinner == null)
    		MotionClass.options.obj_spinnerspinner = new Spinner( MotionClass.options.spin_opts ).spin( MotionClass.options.spinner_target );
    	else
    		MotionClass.options.style = "block";
    }
    
    MotionClass.setBaseUrl = function ( s_baseurl ) {
    	MotionClass.options.str_baseurl = s_baseurl;
    }
    
    MotionClass.stopSpinBar = function () {
    	if (MotionClass.options.obj_spinner) {
    		// MotionClass.options.obj_spinner.stop();
    		
    	}
    }
    
    MotionClass.retrieveMotionRegionInfo = function () {
    	$(".motionDiv").remove();
    	$("#areaList").find(".arealist").remove();
    	MotionClass.options.ptr_arealist = [];
    	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.retrieveMotionRegionInfo.nsf";
    	$.ajax({
    		url: str_url,
    		data: {videoinindex: MotionClass.options.videoinindex},
    		success: function ( result ) {
    			var iLength = result.regionInfo.isdefault.length;
    			for ( var i = 0; i < iLength; i ++ ) {
    				if ( i == 0 ) continue;
    				
    				MotionClass.options.ptr_arealist.push({
    					left: result.regionInfo.rectleft[i],
    					right: result.regionInfo.rectright[i],
    					top: result.regionInfo.recttop[i],
    					bottom: result.regionInfo.rectbottom[i],
    					name: result.regionInfo.rectname[i],
    					isdefault: result.regionInfo.isdefault[i],
    					updated: "0",
    					added: "0",
    					index: (i - 1) + "",
    					sensitivity: result.regionInfo.sensitivity[i],
    					threshold: result.regionInfo.threshold[i]
    				});
    				
    				var oDiv = document.createElement("div");
    		     	$(oDiv).attr("order", i * 1 - 1);
    		     	var strStyle = "z-index:" + (i-1);
    		     	strStyle += "; left: " + (MotionClass.getPageOffsetLeft(document.getElementById("activeXTable")) + result.regionInfo.rectleft[i]*5) + ";top:"+(MotionClass.getPageOffsetTop(document.getElementById("mDrawContainer")) + result.regionInfo.recttop[i]*2.71)+";";
    		     	strStyle += "width: " + ((result.regionInfo.rectright[i] - result.regionInfo.rectleft[i]) * 5) + "px;";
    		     	strStyle += "height: " + ((result.regionInfo.rectbottom[i] - result.regionInfo.recttop[i]) * 2.71) + "px;";
    		     	$(oDiv).attr("style", strStyle);
    		     	
    		     	$(oDiv).addClass("motionDiv");
    		     	
    		     	$(oDiv).attr('sensitivity', result.regionInfo.sensitivity[i]);
    		     	$(oDiv).attr('threshold', result.regionInfo.threshold[i]);
    		     	
    		     	$("#mDrawContainer").append(oDiv);
    		     	
    		     	var newArea = result.regionInfo.rectname[i];
    		     	//$("#areaList").removeClass('active');
    		     	$("#areaList").append("<div class='list-group-item arealist'>" + newArea + "</div>");
    	     		var oAdded = $("#areaList").find(".arealist").last();
    	     		//oAdded.addClass('active');
    	     		oAdded.attr("order", (i-1));
    	     		oAdded.attr( "onclick", "motionDetect.onAreaTrClick( this )" );
    			}
    			MotionClass.options.g_zIndex = iLength - 1;
    		}, 
    		error: function( err ) {
    		} 
    	});
    }
    
    MotionClass.loadAllMotionAreas = function () {
    	
    }
    
    MotionClass.setMotionAreaIndex = function () {
    	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.setMotionIndex.nsf";
    }
    
    MotionClass.addMotionRegionInfo = function () {
    	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.addMotionRegionInfo.nsf";
    }
    
    MotionClass.updateSensitivity = function () {
    	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.updateSensitivity.nsf";
    	$.ajax({
    		url: str_url,
    		data: {videoinindex: MotionClass.options.videoinindex, motionindex: -1, sensitivity: $("#mSensitivity").jqxSlider('getValue'), threshold: $("#mThreshold").jqxSlider('getValue')},
    		success: function ( result ) {
    		}
    	});
    }
    
    MotionClass.getFrameRateInfo = function () {
    	var motionIndex = -1;
    	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.getFrameRate.nsf";
    	if ( MotionClass.options.isRunning == true) {
    		motionIndex = $(".motionDiv").index($(".motionDiv.active"));
    		if ( motionIndex > -1 ) {
    			$.ajax({
    				url: str_url,
    				data: {videoinindex: MotionClass.options.videoinindex, motionindex: motionIndex},
    				success: function ( result ) {
    					if ( result.success == 'success' ) {
    						$("#mProgressBar").css("width", result.pos + "%");
    					}
    				}
    			});
    		}
    	}
    }
    
    MotionClass.setHttpPort = function ( str_webport ) {
    	MotionClass.options.httpPort = str_webport;
    }
    MotionClass.setServerIP = function ( str_serverip ) {
    	MotionClass.options.serverIp = str_serverip;
    }
    
    MotionClass.errorloadingVideo = function () {
    	document.getElementById("VLCManager").src = MotionClass.options.str_baseurl + "assets/images/blank.png";
    	MotionClass.showMainForm( true );
    }
    
    MotionClass.onloadVideo = function () {
    	MotionClass.showMainForm( true );
    }
    
    MotionClass.refreshPlayer = function () {
   		document.getElementById("VLCManager").src = "http://" + MotionClass.options.serverIp +":9090/"+ MotionClass.options.videoinindex +"/video.cgi";
    }
    
    MotionClass.showMainForm = function ( bShowFlag ) {
    	if ( bShowFlag == true ) {    		
    		$("#motionSpinner").hide();
    		$(".motionForm").show();
    		MotionClass.options.isRunning = true;
    	} else {
    		$(".motionForm").hide();
    		$("#motionSpinner").show();
    		MotionClass.options.isRunning = false;
    	} 
    		
    }
    
    MotionClass.unload = function () {
    	document.getElementById("VLCManager").src = MotionClass.options.str_baseurl + "assets/images/blank.png";
    	MotionClass.options.isRunning = false;
    	clearInterval( MotionClass.options.threadFramerate );
    }
    
    MotionClass.init = function (  ) {
    	// MotionClass.options.videoinindex = str_videoinindex;
    	// MotionClass.stopSpinBar();
    	MotionClass.showMainForm( false );
    	MotionClass.showSpinBar();
    	MotionClass.refreshPlayer();
    	MotionClass.retrieveMotionRegionInfo();
    	MotionClass.options.threadFramerate = window.setInterval(MotionClass.getFrameRateInfo, 1000);
    }
    
    MotionClass.setDraw = function ( bDrawFlag ) {
    	MotionClass.options.isEnableDraw = bDrawFlag;
    }
    
    // Functions
    MotionClass.getPageOffsetLeft = function( obj ) {
    	if ( obj == null )
        	return 0;
	  	var x;
	  	// Return the x coordinate of an element relative to the page.
	  	x = obj.offsetLeft;
	  
	  	if (obj.offsetParent != null)
	    	x += MotionClass.getPageOffsetLeft(obj.offsetParent);
	  	return x;
    };
    
    MotionClass.getPageOffsetTop = function ( obj ) {
    	if ( obj == null )
        	return 0;
	  	var y;
	  	// Return the x coordinate of an element relative to the page.
	  	y = obj.offsetTop;
	  
	  	if (obj.offsetParent != null)
	  	y += MotionClass.getPageOffsetTop(obj.offsetParent);
	  	return y;
    };
    
    MotionClass.fn_GetDivByZIndex = function ( str_zindex ) {
    	var ret_obj = null;
 	 	$(".motionDiv").each(function() {
 	 	 	if ($(this).attr('order') == (str_zindex + "")) {
 	 	 	 	ret_obj = this;
 	 	 	}
 	 	});
 	 	return ret_obj;
    };
    
    MotionClass.onPaintRect = function () {
    	if(MotionClass.options.mouseDownFlag == false)
     		return;

     	var currDiv = MotionClass.fn_GetDivByZIndex( MotionClass.options.g_zIndex );// document.getElementById(g_zIndex.toString());

     	if(window.event.clientX - MotionClass.options.firstLeft >= 0) 
     	{
     		currDiv.style.width = window.event.clientX - MotionClass.options.firstLeft;
     	}
     	if(window.event.clientY - MotionClass.options.firstTop >= 0)
     	{
     		currDiv.style.height = window.event.clientY - MotionClass.options.firstTop;
     	}

     	if(window.event.clientX - MotionClass.options.firstLeft < 0)
     	{
     		currDiv.style.left = window.event.clientX - MotionClass.getPageOffsetLeft($('#activeXTable').get(0));
     		currDiv.style.width = MotionClass.options.firstLeft - window.event.clientX;
     	}
     	if(window.event.clientY - MotionClass.options.firstTop < 0)
     	{
     		currDiv.style.top = window.event.clientY - MotionClass.getPageOffsetTop($('#activeXTable').get(0));
     		currDiv.style.height = MotionClass.options.firstTop - window.event.clientY;
     	}
    };
    
    MotionClass.onPaintRectStart = function () {
    	var currDownPosDivExist = false;
     	var currDownPosExistDivs = new Array();
     	var str_selectedDiv = -1;
     	$(".motionDiv").each( function() {
         	var int_left = parseInt(this.style.left.split("px")[0])*1 + MotionClass.getPageOffsetLeft($('#activeXTable').get(0));
         	var int_top = parseInt(this.style.top.split("px")[0])*1 + MotionClass.getPageOffsetTop($('#activeXTable').get(0));
     		if ( window.event.clientX > parseInt(int_left) 
     				&& window.event.clientX < int_left + this.offsetWidth
     				&& window.event.clientY > int_top 
     				&& window.event.clientY < int_top + this.offsetHeight )
     		{
     			currDownPosDivExist = true;
     			str_selectedDiv = $(this).attr("order");
     		}
     	});
     	
     	if(currDownPosDivExist == true)
     	{
     		$(".motionDiv").removeClass('active');
     		var oSelectedDiv = MotionClass.fn_GetDivByZIndex( str_selectedDiv ); //currDownPosExistDivs[count - 1].toString()
     		$( oSelectedDiv ).addClass( 'active' );
     		var str_order = $( oSelectedDiv ).attr('order');
			for ( var i = 0 ; i < MotionClass.options.ptr_arealist.length; i ++) {
				if (MotionClass.options.ptr_arealist[i].index * 1 == str_order * 1 ) {
					$("#mSensitivity").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].sensitivity * 1);
					$("#mThreshold").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].threshold * 1);
					break;
				} 
			}
     		var areaTable = $("#areaList");
     		
     		areaTable.find(".arealist").removeClass('active');
     		var tmp_index = 0;
     		var selected_motionindex = -1;
     		areaTable.find(".arealist").each(function() {
     			if ($(this).attr("order") == str_selectedDiv) {
     				$(this).addClass('active');
     				selected_motionindex = tmp_index;
     				$("#AreaNameText").val( this.innerHTML );
     				return;
     			}
     			tmp_index ++;
     		});

     		$.ajax({
     			url: "http://" + MotionClass.options.serverIp + ":" + MotionClass.options.httpPort + "/grcenter.device.setMotionIndex.nsf",
     			data: {videoinindex: MotionClass.options.videoinindex, motionindex: selected_motionindex},
     			success: function ( result ) {
     				console.log ( result );
     			}
     		});

     		return;
     	}
     	MotionClass.options.mouseDownFlag = true;
     	var oDiv = document.createElement("div");
     	$(oDiv).attr("order", MotionClass.options.g_zIndex);
     	var strStyle = "z-index:" + MotionClass.options.g_zIndex;
     	strStyle += "; left: " + (window.event.clientX - MotionClass.getPageOffsetLeft(document.getElementById("activeXTable"))) + ";top:"+(window.event.clientY - $("#activeXTable").scrollTop() - MotionClass.getPageOffsetTop(document.getElementById("mDrawContainer")))+";";
     	$(oDiv).attr("style", strStyle);
     	$(oDiv).attr("ry",  window.event.clientY);
     	MotionClass.options.firstLeft = window.event.clientX;
     	MotionClass.options.firstTop = window.event.clientY;
     	$(oDiv).addClass("motionDiv");
     	$("#mDrawContainer").append(oDiv);
    }

    MotionClass.onPaintRectEnd = function() {
    	MotionClass.options.mouseDownFlag = false;
     	var areaTable = $("#areaList");
     	var oLastDiv = MotionClass.fn_GetDivByZIndex( MotionClass.options.g_zIndex ); 
     	if( oLastDiv ) 
     	{
     		var currDivLeft = parseInt(oLastDiv.style.left.split("px")[0])*1 + MotionClass.getPageOffsetLeft($('#activeXTable').get(0));
     		var currDivLeftPlusWidth = currDivLeft + oLastDiv.offsetWidth;
     		var currDivTop = parseInt(oLastDiv.style.top.split("px")[0])*1 + MotionClass.getPageOffsetTop($('#activeXTable').get(0));
     		var currDivTopPlusHeight = currDivTop + oLastDiv.offsetHeight;

     		if(Math.abs(currDivLeftPlusWidth - currDivLeft) <= 15 || Math.abs(currDivTopPlusHeight - currDivTop) <= 15)
     		{
     			var otmpDiv = MotionClass.fn_GetDivByZIndex(MotionClass.options.g_zIndex + "");
     			$(otmpDiv).remove();
     			$(".motionDiv").removeClass('active');
     			var tmp_index = -1;
     			if($(".motionDiv").length > 0)
     			{
     				$(".motionDiv").last().addClass('active');
     				
     				areaTable.find('.arealist').removeClass('active');
     				document.getElementById("AreaNameText").value = areaTable.find('.arealist').last().html();
     				areaTable.find('.arealist').last().addClass('active');
     				var str_order = $(".motionDiv").last().attr('order');
     				for ( var i = 0 ; i < MotionClass.options.ptr_arealist.length; i ++) {
        				if (MotionClass.options.ptr_arealist[i].index * 1 == str_order * 1 ) {
        					$("#mSensitivity").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].sensitivity * 1);
        					$("#mThreshold").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].threshold * 1);
        					break;
        				} 
        			}
     				
     				tmp_index = $(".motionDiv").length - 1;
     			}
     			$.ajax({
         			url: "http://" + MotionClass.options.serverIp + ":" + MotionClass.options.httpPort + "/grcenter.device.setMotionIndex.nsf",
         			data: {videoinindex: MotionClass.options.videoinindex, motionindex: $(".motionDiv").length - 1},
         			success: function ( result ) {
         				console.log ( result );
         			}
         		});
     			return;
     		}
     		else
     		{
     			
     		}
     		$("#areaList").find('.arealist').removeClass('active');

     		var newArea = "New Area" + (MotionClass.options.g_zIndex + 1).toString();
     		var equalValue = false;
     		var strTemp = "";
     		var int_lastindex = 0;
     		$("#areaList").find(".arealist").each(function () {
         		var ptr_tmp = $(this).html().split("New Area");         		
         		if ( ptr_tmp.length > 1 ) {
             		int_lastindex = int_lastindex >= ptr_tmp[1] * 1 ?int_lastindex:ptr_tmp[1] * 1; 
         		}
     		});
     		str_tmp = "New Area" + (int_lastindex*1 + 1);
     		areaTable.append("<div class='list-group-item arealist'>" + str_tmp + "</div>");
     		var oAdded = areaTable.find(".arealist").last();
     		oAdded.addClass('active');
     		oAdded.attr("order", MotionClass.options.g_zIndex);
     		//td.align = "center";
     		document.getElementById("AreaNameText").value = str_tmp;

     		oAdded.attr( "onclick", "motionDetect.onAreaTrClick( this )" );
     		$(".motionDiv").removeClass('active');
     		$(oLastDiv).addClass('active');
     		// divIDArray[ divIDArray.length ] = g_zIndex;
			var senDiffer = 100;
			var thrDiffer = 20;
			// document.getElementById("imgScale").style.left = document.getElementById("imgDisappear").offsetLeft + Math.round(thrDiffer * 350 / 300) - 65;

     		var left = parseInt($(".motionDiv").last().get(0).style.left.split("px")[0]);
     		var top = parseInt($(".motionDiv").last().get(0).style.top.split("px")[0]);
     		var width = $(".motionDiv").last().get(0).offsetWidth;
     		var height = $(".motionDiv").last().get(0).offsetHeight;
     	
     		var rectStr = left + ";" + top + ";" + width + ";" + height;
     		MotionClass.options.ptr_arealist.push({
     			left: Math.round( left / 5 ),
				right: Math.round( width / 5 ) + Math.round(left / 5),
				top: Math.round(top / 2.71),
				bottom: Math.round(height / 2.71) + Math.round(top / 2.71),
				name: str_tmp,
				isdefault: 0,
				updated: "0",
				added: "1",
				sensitivity: 100,
				threshold: 20,
				index: MotionClass.options.g_zIndex
     		});
     		
     		$("#mSensitivity").jqxSlider('setValue', 100);
     		$("#mThreshold").jqxSlider('setValue', 20);
     		$(".motionDiv").last().attr("sensitivity", 100);
     		$(".motionDiv").last().attr("threshold", 20);
     		MotionClass.options.g_zIndex ++;
     		
     		var rectStr = "";
     		$(".motionDiv").each(function () {
     			var left = parseInt($(this).last().get(0).style.left.split("px")[0]);
         		var top = parseInt($(this).last().get(0).style.top.split("px")[0]);
         		var width = $(this).last().get(0).offsetWidth;
         		var height = $(this).last().get(0).offsetHeight;
         		rectStr += left + "," + top + "," + parseInt( width / 5 ) + "," + parseInt(height / 2.71) + "," + $(this).attr('sensitivity') + "," +
         		$(this).attr('threshold') + "," + "-1;";
     		});
     		var str_url = "http://" + MotionClass.options.serverIp + ":" + MotionClass.options.httpPort + "/grcenter.device.addMotionRegionInfo.nsf";
     		$.ajax({
     			url: str_url,
     			data: {videoinindex: MotionClass.options.videoinindex, motionRectLength: $(".motionDiv").length, rectStr: rectStr},
     			success: function ( result ) {
     				console.log ( result );
     			}
     		});
			
     		/* var aJax = new nsf.xSync("securacle.deviceConfiguration.addMotionRect.nsf");
     		aJax.addQuery("videoinindex", videoinindex);
     		aJax.addQuery("motionRectLength", divIDArray.length);
     		aJax.addQuery("rectStr",rectStr);
     		aJax.fire(); */
     	}
     	
     }

    MotionClass.onPaintRectOut = function() {
     	if(event.clientX <= MotionClass.getPageOffsetLeft(document.getElementById("VLCManager")) 
     			|| event.clientX >= MotionClass.getPageOffsetLeft(document.getElementById("VLCManager")) + document.getElementById("VLCManager").offsetWidth
     			|| event.clientY <= MotionClass.getPageOffsetTop(document.getElementById("VLCManager")) 
     			|| event.clientY >= MotionClass.getPageOffsetTop(document.getElementById("VLCManager")) + document.getElementById("VLCManager").offsetHeight)
     	{	
     			
     		MotionClass.onPaintRectEnd();
     	}
     }

    MotionClass.onAreaTrClick = function( obj ) {
    	var areaTable = $("#areaList");
    	areaTable.find(".arealist").removeClass("active");
    	$(obj).addClass('active');
    	
    	var str_order = $(obj).attr("order");
    	$(".motionDiv").removeClass("active");
    	$(".motionDiv").each(function () {
    		if ( $(this).attr('order') == str_order) {
    			$(this).addClass('active');
    			$("#AreaNameText").val(obj.innerHTML);
    			for ( var i = 0 ; i < MotionClass.options.ptr_arealist.length; i ++) {
    				if (MotionClass.options.ptr_arealist[i].index * 1 == str_order * 1 ) {
    					$("#mSensitivity").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].sensitivity * 1);
    					$("#mThreshold").jqxSlider('setValue', MotionClass.options.ptr_arealist[i].threshold * 1);
    					break;
    				} 
    			}
    			return;
    		}
    	});
     	/*var areaTable = document.getElementById("AreaTable");
     	for(i = 0; i < areaTable.rows.length; i++)
     	{
     		areaTable.rows[i].style.backgroundColor = "#ffffff";
     		
     	}*/
     }
    MotionClass.onApply = function () {
     	var rectStr = "";
     	for ( var i = 0; i < MotionClass.options.ptr_arealist.length; i ++) {
     		var tmp_arr = MotionClass.options.ptr_arealist[i];
     		rectStr += tmp_arr.left + "," + tmp_arr.top + "," + tmp_arr.right + "," + tmp_arr.bottom + "," + tmp_arr.sensitivity + "," + tmp_arr.threshold + "," + tmp_arr.name +";";
     	}
     	var sensitivity = $("#mSensitivity").jqxSlider('getValue');
     	var threshold = $("#mThreshold").jqxSlider('getValue');
     	var str_url = "http://" + MotionClass.options.serverIp +":" + MotionClass.options.httpPort + "/grcenter.device.insertMotionRectInfo.nsf";
     	console.log ( rectStr );
     	$.ajax({
     		url: str_url,
     		data: {
     			videoinindex: MotionClass.options.videoinindex,
     			motionRectLength: MotionClass.options.ptr_arealist.length,
     			sensitivity: sensitivity,
     			threshold: threshold,
     			rectStr: rectStr
     		},
     		success: function( result ) {
     			console.log ( result );
     		}
     	});
    }
    
    MotionClass.onDelete = function( obj ) {
    	if ( MotionClass.options.ptr_arealist.length == 0) {
    		return;
    	}
    	$("#areaList").find(".arealist.active").remove();
    	var str_selectedorder = $(".motionDiv.active").attr("order"); 
    	$(".motionDiv.active").remove();
    	$(".motionDiv").last().addClass('active');
    	$("#areaList").find(".arealist").last().addClass('active');
    	
    	var str_order = $(".motionDiv.active").attr("order");
    	$(".motionDiv.active").remove();
    	
    	$("#areaList").find(".arealist.active").remove();
    	rectStr = "";
    	for(var i = MotionClass.options.ptr_arealist.length - 1; i >= 0; i --)
     	{
    		var tmp_arr = MotionClass.options.ptr_arealist[i];
    		if ( str_selectedorder == tmp_arr.index) {
    			MotionClass.options.ptr_arealist.splice( i, 1 );
    			continue;
    		}
     		rectStr += tmp_arr.left + "," + tmp_arr.top + "," + tmp_arr.right + "," + tmp_arr.bottom + "," + tmp_arr.sensitivity + "," + tmp_arr.threshold + ",-1;";
     	}

    	var str_url = "http://" + MotionClass.options.serverIp + ":" + MotionClass.options.httpPort + "/grcenter.device.addMotionRegionInfo.nsf";
 		$.ajax({
 			url: str_url,
 			data: {videoinindex: MotionClass.options.videoinindex, motionRectLength: MotionClass.options.ptr_arealist.length, rectStr: rectStr},
 			success: function ( result ) {
 				console.log ( result );
 			}
 		});
    }
    
    MotionClass.onUpdate = function () {
    	if ( MotionClass.options.ptr_arealist.length == 0 ) {
    		MotionClass.updateSensitivity();
    		return;
    	}
    	if ( $(".motionDiv").index($(".motionDiv.active")) < 0 ) {
    		MotionClass.updateSensitivity();
    		return;
    	}
    	var str_order = $(".motionDiv.active").attr("order");
    	var rectStr = "";
    	console.log ( MotionClass.options.ptr_arealist );
    	for ( var i = 0; i < MotionClass.options.ptr_arealist.length; i ++ ) {
    		if ( MotionClass.options.ptr_arealist[i].index * 1 == str_order * 1 ) {
    			
    			MotionClass.options.ptr_arealist[i].name = $("#AreaNameText").val();
    			$("#areaList").find(".arealist.active").get(0).innerHTML = $("#AreaNameText").val();
    			MotionClass.options.ptr_arealist[i].sensitivity = $("#mSensitivity").jqxSlider("getValue");
    			MotionClass.options.ptr_arealist[i].threshold = $("#mThreshold").jqxSlider("getValue");
    		}
    		var tmp_arr = MotionClass.options.ptr_arealist[i];
     		rectStr += tmp_arr.left + "," + tmp_arr.top + "," + tmp_arr.right + "," + tmp_arr.bottom + "," + tmp_arr.sensitivity + "," + tmp_arr.threshold + ",-1;";
    	}
    	
    	var str_url = "http://" + MotionClass.options.serverIp + ":" + MotionClass.options.httpPort + "/grcenter.device.addMotionRegionInfo.nsf";
 		$.ajax({
 			url: str_url,
 			data: {videoinindex: MotionClass.options.videoinindex, motionRectLength: MotionClass.options.ptr_arealist.length, rectStr: rectStr},
 			success: function ( result ) {
 				console.log ( result );
 			}
 		});
    }

    return MotionClass;
}