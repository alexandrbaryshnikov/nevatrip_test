<?php

use \mysql_xdevapi\Exception;
header('Content-Type:text/html;charset=utf-8');
session_start();


require_once 'config.php';


$mysqli = mysqli_connect(HOST, USER, PASS, DB_NAME);

if ($mysqli == false) {
    throw new Exception('Ошибка подключения к базе данных');
}else{
    echo 'Подключение к БД успешно <br>';
}

mysqli_set_charset($mysqli, "utf8");

$table = '';





function createBarcode($mysqli, $table){

    $query = "SELECT barcode FROM $table";
    $barcode = mt_rand(10000000, 99999999);

    $res = mysqli_query($mysqli, $query);

    $arr = mysqli_fetch_all($res, MYSQLI_NUM);

    for($i = 0; $i < count($arr); $i++){

        if($barcode == $arr[$i][0]){

            return createBarcode($mysqli, $table);

        }

    }

    return $barcode;

};

$event_id = '';
$event_date = '';
$ticket_adult_price = '';
$ticket_adult_quantity = '';
$ticket_kid_price = '';
$ticket_kid_quantity = '';



function createOrder($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $mysqli){

    $table = 'orders';

    $barcode = createBarcode($mysqli, $table);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.site.com/book',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'event_id' => $event_id,
            'event_date' => $event_date,
            'ticket_adult_price' => $ticket_adult_price,
            'ticket_adult_quantity' => $ticket_adult_quantity,
            'ticket_kid_price' => $ticket_kid_price,
            'ticket_kid_quantity' => $ticket_kid_quantity,
            'barcode' => $barcode
        ))
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    if($response === true){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.site.com/approve',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($barcode)));

        $response = curl_exec($curl);
        curl_close($curl);

        if($response === true){

            $query = "INSERT INTO $table (
                     event_id,
                     event_date,
                     ticket_adult_price,
                     ticket_adult_quantity,
                     ticket_kid_price,ticket_kid_quantity,barcode                
                     ) VALUES ($event_id, $event_date, $ticket_adult_price,
                               $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode)";

            mysqli_query($mysqli, $query);

        }

    }else{
        createOrder();
    }


}

$ticket_types = ['adult', 'kid', 'prefer', 'group'];

$ticket_type = $ticket_types[2];

function insertColumns($ticket_type, $mysqli, $table){


    $table = 'orders';

    if($ticket_type == 'group'){

        $query = "ALTER TABLE $table ADD COLUMN ticket_group_price VARCHAR(255) AFTER ticket_kid_quantity";

        mysqli_query($mysqli, $query);

        $query = "ALTER TABLE $table ADD COLUMN ticket_group_quantity VARCHAR(255) AFTER ticket_group_price";

        mysqli_query($mysqli, $query);

        echo('Добавление столбцов в базу данных успешно');



    };

    if($ticket_type == 'prefer'){

        $query = "ALTER TABLE $table ADD COLUMN ticket_prefer_price VARCHAR(255) AFTER ticket_kid_quantity";

        mysqli_query($mysqli, $query);

        $query = "ALTER TABLE $table ADD COLUMN ticket_prefer_quantity VARCHAR(255) AFTER ticket_prefer_price";

        mysqli_query($mysqli, $query);

        echo('Добавление столбцов в базу данных успешно');

    };

}


function countTickets($mysqli){

    $query = ('ALTER TABLE orders ADD total_tickets VARCHAR(255) AFTER barcode');

    mysqli_query($mysqli, $query);

    $result = mysqli_query($mysqli, 'UPDATE orders SET total_tickets = coalesce(ticket_adult_quantity, 0) + coalesce(ticket_kid_quantity, 0) + coalesce(ticket_prefer_quantity, 0) + coalesce(ticket_group_quantity, 0)');

    var_dump($result);

};


