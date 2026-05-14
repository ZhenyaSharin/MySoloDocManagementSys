<?php 

if (isset($_POST)) {
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    if (action() === true) {
        return addDb($_POST['dbHost'], $_POST['dbPort'], $_POST['dbTitle'], $_POST['dbUser'], $_POST['dbPassword']);
    }
}

function execFromPublic() {   
    exec('cd ../ && php artisan config:cache');
}

function action() {
    try {
        $settings = [];
        if (file_exists('../.env.prototype')) {
            execFromPublic();
            $data = file('../.env.prototype', FILE_IGNORE_NEW_LINES);
            foreach ($data as &$item) {
                if ($item != '') {
                    $arr = explode('=', $item);
                    $newArr = '';
                    if (count($arr) > 2) {
                        $newArr = implode('=', array_slice($arr, 1));
                        $settings[$arr[0]] = $newArr;
                    } else {
                        $settings[$arr[0]] = $arr[1];
                    }
                // } else {

                }
            };
            execFromPublic(); 
        }
        copy('../.env.prototype', '../.env');
        foreach ($settings as $key => &$value) {
            if ($key === 'APP_URL') {
                $value = trim($_POST['sysProtocol']).'://'.trim($_POST['sysHost']);
            };
            if ($key === 'APP_ENV') {
                $value = trim($_POST['sysMode']);
            };
            if ($key === 'DB_CONNECTION') {
                $value = 'pgsql';
            };
            if ($key === 'DB_HOST') {
                $value = $_POST['dbHost'];
            };
            if ($key === 'DB_PORT') {
                $value = $_POST['dbPort'];
            };
            if ($key === 'DB_DATABASE') {
                $value = $_POST['dbTitle'];
            };
            if ($key === 'DB_USERNAME') {
                $value = $_POST['dbUser'];
            };
            if ($key === 'DB_PASSWORD') {
                $value = $_POST['dbPassword'];
            };
            if ($key === 'MAIL_MAILER') {
                $value = 'smtp';
            };
            if ($key === 'MAIL_HOST') {
                $value = $_POST['mailHost'];
            };
            if ($key === 'MAIL_PORT') {
                $value = $_POST['mailPort'];
            };
            if ($key === 'MAIL_USERNAME') {
                $value = $_POST['mailUser'];
            };
            if ($key === 'MAIL_PASSWORD') {
                $value = $_POST['mailPassword'];
            };
            if ($key === 'MAIL_ENCRYPTION') {
                $value = $_POST['mailEncType'];
            };
            if ($key === 'MAIL_FROM_ADDRESS') {
                $value = $_POST['mailEmail'];
            };
            if ($key === 'MAIL_FROM_NAME') {
                $value = '${APP_NAME}';
            };
        }
        $env = '';
        $fp = fopen("../.env", "w");
        foreach ($settings as $key => $value) {
            $env .= $key.'='.$value."\r\n";
        }
        fwrite($fp, $env);
        execFromPublic();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function addDb($host, $port, $name, $user, $password) {
    $sqlNew = "CREATE DATABASE \"$name\" WITH TEMPLATE = template0 ENCODING = 'UTF8';";
    $sql = file_get_contents('Base_empty_noBase.sql');
    try {
        $connectNew = pg_pconnect("host=$host port=$port user=$user password=$password");
        $resultNew = pg_query($connectNew, $sqlNew);
        if ($resultNew) {
            $connect = pg_pconnect("host=$host port=$port dbname=$name user=$user password=$password");
            $result = pg_query($connect, $sql);
            if (!$result) {
                echo json_encode(["error" => 1]);
                // return false;
            }
        }
        echo json_encode(["error" => 0]);
        // return true;
    // } catch (PDOException $e) {
    //     die($e->getMessage());
    } catch (Exception $e) {
        unlink('../.env');
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return json_encode(["error" => 1]);
    }
}