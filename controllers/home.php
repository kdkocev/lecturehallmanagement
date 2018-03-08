<?php
  include_once "models/presentationSlots.php";

  class HomeController extends Controller {

    function forbiddenIfNotAdmin() {
      if(!$_SESSION['User']['is_admin']) {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        die();
      }
    }

    public function get() {
      $config = include 'config.php';
      if(is_null($_SESSION['User'])) {
        header('Location:' . $config['base_path'] . "/login?error=".$_GET['error']);
      }

      $date = $this->getDate();
      $slots = PresentationSlot::objects()->forDate($date);
      
      $interval = DateInterval::createFromDateString('1 day');
      $prev_day = $date->sub($interval)->format("d-m-Y");
      $date = $this->getDate();
      $next_day = $date->add($interval)->format("d-m-Y");
      $date = $this->getDate();
      
      $all_slots = PresentationSlot::objects()->all();
      $free_slots = [];
      foreach($all_slots as $slot) {
        if(!$slot->slot_is_taken) {
          $free_slots[] = $slot;
        }
      }

      $data = array(
        "slots" => $slots,
        "today" => $date,
        "prev_day" => array(
          "url" => $config['base_path'] ."/?date=".$prev_day,
          "date" => $prev_day
        ),
        "next_day" => array(
          "url" => $config['base_path'] ."/?date=".$next_day,
          "date" => $next_day
        ),
        "free_slots" => $free_slots
      );

      return $this->render("home.tpl", $data);
    }

    private function getDate() {
      $date = new DateTime();
      if(isset($_GET['date'])) {
        $date = DateTime::createFromFormat("d-m-Y", $_GET['date']);
      }
      return $date;
    }

    public function reserve() {
      $id = $_POST['id'];
      $title = $_POST['title'];
      $description = $_POST['description'];
      $slot = PresentationSlot::objects()->get($id);
      if($slot->is_locked || trim($title) == "" || trim($description) == "") {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        die();
      }

      $user_id = $_SESSION['User']['id'];
      PresentationSlot::objects()->reserve($id, $user_id, $title, $description);
      
      $config = include "config.php";

      header("Location: ".$_SERVER['HTTP_REFERER']);
    }

    public function addReview() {
      
      $slot_id = $_POST['slot_id'];
      $slot = PresentationSlot::objects()->get($slot_id);
      $score = $_POST['score'];
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];
      $duration = $_POST['duration'];
      $description = $_POST['description'];

      $slot->addReview($score, $start_time, $end_time, $duration, $description);

      header("Location: ".$_SERVER['HTTP_REFERER']);
      die();
    }

    public function addMany() {
      $this->forbiddenIfNotAdmin();

      $from = $_POST['from'];
      $to = $_POST['to'];
      $interval = $_POST['interval'];
      $date = $_POST['date'];


      $scale = "+" . $interval . " Minutes";
      $start = strtotime($date . " " . $from . ":00");
      $end = strtotime($date . " " . $to . ":00");

      // var_dump($from, $to, $date, $start, $end, $scale);

      $results = array();

      while($start < $end) {
        $new_start = strtotime($scale, $start);
        
        $results[] = array(
          "from" => date("Y-m-d H:i:s", $start),
          "to" => date("Y-m-d H:i:s", $new_start)
        );
        
        $start = $new_start;
      }

      // var_dump($results);

      foreach($results as $d) {
        PresentationSlot::objects()->create($d['from'], $d['to']);
      }

      header("Location: " .  $_SERVER['HTTP_REFERER']);
    }
  }
