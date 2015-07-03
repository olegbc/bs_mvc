$(document).ready(function(){
	building_blocks();

	$('.combination_all_bad_days .peresent_combinations .brick,.combination_all_bad_days .future_combinations .brick').click(function(){
		$('.brick').css('borderColor','#000');
		var teacher = $(this).children("input[name='teacher_choose']").val();
		var timetable = $(this).children("input[name='timetable_choose']").val();
		var level_start = $(this).children("input[name='level_start_choose']").val();
		findComboDays(teacher,timetable,level_start);
		$(this).css('borderColor','rgb(0, 0, 255)');
	});

	var teacher = $('.peresent_combinations .brick:first').children('[name=teacher_choose]').val();
	var timetable = $('.peresent_combinations .brick:first').children('[name=timetable_choose]').val();
	var level_start = $('.peresent_combinations .brick:first').children('[name=level_start_choose]').val();
	$('.brick').css('borderColor','#000');
	findComboDays(teacher,timetable,level_start);
	$('.peresent_combinations .brick:first').css('borderColor','rgb(0, 0, 255)');

	Date.prototype.mGetDay = function() {
		return (this.getDay() + 6) %7;
	}
	var d = new Date();
	var dayday = d.mGetDay();
	var month_now = d.getMonth();
	var year_now = d.getFullYear();
	var shift = 0;
	$('.left_arrow').click(function(){
		shift--;
		$('.brick').each(function(){
			if($(this).css('borderColor')=='rgb(0, 0, 255)' ){				
				var teacher = $(this).children("input[name='teacher_choose']").val();
				var timetable = $(this).children("input[name='timetable_choose']").val();
				var level_start = $(this).children("input[name='level_start_choose']").val();
				findComboDays(teacher,timetable,level_start,shift);
			}
		}); 
	});
	$('.right_arrow').click(function(){
		shift++;
		$('.brick').each(function(){
			if($(this).css('borderColor')=='rgb(0, 0, 255)' ){				
				var teacher = $(this).children("input[name='teacher_choose']").val();
				var timetable = $(this).children("input[name='timetable_choose']").val();
				var level_start = $(this).children("input[name='level_start_choose']").val();
				findComboDays(teacher,timetable,level_start,shift);
			}
		}); 
	});
	
	if(getParameterByName('teacher') && getParameterByName('timetable') && getParameterByName('level_start')){
		$('.brick input').each(function(){
			if($(this).val()==getParameterByName('teacher')){
				$('.brick').css('borderColor','#000');
				var teacher = $(this).parent('.brick').children("input[name='teacher_choose']").val();
				var timetable = $(this).parent('.brick').children("input[name='timetable_choose']").val();
				var level_start = $(this).parent('.brick').children("input[name='level_start_choose']").val();
				findComboDays(teacher,timetable,level_start);
				$(this).parent('.brick').css('borderColor','rgb(0, 0, 255)');
			}
		});
	}


});

////////////    /ready    /////////////////

