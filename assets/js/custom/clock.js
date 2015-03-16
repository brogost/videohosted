function cf_UpdateClock(objRow1, objRow2) {
	var m_days 		= ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	var m_months 	= ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	
	var o_now 	= new Date();
	var str_day 	= o_now.getDay();
	var str_date 	= o_now.getDate();
	var str_month 	= o_now.getMonth();
	var str_year	= o_now.getFullYear();
	var str_hour 	= o_now.getHours();
	var str_minute 	= o_now.getMinutes();
	var str_second 	= o_now.getSeconds();
	var AMorPM 		= "AM";
	
	if (str_hour >= 12)
		AMorPM = "PM";
	
	if (str_hour > 12) 
		str_hour -= 12;
	
	if (str_date < 10) 
		str_date = "0" + str_date;
	
	if (str_minute < 10) 
		str_minute = "0" + str_minute;
	
	if (str_second < 10)
		str_second = "0" + str_second;
	
	var str_firstRow = m_days[str_day] + "&nbsp;" + str_date + "&nbsp;" + m_months[str_month] + "&nbsp;" + str_year + '';
	var str_secondRow = str_hour + ":" + str_minute + ":" + str_second + "&nbsp;" + AMorPM + '';
	
	document.getElementById(objRow1).innerHTML = str_firstRow;
	document.getElementById(objRow2).innerHTML = str_secondRow;
	
	setTimeout("cf_UpdateClock('" + objRow1 +"','" + objRow2 + "')",
			1000);
}