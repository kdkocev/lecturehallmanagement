<?php
	include_once 'system/model.php';
	include_once 'models/members.php';
	include_once 'models/notes.php';

	class PresentationSlot extends Model {
		public $id;
		public $start_time;
		public $end_time;
		public $member;
		public $title;
		public $description;
		public $created_at;
		public $is_locked;
		public $slot_is_taken;

		function __construct($arr) {
			parent::__construct();
			$this->id = $arr['slot_id'];
			$this->start_time = $arr['start_time'];
			$this->end_time = $arr['end_time'];
			
			$this->member = new Member($arr);
			$this->link_id = $arr['link_id'];
			$this->title = $arr['title'];
			$this->description = $arr['description'];
			$this->created_at = $arr['created'];
			$this->is_locked = $arr['is_locked'];
			$this->slot_is_taken = !is_null($this->member->id);
		}
		
		public static function objects() {
			return new PresentationSlotManager();
		}
		
		public function update_time($start_time, $end_time) {
			$id = $this->id;
			$sql = "UPDATE `presentationslots` SET `start_time` = '$start_time', `end_time` = '$end_time' WHERE `id`=$id";
			$this->db->query($sql);
			return PresentationSlot::objects()->get($id);
		}

		public function lock() {
			$id = $this->id;
			$slot = PresentationSlot::objects()->get($id);
			$sql = "UPDATE `presentationslots` SET `is_locked` = '1', `start_time`='".$slot->start_time."', `end_time`='".$slot->end_time."' WHERE `presentationslots`.`id` = $id;";
			$this->db->query($sql);
			return PresentationSlot::objects()->get($id);
		}

		public function unlock() {
			$id = $this->id;
			$slot = PresentationSlot::objects()->get($id);
			$sql = "UPDATE `presentationslots` SET `is_locked` = '0', `start_time`='".$slot->start_time."', `end_time`='".$slot->end_time."' WHERE `presentationslots`.`id` = $id;";
			$this->db->query($sql);
			return PresentationSlot::objects()->get($id);
		}

		public function addReview($score, $start_time, $end_time, $duration, $description) {
			$link_id = $this->link_id;
			$sql = "INSERT INTO `notes` (`id`, `score`, `description`, `start_time`, `end_time`, `duration`, `presentationslots_members`, `created`) VALUES (NULL, '$score', '$description', '$start_time', '$end_time', '$duration', '$link_id', NOW());";
			return $this->db->query($sql);
		}

		public function getNotes() {
			$id = $this->link_id;
			$sql = "SELECT * FROM `notes` WHERE `presentationslots_members`=$id";
			$db_res = $this->db->query($sql);
			$res = [];
			if($db_res === false) {
				return array();
			}
			foreach($db_res as $row) {
				$res[] = new Note($row);
			}
			return $res;
		}
	}

	class PresentationSlotManager extends Manager {

		public static $instance = null;
		function __construct() {
			// if(!is_null(self::$instance)) {
			// 	return self::$instance;
			// }
			parent::__construct();
			self::$instance = $this;
		}
		
		public $table = 'presentationslots';

		public function all() {
			$table = $this->table;

			$sql = "SELECT *, `presentationslots`.`id` as slot_id, `presentationslots_members`.`id` as `link_id`
					FROM `presentationslots`
					LEFT JOIN `presentationslots_members` ON `presentationslots`.`id` = `presentationslots_members`.`presentationslot_id`
					LEFT JOIN `members` ON `presentationslots_members`.`member_id` = `members`.`id`";

			$db_res =  $this->db->query($sql);

			$res = [];
			foreach($db_res as $row) {
				$res[] = new PresentationSlot($row);
			}

			return $res;
		}
		
		public function forDate($date) {
			$start_date = $this->escape($date->setTime(0,0)->format("Y-m-d H:i:s"));
			$end_date = $this->escape($date->setTime(23,59)->format("Y-m-d H:i:s"));

			$table = $this->table;

			$sql = "SELECT *, `presentationslots`.`id` as slot_id, `presentationslots_members`.`id` as `link_id`
					FROM $table
					LEFT JOIN `presentationslots_members` ON `presentationslots`.`id` = `presentationslots_members`.`presentationslot_id`
					LEFT JOIN `members` ON `presentationslots_members`.`member_id` = `members`.`id`
					WHERE start_time > '$start_date' && end_time < '$end_date'";

			$db_res =  $this->db->query($sql);

			$res = [];
			foreach($db_res as $row) {
				$res[] = new PresentationSlot($row);
			}

			return $res;
		}
		
		public function create($start_time, $end_time) {
			$sql = "INSERT INTO `presentationslots`
			(`id`, `start_time`, `end_time`)
			VALUES
			(NULL, '".$this->escape($start_time)."', '".$this->escape($end_time)."');";

			if($this->db->query($sql)) {
				return $this->db->conn->insert_id;
			}
		}
		
		// TODO: verify that it is only a single element
		public function get($id) {
			$id = $this->escape($id);
			$table = $this->table;
			$sql = "SELECT *, `presentationslots`.`id` as slot_id, `presentationslots_members`.`id` as `link_id`
					FROM $table
					LEFT JOIN `presentationslots_members` ON `presentationslots`.`id` = `presentationslots_members`.`presentationslot_id`
					LEFT JOIN `members` ON `presentationslots_members`.`member_id` = `members`.`id`
					WHERE `presentationslots`.`id`=$id";
			$db_res = $this->db->query($sql);

			$res=[];
			foreach($db_res as $row) {
				$res[] = new PresentationSlot($row);
			}
			return $res[0];
		}
		
		public function _delete($id) {
			$table = $this->table;
			$id = $this->escape($id);
			$sql = "DELETE FROM $table WHERE `id`=$id";
			return $this->db->query($sql);
		}

		public function reserve($slot_id, $user_id, $title, $description) {
			$slot_id = $this->escape($slot_id);
			$user_id = $this->escape($user_id);
			$title = $this->escape($title);
			$description = $this->escape($description);
			$sql = "INSERT INTO `presentationslots_members` (`id`, `member_id`, `presentationslot_id`, `title`, `description`, `created`) VALUES (NULL, '$user_id', '$slot_id', '$title', '$description', CURRENT_TIMESTAMP);";
			return $this->db->query($sql);
		}

		public function delete_reservation($slot_id, $user_id) {
			$slot_id = $this->escape($slot_id);
			$user_id = $this->escape($user_id);

			$sql = "DELETE FROM `presentationslots_members` WHERE `member_id`=$user_id AND `presentationslot_id`=$slot_id";
			return $this->db->query($sql);
		}
	}
