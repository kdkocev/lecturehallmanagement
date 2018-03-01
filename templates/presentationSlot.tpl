<?php error_reporting(0); ?>

<?php
  $slot_classes = array("slot");
  if($this->db_slot->slot_is_taken) {
    $slot_classes[] = "taken";
  }
  if($this->db_slot->is_locked) {
    $slot_classes[] = "locked";
  }
  $slot_classes = implode(" ", $slot_classes);

  $slot_styles = "top:". $this->top . "px;" . "height:" . $this->height . "px;";

  $request_user = $_SESSION['User'];
?>

<div class='<?php echo $slot_classes; ?>' id='<?php echo $this->id; ?>' style="<?php echo $slot_styles; ?>">
  <?php if($this->db_slot->slot_is_taken) { ?>
    <div class="slot-content">
      <div class="author">
        <i class="material-icons">account_circle</i>
        <?php if($this->db_slot->slot_is_taken){ echo $this->db_slot->member->render();} ?>
      </div>
      <div class="title">
        <?php echo $this->title; ?>
      </div>
      <div class="content">
        <?php echo $this->content; ?>
      </div>
    </div>
  <?php } ?>

  <?php if(!$this->db_slot->slot_is_taken && !$this->db_slot->is_locked && !$request_user['is_admin']) { ?>
    <div class="slot-placeholder">
      <button class="take-slot-button">Take slot</button>
    </div>
  <?php } ?>

  <?php if($request_user['is_admin']) { ?>
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
  <?php } ?>

  <?php if(!$request_user['is_admin'] && $this->db_slot->is_locked) { ?>
    <div class="slot-locked-status">
      <i class="material-icons">lock</i>
    </div>
  <?php } ?>

  <?php if(!$request_user['is_admin'] && !$this->db_slot->is_locked && $request_user['id'] == $this->db_slot->member->id) { ?>
    <div class="settings-bar">
      <div class="settings-button"><i class="material-icons">settings</i></div>
      <div class="settings-list">
        <ul>
          <?php if($request_user['id'] == $this->db_slot->member->id && !$this->db_slot->is_locked) { ?>
            <li class="delete-reservation"><i class="material-icons">delete_forever</i> Delete reservation</li>
          <?php } ?>
        </ul>
      </div>
    </div>
  <?php } ?>

  <?php if($request_user['is_admin']) { ?>
    <div class='resize-handle' ref='<?php echo $this->id; ?>'></div>
  <?php } ?>
</div>
<?php error_reporting(E_ALL); ?>