function findComboDays(teacher,timetable,level_start,shift){
	shift = shift || 0;
	$.ajax({
		type: 'POST',
		url: './BadDays/CombinationAndBadDaysDates',
		dataType: 'json',
		data: {teacher:teacher,timetable:timetable,level_start:level_start},
        success: function(data) {
            //console.log(data);
            var i=0;
            var yearMonthDay = [];
            for(i=0;i<21;i++){
                yearMonthDay[i] = data['combinationDates'][0][i].split('-');
            }
            var badDayDates = [];
            for(i=0;i<data['BadDaysOfCombination'].length;i++){
                badDayDates[i] = data['BadDaysOfCombination'][i].split('-');
            }

            monthInput=0;
            monthOutput=0;
            monthInput=parseInt((yearMonthDay[0][1]-1)+shift);
            year=0;
            year=parseInt(yearMonthDay[0][0]);
            indexOfrepeat=0;
            n=0;
            flagOfDecrease=0;
            if(monthInput<0){flagOfDecrease=1;}
            n=Math.abs(parseInt(monthInput/12));
            if(flagOfDecrease==1){
                if(monthInput%12==0 || monthInput==0){
                    monthOutput=0;
                }else{
                    monthOutput=(12*(n+1))+monthInput;
                }
                nYear=Math.abs(parseInt(monthInput/12))+1;
                if(monthInput%12==0 || monthInput==0){
                    nYear=nYear-1;
                }
                console.log("nYear = "+nYear);
                year=parseInt(yearMonthDay[0][0])-nYear;
            }

            if(flagOfDecrease==0){
                if(monthInput>=parseInt(12*n) && monthInput<=parseInt(12*(n+1))){
                    indexOfrepeat=n;
                    if(n>=1){
                        monthOutput=parseInt(monthInput-(12*indexOfrepeat));
                    }else{
                        monthOutput=monthInput-(11*indexOfrepeat);
                    }
                    year=parseInt(yearMonthDay[0][0])+n;
                }
            }
            displayCalendarTable(monthOutput,year,yearMonthDay,teacher,timetable,level_start,badDayDates);
        },
        error:  function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
	});
}

