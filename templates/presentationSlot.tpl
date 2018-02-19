<?php error_reporting(0); ?>
<div class='slot<?php if($this->db_slot->slot_is_taken) {echo " taken";} ?><?php if($this->db_slot->is_locked) { echo " locked"; }?>' id='<?php echo $this->id; ?>' style='top:<?php echo $this->top; ?>px; height:<?php echo $this->height; ?>px;'>
	<?php if($this->db_slot->slot_is_taken) { ?>

	<div class="slot-content">
		<div class="author">
			<i class="material-icons">account_circle</i><?php if($this->db_slot->slot_is_taken){ echo $this->db_slot->member->render();} ?>
		</div>
		<div class="title">
			<?php echo $this->title; ?>
		</div>
		<div class="content">
			<?php echo $this->content; ?>
		</div>
	</div>
	<?php } else if(!$this->db_slot->is_locked && !$_SESSION['User']['is_admin']) { ?>
		<div class="slot-placeholder">
			<button class="take-slot-button">Take slot</button>
		</div>
	<?php } ?>

	<?php if($_SESSION['User']['is_admin']) { ?>
		<div class="settings-bar">
			<div class="settings-button"><i class="material-icons">settings</i></div>
			<div class="settings-list">
				<ul>
					<li class="remove-button"><i class="material-icons">delete_forever</i> Delete</li>
					<li class="toggle-lock-slot">
						<?php if($this->db_slot->is_locked) { ?>
						<i class="material-icons">lock_open</i> Unlock
						<?php } else { ?>
						<i class="material-icons">lock</i> Lock
						<?php } ?>
					</li>
					<li class="add-note-button"><i class="material-icons">mode_comment</i> Add Note</li>
					<li class="show-notes-button"><i class="material-icons">assignment</i> Show Notes</li>
					<li class="resize-mode"><i class="material-icons">vertical_align_center</i> Resize</li>
				</ul>
			</div>
		</div>
	<?php } else if($this->db_slot->is_locked) { ?>
		<div class="slot-locked-status">
			<i class="material-icons">lock</i>
		</div>
	<?php } else if($_SESSION['User']['id'] == $this->db_slot->member->id) { ?>
		<div class="settings-bar">
			<div class="settings-button"><i class="material-icons">settings</i></div>
			<div class="settings-list">
				<ul>
					<?php if($_SESSION['User']['id'] == $this->db_slot->member->id && !$this->db_slot->is_locked) { ?>
					<li class="delete-reservation"><i class="material-icons">delete_forever</i> Delete reservation</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	<?php } ?>

	<?php if($_SESSION['User']['is_admin']) { ?>
		<div class='resize-handle' ref='<?php echo $this->id; ?>'></div>
	<?php } ?>
</div>
<?php error_reporting(E_ALL); ?>