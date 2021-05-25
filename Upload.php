
<?php

if(!empty($_FILES['file']['name']))
{
 $connect = new PDO("localhost:3306", "root", "Gokul12", array(
        PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    ));

 $total_row = count(file($_FILES['file']['tmp_name']));

 $file_location = str_replace("\\", "/", $_FILES['file']['tmp_name']);

 $query_1 = '
 LOAD DATA LOCAL INFILE "'.$file_location.'" IGNORE 
 INTO TABLE customer_table 
 FIELDS TERMINATED BY "," 
 LINES TERMINATED BY "\r\n" 
 IGNORE 1 LINES 
 (@column1,@column2,@column3,@column4) 
 SET customer_first_name = @column2, customer_last_name = @column3,  customer_email = @column4, customer_gender = @column5
 ';

 $statement = $connect->prepare($query_1);

 $statement->execute();

 $query_2 = "
 SELECT MAX(customer_id) as customer_id FROM customer_table
 ";

 $statement = $connect->prepare($query_2);

 $statement->execute();

 $result = $statement->fetchAll();



 foreach($result as $row)
 {
  $customer_id = $row['customer_id'];
 }

 $first_customer_id = $customer_id - $total_row;

 $output = array(
  'success' => 'Total <b>'.$total_row.'</b> Data imported'
 );

 echo json_encode($output);
}

?>
