<div class="table_title">
    <h1>MAIN</h1>
</div>
<div class="search_input">
    <lebel for="search_fio">Поиск студента </lebel>
    <input type="text" id="search_fio" />
</div>
<div class="main_form">
    <form name="save2table" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
        <table  class="main_table default_table">
            <tr>
                <th>id</th>
                <th>fio</th>
                <th>dog_num</th>
                <th>Создать уровень</th>
                <!-- <th>Отметить посещение</th> -->
                <th>Принять проплату</th>
                <th>Удалить</th>
            </tr>
            <?php
                foreach($data['main'] as $row) {
                    if(is_array($row)){
                        extract($row);  // id,fio,dog_num,date
                        $fioQuoted ="'".$fio."'";

            ?>
                    <tr class="tr_<?php echo $id; ?>">
                        <td><input type="text" name="id" value="<?php echo $id; ?>" readonly></td>
                        <td><a href='http://test.ru/bs_mvc/person?id=<?php echo $id; ?>' target='_self' class="fio_links"><?php echo $fio; ?></a></td>
                        <td><input type="text" name="dog_num" size="5"  onchange="call2(this.value,<?php echo $id; ?>,'dog_num')" class="id_<?php echo $id; ?>"  value="<?php echo $dog_num; ?>"></td>
                        <td><p class="lgtt" onclick="showDivWrapperOfFormShowGrayBackgroundResetForm();fillInNameAndIdInForm(<?php echo $id.','.$fioQuoted; ?>);">Создать уровень</p></td>
<!--                        <td><p class="take" onclick="take(idPHP)ShowTakeForm();">Принять проплату</p></td>  -->
                        <td><p class="take" onclick="ShowTakeForm(<?php echo $id.','.$fioQuoted; ?>);">Принять проплату</p></td>
                        <td><p class="del" onclick="deleteStudent(<?php echo $id.", '".$fio."'"; ?>)">Удалить</p></td>
                    </tr>
                    <?php }else{echo 'Нет студентов';} ?>
                <?php } ?>
        </table>
    </form>
</div>
<div class="level_person_form">
    <div class="close_cross"></div>
    <form name="level_person_form" action="javascript:void(null);" method="post" onsubmit="createCombinationOrUpdateStartStopDates()" id="level_person_form">
        <div class="item"><label for="id_person">ID:</label> <input id="id_person" class="add_form_input" type="text" name="id_person" readonly></div>
        <div class="item"><label for="fio_person">ФИО:</label> <input id="fio_person" class="add_form_input" type="text" name="fio_person" readonly></div>
        <div class="item"><label for="fio_person">Интенсив:</label> <input id="IntensiveCheckIn" class="add_form_input" type="checkbox" name="IntensiveCheckIn"></div>
        <div class="item teacher_soch"><label for="teacher">Учитель:</label>
            <select name="teacher" id="teacher" class="add_form_select" onchange="get_timetable(this.value)">
                <option value="choose_teacher" selected>Выберите учителя</option>
                <?php
                echo 'eee';
                foreach($data['allTeachers'] as $row){
                    extract($row);  // teacher
                ?>
                    <option value="<?php echo $teacher; ?>"><?php echo $teacher; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="item last"><div class="warning">Даты старт/стоп студента, нельзя изменить, если у студента на данном сочетании есть проплаты или посещеня.</div><input type="submit" class="add_form_btn" value="Сохранить" /></div>
    </form>
</div>
<div class="take_form">
    <div class="close_cross"></div>
    <form name="take_to_bd" action="javascript:void(null);" method="post" onsubmit="saveAmountOfMoney()" id="take_to_bd_form">
        <div class="item"><label for="id_take">ID:</label> <input id="id_take" class="add_form_input" type="text" name="id_take" readonly></div>
        <div class="item"><label for="fio_take">ФИО:</label> <input id="fio_take" class="add_form_input" type="text" name="fio_take" readonly></div>
        <div class="item"><label for="take_person_money">Принять:</label> <input class="add_form_input" type="text" id="take_person_money" name="take_person_money"><cite>Если вносятся копейки - формат ввода 555.55(точка, а не запятая)</cite></div>
        <div class="item last"><input type="submit" class="add_form_btn" value="Сохранить" /></div>
    </form>
</div>
<div class="add_form">
    <div class="close_cross"></div>
    <form name="save2table" action="javascript:void(null);" method="post" onsubmit="add_fn()" id="add_form">
        <div class="item"><label for="fio_add">ФИО:</label> <input id="fio_add" class="add_form_input" type="text" name="fio_add"></div>
        <div class="item last"><input type="submit" class="add_form_btn" value="Сохранить" /></div>
    </form>
</div>

