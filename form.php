<?php
$conn_str = "host=127.0.0.1 port=5433 dbname=test1 user=postgres password=pass";
$db = pg_connect($conn_str);

if(!$db){
    $errormessage = pg_last_error();
    echo "Error 0: " . $errormessage;
    exit();
}else {
   // echo 'bd ok';
}

function valid($post) {

    if(!empty($post)) {

        $name = $post['name'];
        $surname = $post['surname'];
        $email = $post['email'];
        $pass = $post['pass'];
        $pass2 = $post['pass2'];
        $age = $post['age'];

        $error_messages = array();

        $min_long = array(
            "email" => 5,
            "pass" => 8,
            "name" => 2,
            "surname" => 2,
            "age" => 1,
        );

        $max_long = array(
            "email" => 30,
            "pass" => 30,
            "name" => 30,
            "surname" => 30,
            "age" => 3,
        );


        if(!empty($name) && !empty($surname) && !empty($email) && !empty($pass) && !empty($pass2) && !empty($age)) {

            if(preg_match('/[0-9]+/i', $name)) {
                array_push($error_messages, "Поле Имя не должно содержать цифры !");
            }
            if(strlen($name) < $min_long["name"]) {
                array_push($error_messages, "Поле Имя слишком короткий !");
            }
            if(strlen($name) > $max_long["name"]) {
                array_push($error_messages, "Поле Имя слишком длинное !");
            }


            if(preg_match('/[0-9]+/i', $surname)){
                array_push($error_messages, "Поле Фамилия не должно содержать цифры !");
            }
            if(strlen($surname) < $min_long["surname"]) {
                array_push($error_messages, "Поле Фамилия слишком короткий !");
            }
            if(strlen($surname) > $max_long["surname"]) {
                array_push($error_messages, "Поле Фамилия слишком длинное !");
            }


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($error_messages, "Email указан корректно !");
            }
            if(strlen($email) < $min_long["email"]) {
                array_push($error_messages, "Поле Email слишком короткий !");
            }
            if(strlen($email) > $max_long["email"]) {
                array_push($error_messages, "Поле Email слишком длинное !");
            }


            if(!preg_match('/[0-9]+/i', $age)){
                array_push($error_messages, "Поле Возраст не должно содержать буквы !");
            }
            if(strlen($age) < $min_long["age"]) {
                array_push($error_messages, "Поле Возраст слишком короткий !");
            }
            if(strlen($age) > $max_long["age"]) {
                array_push($error_messages, "Поле Возраст слишком длинное !");
            }



            if(strlen($pass) < $min_long["pass"]) {
                array_push($error_messages, "Поле Пароль слишком короткий !");
            }
            if(strlen($pass2) < $min_long["pass"]) {
                array_push($error_messages, "Поле Повторный пароль слишком короткий !");
            }
            if(strlen($pass) > $max_long["pass"]) {
                array_push($error_messages, "Поле Пароль слишком длинное !");
            }
            if(strlen($pass2) > $max_long["pass"]) {
                array_push($error_messages, "Поле Повторный пароль слишком длинное !");
            }

            if($pass < $pass2) {
                array_push($error_messages, "Пароли не совпадают !");
            }
            echo "ok";
            $hash = md5($pass);
            $query = "INSERT INTO users (name, last_name, email, work_id, age, pass) VALUES ($name, $surname, $email, 1, $age, $hash)";
            $result = pg_query($db, $query) or die('Ошибка запроса: ' . pg_last_error());

            if(!empty($error_messages)){ ?>
                <div class="output"><?
                    foreach ($error_messages as $element) {?>
                        <div class="error">
                            <? echo $element; ?>
                        </div>
                    <?}?>
                </div>
                <?
            }else {?>

                <div class="output">
                    <h4>Вы успешно Зарегистрировались</h4>
                    <div class="info"><span>Ваше имя:</span> <? echo $name; ?></div>
                    <div class="info"><span>Ваша фамилия:</span> <? echo $surname; ?></div>
                    <div class="info"><span>Ваш Email:</span> <? echo $email; ?></div>
                    <div class="info"><span>Ваш возраст:</span> <? echo $age; ?></div>
                    <div class="info"><span>Ваш пароль:</span> <? echo $pass; ?></div>
                </div>
                <?php
            }
        }else { ?>
            <div class="output">
                <div class="warning">Заполнение всех полей обязательно!</div>
            </div>
        <?php }
   }
}
pg_close($db);
 ?>


