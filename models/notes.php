<?php
	class Note extends Model {
		public $id;
		public $score;
		public $start_time;
		public $end_time;
		public $duration;
		public $description;
		public $created_at;
		public $link_id;

		function __construct($arr) {
			parent::__construct();
			$this->id = $arr['id'];
			$this->score = $arr['score'];
			$this->start_time = $arr['start_time'];
			$this->end_time = $arr['end_time'];
			$this->duration = $arr['duration'];
			$this->description = $arr['description'];
			$this->created_at = $arr['created'];
			$this->link_id = $arr['presentationslots_members'];
		}
	}