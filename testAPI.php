<?php
/**
 * Created by PhpStorm.
 * User: peymanvalikhanli
 * Date: 8/24/17 AD
 * Time: 10:42
 */
?>

<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?php
// turn off the WSDL cache
ini_set("soap.wsdl_cache_enabled", "0");
  try {
      $user = "valikhanli";
      $pass = "123123";
      $line_number = "20002222009403";

      $textMessage = "salam test send message";


      $client = new SoapClient("http://87.107.121.52/post/send.asmx?wsdl");

      $getcredit_parameters = array(
          "username"=>$user,
          "password"=>$pass
      );
      $credit = $client->GetCredit($getcredit_parameters)->GetCreditResult;
      echo "Credit: ".$credit."<br />";

      $encoding = "UTF-8";//CP1256, CP1252
      $textMessage = iconv($encoding, 'UTF-8//TRANSLIT',$textMessage);

      $sendsms_parameters = array(
          'username' => $user,
          'password' => $pass,
          'from' => $line_number,
          'to' => array("09374386515"),
          'text' => $textMessage,
          'isflash' => false,
          'udh' => "",
          'recId' => array(0),
          'status' => 0
      );

      $status = $client->SendSms($sendsms_parameters)->SendSmsResult;
      echo "Status: ".$status."<br />";

      $getnewmessage_parameters = array(
          "username"=>$user,
          "password"=>$pass,
          "from"=>$line_number
      );
      $incomingMessagesClient = new SoapClient("http://87.107.121.52/post/IncomingMessages.asmx?wsdl");
      $res = $incomingMessagesClient->GetNewMessagesList($getnewmessage_parameters);

      echo "<table border=1>";
      echo "<th>MsgID</th><th>MsgType</th><th>Body</th><th>SendDate</th><th>Sender</th><th>Receiver</th><th>Parts</th><th>IsRead</th>";
      foreach($res->GetNewMessagesAResult->Message as $row){
          echo "<tr>"
              ."<td>".$row->MsgID."</td>"
              ."<td>".$row->MsgType."</td>"
              ."<td>".$row->Body."</td>"
              ."<td>".$row->SendDate."</td>"
              ."<td>".$row->Sender."</td>"
              ."<td>".$row->Receiver."</td>"
              ."<td>".$row->Parts."</td>"
              ."<td>".$row->IsRead."</td>"
              ."</tr>";
      }
      echo "</table>";

  } catch (SoapFault $ex) {
      echo $ex->faultstring;
  }
?>
</body>
</html>