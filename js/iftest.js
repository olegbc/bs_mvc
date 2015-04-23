if(color == 1){
    if(start_date == 0){
        if(stop_date === 0){
            if(before_person_start == 1){
                $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
            }else if(before_person_start == 0){
                if(after_person_stop == 0){
                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark color'>"+match+"</td>");
                }else if(after_person_stop == 1){
                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                }
            }
        }

    }else if(start_date == 1){
        $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_start_mark color'>"+match+"</td>");
    }
}

if(color_freeze == 1){
    if(start_date == 0){
        if(stop_date === 0){
            if(before_person_start == 0){
                $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
                if(after_person_stop == 1){
                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                }
            }
            if(before_person_start == 1){
                $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
            }else if(after_person_stop == 0){
                $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
            }
        }

    }




    else if(start_date == 1){
        $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(q+1)+"' class='payment_mark attendence_mark person_start_mark color_freeze'>"+match+"</td>");
    }
    else if(stop_date == 1){
        $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_stop_mark color_freeze'>"+match+"</td>");
    }
}




else if(color == 0 && start_date == 1){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_start_mark'>"+match+"</td>");
}
else if(color == 0 && stop_date == 1){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_stop_mark'>"+match+"</td>");
}else if(color == 0 && before_person_start == 1){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
}else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
}else if(color == 0 && after_person_stop == 1){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
}else if(color == 0 && after_person_stop == 0){
    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
}

