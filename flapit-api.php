<?php

  class FlapIt {

    private $fb_access_token = '$$$$$';

    private $flapit_url = 'https://www.flapit.com/control/device';

    private $conn;

    function __construct() {
       $servername = "$$$$$";
       $username = "$$$$$";
       $password = "$$$$$";
       $dbname = "$$$$$";

       $this->conn = new mysqli($servername, $username, $password,$dbname);


       if ($this->conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
       }

       $this->getAccessToken();
       $this->FBGetForms();
    }

    private function getAccessToken(){

      $db_token = "null";

      $sql = "SELECT dddd FROM ddddd";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {
            $db_token = $row['token'];
        }
      }


      $url = 'https://graph.facebook.com/oauth/access_token?client_id=#####&client_secret=9609d774006f8f41c16af825042477de&grant_type=fb_exchange_token&fb_exchange_token='.$db_token;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERAGENT, "I-am-browser");
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      $response = curl_exec($ch);
      curl_close($ch);

      $formated_response = json_decode($response, true);
      $new_token = $formated_response['access_token'];

      $sql = "UPDATE ddddd SET ddddd='$new_token' WHERE id=1";
      $result = $this->conn->query($sql) or die(mysqli_error($this->conn));

      $this->fb_access_token= $new_token;

    }

    /*
      Este metodo se encarga de obtener todos los formularios de la pagina de ######,
      luego obtiene el id del ultimo formulario cargado en la pagina, para pasar al siguiente metodo.
    */
    private function FBGetForms(){

      $fb_url = "https://graph.facebook.com/v2.9/BMW.Paraguay/leadgen_forms?access_token=".$this->fb_access_token;

      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL, $fb_url);
      $result = curl_exec($ch);
      $print_result = file_get_contents($fb_url);
      curl_close($ch);

      if($result){

        $formated_result = json_decode($print_result, true);
        $last_item = $formated_result['data'][0];
        $form_id = $last_item['id'];

        $this->FBForm($form_id);

      } else {

        echo "ERROR EN CURL PARA OBTENER FORMS DE FB";

      }


    }

    /*
      Este metodo depende del id del ultimo formulario de fb cargado en la pagina ######,
      con el id obtiene todos los datos cargados en el formulario, se encarga de generar el size
      para notificar al siguiente metodo.
    */

    private function FBForm($id){

        $fb_form_url = "https://graph.facebook.com/v2.9/ddddddd/leads?limit=999999&access_token=".$this->fb_access_token;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $fb_form_url);
        $result = curl_exec($ch);
        $print_result = file_get_contents($fb_form_url);
        curl_close($ch);

        if($result){

          $formated_result = json_decode($print_result, true);
          $size = count($formated_result['data']);

          $this->flapItCurl($size);

        } else {

          echo "ERROR EN CURL PARA OBTENER FORM DATA DE FB";

        }



    }

    /*
      Este metodo recibe el size del ultimo formulario y notifica al curl que se encarga de notificar al flapit.
    */

    private function flapItCurl($size){


      $db_size = 0;

      $sql = "SELECT size FROM count";
      $result = $this->conn->query($sql);

      if($result->num_rows > 0){

        while($row = $result->fetch_assoc()){
          $db_size = $row['size'];
        }

      }

      echo "SIZE ACA";
      echo $size;

      if($db_size < $size){

        $sql = "UPDATE ddddd SET size= '$size' WHERE id=1";
        $result = $this->conn->query($sql) or die(mysqli_error($this->conn));

        $data = array("device_id" => ["FLP1-ddddd"], "token" => "ddddd", "message" => "facebook " . $size);
        $header = array('Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $this->flapit_url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        var_dump($result); //dejamos este var_dump por cuestiones de debugging

      } else {
        echo "YA ESTA ACTUALIZADO";
      }

    }
  }

  $flapit = new FlapIt();

?>