function markAttenedDays(teacher,timetable,level_start){
    $.ajax({
        type: 'POST',
        url: './BadDays/AttenedDates',
        async: 'false',
        dataType: 'json',
        data: {teacher:teacher,timetable:timetable,level_start:level_start},
        success: function(data){
            var AttenedYearMounthDay = new Array();
            var month_arr = new Array();
            month_arr[0] = "January";
            month_arr[1] = "February";
            month_arr[2] = "March";
            month_arr[3] = "April";
            month_arr[4] = "May";
            month_arr[5] = "June";
            month_arr[6] = "July";
            month_arr[7] = "August";
            month_arr[8] = "September";
            month_arr[9] = "October";
            month_arr[10] = "November";
            month_arr[11] = "December";

            for(i in data){
                AttenedYearMounthDay[i] = data[i].split('-');

                $('.bad_days_calendar').find('.month_year_div').each(function(){
                    if($(this).text() == month_arr[(parseInt(AttenedYearMounthDay[i][1])-1)]+' '+AttenedYearMounthDay[i][0]){
                        //$(this).css('color','red');
                        $(this).parent().find('td.day.combo_day').each(function(){
                            if(parseInt($(this).text()) == parseInt(AttenedYearMounthDay[i][2])){
                                $(this).css({'background':'red','border':'2px solid black','color':'black'}).addClass('attenedDay');
                            }
                        });
                    }
                });
            }
            $('.attenedDay').click(function(){
                alert('Вы не можете установить  bad day в дату с посещением');
            });
            $('.day').not('.attenedDay').click(function(){
                var badDayClicked=$(this).attr('date-ymd');
                if(badDayClicked!=level_start){
                    $.ajax({
                        type: 'POST',
                        url: './BadDays/InsertOrDeleteBadDay',
                        dataType: 'json',
                        data: {badDayClicked:badDayClicked,teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data){
                            $.ajax({
                                type:'POST',
                                url: './LevelCalculation/calculateLevelDates',
                                dataType: 'json',
                                data: {teacher:teacher,timetable:timetable,level_start:level_start},
                                success: function(data){
                                    findComboDays(teacher,timetable,level_start);
                                },
                                error:  function(xhr, str){
                                    alert('Возникла ошибка: ' + xhr.responseCode);
                                }
                            });
                            findComboDays(teacher,timetable,level_start);
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }else{alert('Если дата старта не подходящий день, перенесите дату старта на странице управления attendance_table');}
            });
        },
        error: function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}

function combiOnBadDaysTable(){
	var text= '2014';
	var text2 = $('.month_year_div').text();
	var re=new RegExp (text,"i");
	var matchstr = text2.match(re);

	var month_arr = new Array();
		month_arr[0] = "January";
		month_arr[1] = "February";
		month_arr[2] = "March";
		month_arr[3] = "April";
		month_arr[4] = "May";
		month_arr[5] = "June";
		month_arr[6] = "July";
		month_arr[7] = "August";
		month_arr[8] = "September";
		month_arr[9] = "October";
		month_arr[10] = "November";
		month_arr[11] = "December";
}

function setToday() {
	var now   = new Date();
	var day   = now.getDate();
	var month = now.getMonth();
	var year  = now.getYear();
	if (year < 2000){
		year = year + 1900;
	}
	this.focusDay = day;
	document.calControl.month.selectedIndex = month;
	document.calControl.year.value = year;
	displayCalendar(month, year);
}

function selectDate() {
	var year  = document.calControl.year.value;
	var day   = 0;
	var month = document.calControl.month.selectedIndex;
	displayCalendar(month, year);
}

function setPreviousYear() {
	var year  = document.calControl.year.value;
	// if (isFourDigitYear(year)) {
		var day   = 0;
		var month = document.calControl.month.selectedIndex;
		year--;
		document.calControl.year.value = year;
		displayCalendar(month, year);
	// }
}
function setPreviousMonth() {
	var year  = document.calControl.year.value;
	// if (isFourDigitYear(year)) {
		var day   = 0;
		var month = document.calControl.month.selectedIndex;
		if (month == 0) {
			month = 11;
			if (year > 1000) {
				year--;
				document.calControl.year.value = year;
			}
		}else{
			month--;
	}
	document.calControl.month.selectedIndex = month;
	displayCalendar(month, year);
	// }
}
function setNextMonth() {
	var year  = document.calControl.year.value;
	var day = 0;
	var month = document.calControl.month.selectedIndex;
	if (month == 11) {
		month = 0;
		year++;
		document.calControl.year.value = year;
	}else{
		month++;
	}
	document.calControl.month.selectedIndex = month;
	displayCalendar(month, year);
}

function setNextYear() {
	var year = document.calControl.year.value;
	var day = 0;
	var month = document.calControl.month.selectedIndex;
	year++;
	document.calControl.year.value = year;
	displayCalendar(month, year);
}

function displayCalendarTable(month,year,yearMonthDay,teacher,timetable,level_start,badDayDates){
	month=parseInt(month);
	year=parseInt(year);
	$('.bad_days_calendar_monthes_wrapper').show();
	$('.bad_days_calendar_1 .month_year_div').remove()
	$('.bad_days_calendar_2 .month_year_div').remove()
	$('.bad_days_calendar_3 .month_year_div').remove()
	$('.bad_days_calendar_4 .month_year_div').remove()

	$('.bad_days_calendar_1 table').empty();
	$('.bad_days_calendar_2 table').empty();
	$('.bad_days_calendar_3 table').empty();
	$('.bad_days_calendar_4 table').empty();


//	/построение шапки
	var d = new Date();
	var month_2 = d.getMonth()+1;
	var day = d.getDate();
	var day_of_week = d.getDate();
	var month_arr = new Array();
	month_arr[0] = "January"; month_arr[1] = "February"; month_arr[2] = "March"; month_arr[3] = "April"; month_arr[4] = "May"; month_arr[5] = "June"; month_arr[6] = "July"; month_arr[7] = "August"; month_arr[8] = "September"; month_arr[9] = "October"; month_arr[10] = "November"; month_arr[11] = "December";

	if(month == 0){var month_before = month_arr[month+11];var year_of_month_before =year-1;}else{var month_before = month_arr[month-1];var year_of_month_before =year;}
	var month_now = month_arr[month];var year_of_month_now = year;
	if(month == 11){var month_after = month_arr[month-11];var year_of_month_after =year+1;}else{var month_after = month_arr[month+1];var year_of_month_after =year;}
	if(month == 11){var month_afterafter = month_arr[month-10];var year_of_month_afterafter =year+1;}else if(month+1 == 11){var month_afterafter = month_arr[month-10];var year_of_month_afterafter =year+1;}else{var month_afterafter = month_arr[month+2];var year_of_month_afterafter =year;}
	var year_now = d.getFullYear();

    $('.bad_days_calendar_1').addClass('bad_days_calendar');
    $('.bad_days_calendar_2').addClass('bad_days_calendar');
    $('.bad_days_calendar_3').addClass('bad_days_calendar');
    $('.bad_days_calendar_4').addClass('bad_days_calendar');

	$('.bad_days_calendar_1 table').html('<tbody><tr id="week_days_names"><th class="" id="">Пн</th><th class="" id="">Вт</th><th class="" id="">Ср</th><th class="" id="">Чт</th><th class="" id="">Пц</th><th class="" id="">Сб</th><th class="" id="">Вс</th></tr></tbody>');
	$('.bad_days_calendar_2 table').html('<tbody><tr id="week_days_names"><th class="" id="">Пн</th><th class="" id="">Вт</th><th class="" id="">Ср</th><th class="" id="">Чт</th><th class="" id="">Пц</th><th class="" id="">Сб</th><th class="" id="">Вс</th></tr></tbody>');
	$('.bad_days_calendar_3 table').html('<tbody><tr id="week_days_names"><th class="" id="">Пн</th><th class="" id="">Вт</th><th class="" id="">Ср</th><th class="" id="">Чт</th><th class="" id="">Пц</th><th class="" id="">Сб</th><th class="" id="">Вс</th></tr></tbody>');
	$('.bad_days_calendar_4 table').html('<tbody><tr id="week_days_names"><th class="" id="">Пн</th><th class="" id="">Вт</th><th class="" id="">Ср</th><th class="" id="">Чт</th><th class="" id="">Пц</th><th class="" id="">Сб</th><th class="" id="">Вс</th></tr></tbody>');
	$('.bad_days_calendar_1 table').before('<div class="month_year_div"><tr><td class="month_year">'+month_before+" "+year_of_month_before+'</td></tr></div>');
	$('.bad_days_calendar_2 table').before('<div class="month_year_div"><tr><td class="month_year">'+month_now+" "+year_of_month_now+'</td></tr></div>');
	$('.bad_days_calendar_3 table').before('<div class="month_year_div"><tr><td class="month_year">'+month_after+" "+year_of_month_after+'</td></tr></div>');
	$('.bad_days_calendar_4 table').before('<div class="month_year_div"><tr><td class="month_year">'+month_afterafter+" "+year_of_month_afterafter+'</td></tr></div>');
//	/построение шапки

	for(i=0;i<6;i++){
		$('.bad_days_calendar_1 table tbody').append('<tr class="tr_'+i+'"></tr>');
		$('.bad_days_calendar_2 table tbody').append('<tr class="tr_'+i+'"></tr>');
		$('.bad_days_calendar_3 table tbody').append('<tr class="tr_'+i+'"></tr>');
		$('.bad_days_calendar_4 table tbody').append('<tr class="tr_'+i+'"></tr>');
		for(u=0;u<7;u++){
			$('.bad_days_calendar_1 table tbody .tr_'+i).append('<td class="td_'+u+'">&nbsp;</td>');
			$('.bad_days_calendar_2 table tbody .tr_'+i).append('<td class="td_'+u+'">&nbsp;</td>');
			$('.bad_days_calendar_3 table tbody .tr_'+i).append('<td class="td_'+u+'">&nbsp;</td>');
			$('.bad_days_calendar_4 table tbody .tr_'+i).append('<td class="td_'+u+'">&nbsp;</td>');
		}
	}
	if(month == 0){daysNstartingPos((month+11),(year-1),'bad_days_calendar_1',badDayDates);}else{daysNstartingPos((month-1),year,'bad_days_calendar_1',badDayDates);}
	daysNstartingPos(month,year,'bad_days_calendar_2',badDayDates);
	if(month==11){daysNstartingPos((month-11),(year+1),'bad_days_calendar_3',badDayDates);}else{daysNstartingPos((month+1),year,'bad_days_calendar_3',badDayDates);}
	if(month==11){daysNstartingPos((month-10),(year+1),'bad_days_calendar_4',badDayDates);}else if(month+1==11){daysNstartingPos((month-10),(year+1),'bad_days_calendar_4',badDayDates);}else{daysNstartingPos((month+2),year,'bad_days_calendar_4',badDayDates);}
	betweenComboDays(yearMonthDay);

	function daysNstartingPos(month,year,table,badDayDates){
		// console.log(badDayDates);
		var month_num_now = d.getMonth();
		var year_num_now = d.getFullYear();
		month = parseInt(month);
		year = parseInt(year);
		var days = getDaysInMonth(month+1,year);
		days = parseInt(days);
		var firstOfMonth = new Date (year, month, 1);
		var startingPos = firstOfMonth.mGetDay();
		days += startingPos;
		fillOut(table,startingPos,days,month,year,month_num_now,year_num_now,badDayDates);
	}

	function fillOut(table,startingPos,days,month,year,month_num_now,year_num_now,badDayDates){
		var num_tr=0;
		var value=0;
		var value_hidden=0;
		var month_hidden=0;
		var i = 0;
		if((month+1)<10){month_hidden="0"+(month+1);}else{month_hidden=(month+1);}
		for(i = 0; i <= startingPos; i++) {
			$('.'+table+' table tbody .tr_'+num_tr+' .td_'+i).html('&nbsp;');
		}
		var t=0;
		for(i = startingPos; i < days; i++) {
			value = i-startingPos+1;
			if(value<10){value_hidden="0"+value;}else{value_hidden=value;}
			if((i+1)%7 == 0 ){
				if(value==day && month==month_num_now && year==year_num_now){
					$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).addClass('day_now');
				}
				$('.'+table+' table tbody .tr_'+num_tr+' .td_'+(i-(7*t))).html(value).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('day');
				for(var d=0;d<21;d++){
					if(value==yearMonthDay[d][2] && month+1==yearMonthDay[d][1] && year==yearMonthDay[d][0]){
						$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('combo_day');
					}
				}
				if(badDayDates){
					for(var bd=0;bd<badDayDates.length;bd++){
						if(value==badDayDates[bd][2] && month+1==badDayDates[bd][1] && year==badDayDates[bd][0]){
							$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('bad_day');
						}
					}
				}
				num_tr++;
				t++;
			}else{
				if(value==day && month==month_num_now && year==year_num_now){$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).addClass('day_now');}
				$('.'+table+' table tbody .tr_'+num_tr+' .td_'+(i-(7*t))).html(value).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('day');
				for(var d=0;d<21;d++){
					if(value==yearMonthDay[d][2] && month+1==yearMonthDay[d][1] && year==yearMonthDay[d][0]){$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('combo_day');}
				}
				if(badDayDates){
					for(var bd=0;bd<badDayDates.length;bd++){
						if(value==parseInt(badDayDates[bd][2]) && month+1==parseInt(badDayDates[bd][1]) && year==parseInt(badDayDates[bd][0])){
							$('.'+table+' .tr_'+num_tr+' .td_'+(i-(7*t))).attr({'date-ymd':year+'-'+month_hidden+'-'+value_hidden,'date-year':year,'date-month':month_hidden,'date-day':value_hidden}).addClass('bad_day');
						}
					}
				}
			}
		}
		for (i=days; i<=42; i++)  {
			$('.'+table+' table tbody .tr_'+num_tr+' .td_'+(i-(7*t))).html('&nbsp;');
		}
	}
    markAttenedDays(teacher,timetable,level_start);

    //	/бэд дэй в базу
    //$('.day').not('.attenedDay').click(function(){
    //    //if($(this).hasClass('.attenedDay')){
    //    //    alert('55');
    //    //}
    //    alert('55');
    //    return;
    //    var badDayClicked=$(this).attr('date-ymd');
    //    if(badDayClicked!=level_start){
    //        $.ajax({
    //            type: 'POST',
    //            url: './BadDays/InsertOrDeleteBadDay',
    //            dataType: 'json',
    //            data: {badDayClicked:badDayClicked,teacher:teacher,timetable:timetable,level_start:level_start},
    //            success: function(data){
    //                $.ajax({
    //                    type:'POST',
    //                    url: './LevelCalculation/calculateLevelDates',
    //                    dataType: 'json',
    //                    data: {teacher:teacher,timetable:timetable,level_start:level_start},
    //                    success: function(data){
    //                        findComboDays(teacher,timetable,level_start);
    //                    },
    //                    error:  function(xhr, str){
    //                        alert('Возникла ошибка: ' + xhr.responseCode);
    //                    }
    //                });
    //                findComboDays(teacher,timetable,level_start);
    //            },
    //            error: function(xhr, str){
    //                alert('Возникла ошибка: ' + xhr.responseCode);
    //            }
    //        });
    //    }else{alert('Если дата старта не подходящий день, перенесите дату старта на странице управления attendance_table');}
    //});
    //	/бэд дэй в базу
}


function betweenComboDays(yearMonthDay){
    //console.log( yearMonthDay  );
	for(var y=yearMonthDay[0][0];y<=yearMonthDay[20][0];y++){
		if(y == parseInt(yearMonthDay[0][0])+1){
			for(var m=1;m<=yearMonthDay[20][1];m++){
				if(m<10){m="0"+m;}
				var days = getDaysInMonth(m,y);
				if(m==yearMonthDay[0][1]){
					for(var d=yearMonthDay[0][2];d<=days;d++){
						if(d<10){d="0"+d;}
                        console.log( $('td[date-ymd='+y+'-'+m+'-'+d+']')   );
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
				if(m!=yearMonthDay[0][1] && m!=yearMonthDay[20][1]){
					for(var d=1;d<=days;d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
				if(m==yearMonthDay[20][1]){
					for(var d=1;d<=yearMonthDay[20][2];d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
			}
		}
		if(y == parseInt(yearMonthDay[0][0]) && parseInt(yearMonthDay[0][0])!=parseInt(yearMonthDay[20][0])){
			for(var m=yearMonthDay[0][1];m<=12;m++){
				if(m<10){m="0"+m;}
				var days = getDaysInMonth(m,y);
				if(m==yearMonthDay[0][1]){
					for(var d=yearMonthDay[0][2];d<=days;d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
				if(m!=yearMonthDay[0][1] && m!=yearMonthDay[20][1]){
					for(var d=1;d<=days;d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
				if(m==yearMonthDay[20][1]){
					for(var d=1;d<=yearMonthDay[20][2];d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
			}
		}
		if(parseInt(yearMonthDay[0][0])==parseInt(yearMonthDay[20][0])){
			for(var m=parseInt(yearMonthDay[0][1]);m<=parseInt(yearMonthDay[20][1]);m++){
				if(m<10){m="0"+m;}
				var days = getDaysInMonth(m,y);
				if(m==yearMonthDay[0][1]){
                    var count = 0;
					for(var d=yearMonthDay[0][2];d<=days;d++){
						if(d<10 && count!=0){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
                        count++;
					}
				}
				if(m!=yearMonthDay[0][1] && m!=yearMonthDay[20][1]){
					for(var d=1;d<=days;d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
				if(m==yearMonthDay[20][1]){
					for(var d=1;d<=yearMonthDay[20][2];d++){
						if(d<10){d="0"+d;}
						$('td[date-ymd='+y+'-'+m+'-'+d+']').addClass('betweenComboDays');
					}
				}
			}
		}
	}
}

function getDaysInMonth(month,year){
	var days;
	if (month==1 || month==3 || month==5 || month==7 || month==8 || month==10 || month==12){
		days=31;
	}else if(month==4 || month==6 || month==9 || month==11){
		days=30;
	}else if (month==2){
		if(isLeapYear(year)){
			days=29;
		}else{
			days=28;
		}
	}
	return (days);
}

function isLeapYear (Year){
	if (((Year % 4)==0) && ((Year % 100)!=0) || ((Year % 400)==0)) {
		return (true);
	}else{
		return (false);
	}
}
