<?php
	include_once "models/presentationSlots.php";

	class SlotController extends Controller {
		function forbiddenIfNotAdmin() {
			if(!array_key_exists('User', $_SESSION) || !$_SESSION['User']['is_admin']) {
				header('HTTP/1.0 403 Forbidden');
				die();
			}
		}

		function fetch_all() {
			$date = $_POST['date'];
			$slots = PresentationSlot::objects()->forDate(new DateTime($date));
			echo json_encode($slots);
			
		}

		function retreive() {
			$id = $_POST['id'];
			$slot = PresentationSlot::objects()->get($id);
			return json_encode($slot);
		}

		function create() {
			$this->forbiddenIfNotAdmin();

			$post = $_POST['slot'];
			$start_time = $post['start_date'];
			$end_time = $post['end_date'];
			$slot = PresentationSlot::objects()->create($start_time, $end_time);
			echo $slot;
		}
		
		function remove() {
			$this->forbiddenIfNotAdmin();
			$id = $_POST['id'];
			echo PresentationSlot::objects()->_delete($id);
		}

		function toggle_lock_slot() {
			$this->forbiddenIfNotAdmin();
			$id = $_POST['id'];
			$slot = PresentationSlot::objects()->get($id);
			if($slot->is_locked) {
				$slot->unlock();
			} else {
				$slot->lock();
			}
			echo json_encode($slot);
		}
		
		function update() {
			$this->forbiddenIfNotAdmin();

			$id = $_POST['id'];
			$start_date = $_POST['start_date'];
			$end_date = $_POST['end_date'];
			
			$slot = PresentationSlot::objects()->get($id);
			
			
			echo json_encode($slot->update_time($start_date, $end_date));
		}

		function renderSlot() {
			error_reporting(0);
			$data = json_decode($_POST['slot'], true);
			if(array_key_exists('id', $data)) {
				$data['db_slot'] = PresentationSlot::objects()->get(substr($data['id'], 5));
			}
			error_reporting(E_ALL);

			return $this->render("presentationSlot.tpl", $data);
		}

		function renderNotes() {
			$this->forbiddenIfNotAdmin();
			$slot_id = $_POST['id'];
			$slot = PresentationSlot::objects()->get($slot_id);

			$notes = $slot->getNotes();
			return $this->render("slotNotes.tpl", array("notes" => $notes));
		}

		public function setfree() {
			$id = $_POST['id'];
			$slot = PresentationSlot::objects()->get($id);

			if($slot->member->id != $_SESSION['User']['id']) {
				header("Location: ".$_SERVER['HTTP_REFERER']);
				die();
			}

			$res = PresentationSlot::objects()->delete_reservation($slot->id, $slot->member->id);

			echo $res;
		}
	}