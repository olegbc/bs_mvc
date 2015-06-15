$(document).ready(function(){
	building_blocks();
    //return;
	var teacher = $('.peresent_combinations .brick:first').children('[name=teacher_choose]').val();
	var timetable = $('.peresent_combinations .brick:first').children('[name=timetable_choose]').val();
	var level_start = $('.peresent_combinations .brick:first').children('[name=level_start_choose]').val();
	$('.peresent_combinations .brick:first').css('borderColor','rgb(0, 0, 255)');
	lgtt_match_fn(teacher,timetable,level_start);
});