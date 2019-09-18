 <?php
require("PHPMailer/PHPMailerAutoload.php");

//helper functions

function destroy_sess()
{
    session_destroy();
}

function last_id()
{
    global $conn;
    return mysqli_insert_id($conn);
}

function deleteSession_ExceptUser()
{
    foreach($_SESSION as $key => $val)
{
    if ($key !== 'useraccount')
    {
      unset($_SESSION[$key]);
    }
}
}

function count_Sessions()
{
    $activesessnum = 0;
    foreach($_SESSION as $key => $val)
    {
        if ($key !== 'useraccount')
        {
            $activesessnum++;
        }
    }
    return $activesessnum;
}

function set_message($msg)
{
    if(!empty($msg))
    {
        $_SESSION['message'] = $msg;
    }else{
        $msg = "";
    }
}

function display_message()
{
    if(isset($_SESSION['message']))
    {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location){

    header("Location: $location ");
}

function query($sql){

    global $conn;
    return mysqli_query($conn, $sql);
}

function confirm($result){
    if(!$result)
    {
        die("QUERY FAILED" . mysqli_error($conn));
    }
}

function escape_string($string){
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

function fetch_array($result)
{
    return mysqli_fetch_array($result);
}


function get_products()
{

    $query = query(" SELECT * FROM products");
    confirm($query);

    while($row = fetch_array($query))
    {
        if($row['product_quantity'] > 0)
        {
        $product = <<<DELIMETER
            <div class="col-sm-4 col-lg-4 col-md-4">
                <div class="thumbnail" style="height:340px">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:165px;" class="imgsize" src="../resources/uploads/{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="" href="../resources/cart.php?add={$row['product_id']}">Add To Cart</a>
                        <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
        }
    }
}


function get_categories_products()
{
    $query = query(" SELECT * FROM products WHERE product_category_id =" . escape_string($_GET['id']) ."");
    confirm($query);

    while($row = fetch_array($query))
    {
        if($row['product_quantity'] > 0)
        {
        $product = <<<DELIMETER
            <div class="col-md-4 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:200px;" class="imgsize" src="../resources/uploads/{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 84px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="" href="../resources/cart.php?add={$row['product_id']}">Add To Cart</a>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
        }
    }
}


function get_categories()
{
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = mysqli_fetch_array($query))
    {
        $categories = <<<DELIMETER
            <a href="category.php?id={$row['cat_id']}" class='list-group-item'>{$row['cat_title']}</a>
        DELIMETER;

        echo $categories;
    }
}


function  get_shop_products()
{

$query = query(" SELECT * FROM products");
   confirm($query);

    while($row = fetch_array($query))
    {
        if($row['product_quantity'] > 0)
        {
        $product = <<<DELIMETER
            <div class="col-md-4 col-sm-6 col-lg-3">
                <div class="thumbnail">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:200px;" class="imgsize" src="../resources/uploads/{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;text-overflow: ellipsis;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <p>
                        <a class="btn btn-primary" target="" href="../resources/cart.php?add={$row['product_id']}">Add To Cart</a>
                        </p>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
        }
    }
}



function redirect_user($username, $password)
{
    $accounquery = query("SELECT * FROM users WHERE username = '{$username}' OR useremail = '{$username}' AND password = '{$password}'");
    confirm($accounquery);

    $accarr = fetch_array($accounquery);


    $arr = array($accarr['firstname'], $accarr['accounttype'],$accarr['username'],$accarr['user_id']);

    $_SESSION['useraccount'] = $arr;


    if($accarr['accounttype'] == 'buyer')
    {
        redirect("user.php?user_id={$accarr['user_id']}&accounttype={$accarr['accounttype']}");
    }
    else if($accarr['accounttype'] == 'seller')
    {
        redirect("admin/index.php?main");// the ? after php for buyer no longer needed
    }else if($accarr['accounttype'] == 'admin')
    {
        redirect("admin/index.php?main");// the ? after php for admin no longer needed
    }
}

function redirect_email()
{
   // $accounquery = query("SELECT accounttype, user_id FROM users WHERE username = '{$username}' AND password = '{$password}'");
  //  confirm($accounquery);

   // $accounttype = fetch_array($accounquery);
        redirect("emailconfirmation.php");
}

function login_user()
{
    if(isset($_POST['submitlogin']))
    {
        $userinput = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

        $query = query("SELECT * FROM users WHERE useremail = '{$userinput}' OR username = '{$userinput}'");
        confirm($query);
        $hashed = fetch_array($query);


        if(mysqli_num_rows($query) == 0 || password_verify($password, $hashed['password']) == 0 )
        {
            set_message("incorrect password or username");
            redirect("login.php");
        }else if(password_verify($password, $hashed['password']))
        {

            redirect_user($userinput, $hashed['password']);
        }
    }
}

function signup_user()
{
    if(isset($_POST['submitsignup']))
    {
        $firstname = escape_string($_POST['firstname']);
        $lastname = escape_string($_POST['lastname']);
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
        $passwordrepeat = escape_string($_POST['password-repeat']);
        $accounttype = escape_string($_POST['accounttype']);

        $checkusers = query("SELECT username FROM users WHERE username = '{$username}' ");
        confirm($checkusers);
        if(mysqli_num_rows($checkusers) == 0)
        {

            if($password == $passwordrepeat)
            {
<<<<<<< HEAD
                $confirmcode = rand();
                $query = query("INSERT INTO users(firstname, lastname, username, useremail, password, accounttype, confirmed, confirmcode) VALUES('{$firstname}', '{$lastname}', '{$username}','{$email}','{$password}','{$accounttype}', '0', '{$confirmcode}')");
=======
                $hashedpwd = password_hash($password,PASSWORD_DEFAULT);
                $query = query("INSERT INTO users(firstname,lastname,username,useremail,password,accounttype) VALUES('{$firstname}','{$lastname}','{$username}','{$email}','{$hashedpwd}','{$accounttype}')");
>>>>>>> master
                confirm($query);
                //$message =
                //"
               // Confirm Your Email!
               // Click the link below to verify your NetNexus account
               // http://192.168.64.2/shoppingtest/public/emailconfirmation.php?username=$username&code=$confirmcode
                //";

                send_mail($email, $username, $confirmcode);

		      //echo "Registration Complete! Please confirm your email address";

            }else
            {
                set_message("Passwords Do Not Match");
                redirect("register.php");
            }
        }else
        {
            set_message("Username Already Taken");
            redirect("register.php");
        }

    }
}

function signout()
{
    if(isset($_POST['logout']))
    {
        session_destroy();
        redirect('index.php');
    }
}




function send_message()
{
    if(isset($_POST['submit']))
    {
        $to        = "someEmail@gmail.com";
        $from_name = $_POST["name"];
        $subject   = $_POST["subject"];
        $email     = $_POST["email"];
        $message   = $_POST["message"];
        $headers = "From: {$from_name} {$email}";

        //should change very unreliable
        $res = mail($to,$subject,$message,$headers);

        if(!res)
        {
            set_message("error");
            redirect("contact.php");
        }else
        {
            set_message("success");
            redirect("contact.php");

        }
    }
}

function send_mail($email, $username, $confirmcode)
{
 $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'netnexusshop@gmail.com';                 // SMTP username
$mail->Password = 'netnexus123';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('donotreply@netnexus.com', 'NetNexus');
$mail->addAddress($email);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'NetNexus Email Confirmation';
$mail->Body    = "
                Confirm Your Email!
                Click the link below to verify your NetNexus account
                http://192.168.64.2/shoppingtest/public/emailconfirmation.php?username=$username&code=$confirmcode
                ";
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Registration Complete! Check your emails for a confirmation!';
}

}

// function to get products based on search
function get_search()
{

    $search = $_GET["search"];


    $query= query("SELECT * FROM products WHERE product_brand LIKE '%$search%' OR product_category_id IN(Select cat_id from categories where cat_title like '%$search%')");
    confirm($query);


    $queryResult = mysqli_num_rows($query);

    if ($queryResult > 0) {

        $query = query("SELECT * FROM products WHERE product_brand LIKE '%". escape_string($_GET['search']) ."%' OR product_category_id IN(Select cat_id from categories where cat_title like'%". escape_string($_GET['search']) ."%')  ");
        confirm($query);

        while($row = fetch_array($query))
        {
            $product = <<<DELIMETER
           <div class="col-sm-4 col-lg-4 col-md-4">
                <div class="thumbnail" style="height:340px">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:165px;" class="imgsize" src="{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add To Cart</a>
                        <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    </div>
                </div>
            </div>

        DELIMETER;

            echo $product;

        }
    }else {
        echo "There are no results matching your search!";
    }

}


// function to get products based on price range

function get_price_products()
{
    $query = query(" SELECT * FROM products WHERE product_price BETWEEN " . escape_string($_GET['lowPrice']) ." AND " . escape_string($_GET['highPrice']) ." ");
    confirm($query);

    while($row = fetch_array($query))
    {
        $product = <<<DELIMETER
            <div class="col-sm-4 col-lg-4 col-md-4">
                <div class="thumbnail" style="height:340px">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:165px;" class="imgsize" src="{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add To Cart</a>
                        <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
    }
}
// function to display all products

function get_all_products() {
    $query = query(" SELECT * FROM products");
    confirm($query);

    while($row = fetch_array($query))
    {
        $product = <<<DELIMETER
            <div class="col-sm-4 col-lg-4 col-md-4">
                <div class="thumbnail" style="height:340px">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:165px;" class="imgsize" src="{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add To Cart</a>
                        <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
    }
}

// function to filter product by search and also price
function get_search_price_products() {

    $query = query(" SELECT * FROM products WHERE product_price BETWEEN " . escape_string($_GET['lowPrice']) ." AND " . escape_string($_GET['highPrice']) ." AND product_brand LIKE '%". escape_string($_GET['search']) ."%' OR product_category_id IN(Select cat_id from categories where cat_title like'%". escape_string($_GET['search']) ."%')");
    confirm($query);
    while($row = fetch_array($query))
    {
        $product = <<<DELIMETER
            <div class="col-sm-4 col-lg-4 col-md-4">
                <div class="thumbnail" style="height:340px">
                    <a href="item.php?id={$row['product_id']}"><img style="width: auto;height:165px;" class="imgsize" src="{$row['product_image']}" alt=""></a>
                    <div class="caption">
                        <h4 style="overflow: hidden;text-overflow: ellipsis;" >
                            <a style="text-overflow: ellipsis;" href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                        </h4>
                        <p style="overflow: hidden;height: 64px;">{$row['product_short_description']}</p>
                    </div>
                    <div class="ratings">
                        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add To Cart</a>
                        <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    </div>
                </div>
            </div>

        DELIMETER;

        echo $product;
    }
}

function check_search() {

}

//____________________________________ back end functions__________________//





function display_orders()
{
    $up = "../../resources/uploads";
    $del = "../../resources/templates/back/delete_order.php?";
    if($_SESSION['useraccount'][1] == 'seller'  )
    {

        $query = query("SELECT *
                        FROM categories, reports INNER JOIN orders ON reports.order_id = orders.order_id
                                     INNER JOIN products ON reports.product_id = products.product_id
                                     INNER JOIN users ON reports.purchaser_id = users.user_id
                        WHERE        (categories.cat_id = products.product_category_id) &&
                                     (products.seller_id = " . $_SESSION['useraccount'][3] ." )ORDER BY reports.order_id");
        confirm($query);
        echo "ff";
    }else if( $_SESSION['useraccount'][1] == 'admin')
    {

        $query = query("SELECT *
                        FROM categories, reports INNER JOIN orders ON reports.order_id = orders.order_id
                                     INNER JOIN products ON reports.product_id = products.product_id
                                     INNER JOIN users ON reports.purchaser_id = users.user_id
                        WHERE categories.cat_id = products.product_category_id
                        ORDER BY  reports.order_id");
                confirm($query);
    }else if($_SESSION['useraccount'][1] == 'buyer')
    {
       $query = query("SELECT *
                        FROM categories, reports INNER JOIN orders ON reports.order_id = orders.order_id
                                     INNER JOIN products ON reports.product_id = products.product_id
                                     INNER JOIN users ON reports.purchaser_id = users.user_id
                        WHERE        (categories.cat_id = products.product_category_id) &&
                                     (reports.purchaser_id =  " .$_SESSION['useraccount'][3].") ");
       confirm($query);
       $up = "../resources/uploads";
       $del = "../resources/templates/back/delete_order.php?";

    }

    while($row = fetch_array($query))
    {
        $orders = <<<DELIMETER
        <tr>
            <td>{$row['user_id']}</td>
            <td>{$row['seller_id']}</td>
            <td>{$row['cat_title']}</td>
            <td>{$row['product_title']}</td>
            <td><img style="width:64px; height:64px;" src="{$up}/{$row['product_image']}" alt=""></td>
            <td>{$row['purchased_product_price']}</td>
            <td>{$row['purchased_quantity']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['order_amt']}</td>
            <td>{$row['order_curency']}</td>
            <td>{$row['order_status']}</td>
            <td>{$row['order_transaction']}</td>
            <td><a class="btn btn-danger" href="{$del}id={$row['order_id']}&pr_id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

        DELIMETER;
        echo $orders;
    }
}

//sql query to select all will be needed

//SELECT users.user_id, products.seller_id, products.seller_id, product_image, product_title, purchased_product_price, purchased_quantity, order_amt, order_curency, order_status, order_transaction
//FROM reports r, products p, users u, orders o
//WHERE r.order_id = o.order_id && (r.product_id = p.product_id) &&(r.purchaser_id = u.user_id)





function get_products_backend()
{

    if($_SESSION['useraccount'][1] == 'seller' )
    {
       $query = query(" SELECT * FROM users,products, categories  WHERE (users.user_id = products.seller_id) && (categories.cat_id = products.product_category_id ) && products.seller_id = " .$_SESSION['useraccount'][3]." ");
       confirm($query);
        $up = "../../resources" . DS . "uploads" . DS;
        $del = "../../resources/templates/back/delete_product.php?";
    }else if( $_SESSION['useraccount'][1] == 'admin')
    {
       $query = query(" SELECT * FROM products, categories  WHERE categories.cat_id = products.product_category_id");
       confirm($query);
       $up = "../../resources" . DS . "uploads" . DS;
       $del = "../../resources/templates/back/delete_product.php?";

    }


    while($row = fetch_array($query))
    {
        $product = <<<DELIMETER
        <tr>
            <td>{$row['product_id']}</td>
            <td>{$row['seller_id']}</td>
            <td>{$row['product_title']}<br>
            <a href="index.php?edit_product&id={$row['product_id']}"><img style="width:64px; height:64px;" src="{$up}{$row['product_image']}" alt=""></a>
            </td>
            <td>{$row['cat_title']}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="{$del}id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

        DELIMETER;

        echo $product;
    }
}



//add products




function add_product()
{
if(isset($_POST['publish']))
{

$product_title          = escape_string($_POST['product_title']);
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$short_desc             = escape_string($_POST['short_desc']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = escape_string($_FILES['file']['tmp_name']);

//if(move_uploaded_file($image_temp_location, UPLOADS_DIRECTORY . DS . $product_image))
//{
//    redirect("index.php");
//}else
//{
//        redirect("index.php?fucku");
//
//}

$query = query("INSERT INTO products(product_title,seller_id, product_category_id, product_price,product_quantity, product_description, product_short_description, product_image) VALUES('{$product_title}','{$_SESSION['useraccount'][3]}', '{$product_category_id}', '{$product_price}','{$product_quantity}', '{$product_description}', '{$short_desc}',  '{$product_image}')");

$last_id = last_id();
confirm($query);
set_message("New Product with id {$last_id} was Added");


//might need move_uploaded_file on live server

if(copy($image_temp_location, UPLOADS_DIRECTORY . DS . $product_image))
{
    redirect("index.php?products");
}else
{
        //redirect("index.php?fucku");
    echo $_FILES["file"]['name']."<br>";
    echo $_FILES["file"]['tmp_name']."<br>";
    echo $_FILES["file"]['size']."<br>";
    echo $_FILES['file']['error']."<br>";
    echo UPLOADS_DIRECTORY . DS . $product_image;

}

}

}



function backend_addproductpage_categories()
{
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = mysqli_fetch_array($query))
    {
       $categoriesoptions = <<<DELIMETER
            <option value="{$row['cat_id']}">{$row['cat_title']}</option>
        DELIMETER;

        echo $categoriesoptions;
    }
}

function backend_categories_title($id)
{
    $query = query("SELECT * FROM categories WHERE cat_id=" .$id);
    confirm($query);

    while($row = mysqli_fetch_array($query))
    {
       return $row['cat_title'];
    }
}



function update_product()
{
if(isset($_POST['update']))
{

$product_title          = escape_string($_POST['product_title']);
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$short_desc             = escape_string($_POST['short_desc']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = escape_string($_FILES['file']['tmp_name']);



if(empty($product_image)) {

$get_pic = query("SELECT product_image FROM products WHERE product_id =" .escape_string($_GET['id']). " ");
confirm($get_pic);

while($pic = fetch_array($get_pic)) {

$product_image = $pic['product_image'];

    }

}

    copy($image_temp_location, UPLOADS_DIRECTORY . DS . $product_image);

$query = "UPDATE products SET ";
$query .= "product_title              = '{$product_title}'        , ";
$query .= "product_category_id        = '{$product_category_id}'  , ";
$query .= "product_price              = '{$product_price}'        , ";
$query .= "product_quantity           = '{$product_quantity}'     , ";
$query .= "product_description        = '{$product_description}'  , ";
$query .= "product_short_description  = '{$short_desc}'           , ";
$query .= "product_image              = '{$product_image}'          ";
$query .= "WHERE product_id           = " . escape_string($_GET['id']);

$query_update = query($query);
confirm($query_update);
set_message("Product updated");


//might need move_uploaded_file on live server


    redirect("index.php?products");

}

}