function createAdultTickets($mysqli){

    $query = "SELECT event_id, ticket_adult_quantity FROM orders WHERE ticket_adult_quantity != 0";
    $res = mysqli_query($mysqli, $query);


    $arr = mysqli_fetch_all($res, MYSQLI_NUM);


    for($i = 0; $i < count($arr); $i++){

        for($k = 0; $k < $arr[$i][1]; $k++){


            $event_id = $arr[$i][0];
            $query = "INSERT INTO tickets (
                     event_id,
                     event_date,
                     ticket_adult_price,
                     ticket_adult_quantity,
                     created)
                     SELECT event_id,
                     event_date,
                     ticket_adult_price,
                    ticket_adult_quantity/ticket_adult_quantity,
                     created                            
                    FROM orders                    
                    WHERE event_id = $event_id";


            mysqli_query($mysqli, $query);

        }

    }


};


function createKidTickets($mysqli){

    $query = "SELECT event_id, ticket_kid_quantity FROM orders WHERE ticket_kid_quantity != 0";
    $res = mysqli_query($mysqli, $query);

    $arr = mysqli_fetch_all($res, MYSQLI_NUM);


    for($i = 0; $i < count($arr); $i++){

        for($k = 0; $k < $arr[$i][1]; $k++){


            $event_id = $arr[$i][0];
            $query = "INSERT INTO tickets (
                     event_id,
                     event_date,
                     ticket_kid_price,
                     ticket_kid_quantity,
                     created)
                     SELECT event_id,
                     event_date,
                     ticket_kid_price,
                    ticket_kid_quantity/ticket_kid_quantity,
                     created                            
                    FROM orders                    
                    WHERE event_id = $event_id";


            mysqli_query($mysqli, $query);

        }

    }
};


function createPreferTickets($mysqli){

    $query = "SELECT event_id, ticket_prefer_quantity FROM orders WHERE ticket_prefer_quantity != 0";
    $res = mysqli_query($mysqli, $query);

    $arr = mysqli_fetch_all($res, MYSQLI_NUM);


    for($i = 0; $i < count($arr); $i++){

        for($k = 0; $k < $arr[$i][1]; $k++){


            $event_id = $arr[$i][0];
            $query = "INSERT INTO tickets (
                     event_id,
                     event_date,
                     ticket_prefer_price,
                     ticket_prefer_quantity,
                     created)
                     SELECT event_id,
                     event_date,
                     ticket_prefer_price,
                    ticket_prefer_quantity/ticket_prefer_quantity,
                     created                            
                    FROM orders                    
                    WHERE event_id = $event_id";


            mysqli_query($mysqli, $query);

        }

    }

}


function createGroupTickets($mysqli){

    $query = "SELECT event_id, ticket_group_quantity FROM orders WHERE ticket_group_quantity != 0";
    $res = mysqli_query($mysqli, $query);

    $arr = mysqli_fetch_all($res, MYSQLI_NUM);


    for($i = 0; $i < count($arr); $i++){

        for($k = 0; $k < $arr[$i][1]; $k++){


            $event_id = $arr[$i][0];
            $query = "INSERT INTO tickets (
                     event_id,
                     event_date,
                     ticket_group_price,
                     ticket_group_quantity,
                     created)
                     SELECT event_id,
                     event_date,
                     ticket_group_price,
                    ticket_group_quantity/ticket_group_quantity,
                     created                            
                    FROM orders                    
                    WHERE event_id = $event_id";


            mysqli_query($mysqli, $query);

        }

    }

}

function createTicketsList($mysqli){

    $table = 'tickets';

    createAdultTickets($mysqli);
    createKidTickets($mysqli);
    createPreferTickets($mysqli);
    createGroupTickets($mysqli);

    $query = "SELECT id FROM $table";
    $res = mysqli_query($mysqli, $query);

    $arr = mysqli_fetch_all($res, MYSQLI_NUM);



    for($i = 0; $i < count($arr); $i++){

        $barcode = createBarcode($mysqli, $table);
        $id = $arr[$i][0];
        $query = "UPDATE $table SET barcode=$barcode WHERE id=$id";
        mysqli_query($mysqli, $query);
        echo ('<br>' . $barcode);

    };

}
createTicketsList($mysqli);









