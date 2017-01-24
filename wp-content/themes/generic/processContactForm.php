<?php
/*Template Name: Process Contact Form*/


  function processData($data){
    $item = trim($data);
    $item = stripslashes($data);
    $item = htmlspecialchars($data);

    return $item;
  }

  $form = processData($_POST['formName']);

  if($form == 'contactUs'){
      $name    = processData($_POST['fullName']);
      $company    = processData($_POST['companyName']);
      $phone      = processData($_POST['phoneNumber']);
      $email          = processData($_POST['email']);



      $to = 'okinsey@elearningsolution.tech';
      $subject = 'Contact Preferred Insurance';
      $from = 'Preferred Insurance webform';
      $headers = "From: $from";


      $message =
        "The form works!!! You have received a message from you quote form.  The sender's information is as follows:

          Name: $name

          Company: $company

          Email: $email

          Phone: $phone";


      mail( $to, $subject, $message, $headers );

      header('Location: http://elearningsolution.tech/?r=1#section_four');
      exit();
  }
  else{
    header('Location: http://elearningsolution.tech/?r=error');
  }

 ?>
