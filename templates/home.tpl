<?php include 'header.tpl'; ?>


<div class="page-wrapper">
  <div class="sidebar">
    <div class="search">
      <input name="search" placeholder="Search..." />
      <i class="material-icons">search</i>
    </div>

    <div class="free-slots">
      <h3>Free slots</h3>
      <ul class="free-slots-list">
        <?php foreach($this->free_slots as $slot) { ?>
          <li class="slot-item">
            <?php echo date("M-d", strtotime($slot->start_time)); ?>
            <b>
              <?php echo date("H:i", strtotime($slot->start_time)) . " - " . date("H:i", strtotime($slot->end_time)); ?>
            </b>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      <div class="presentation-slots-list">
        <div class="presentation-slot head">
          <div>Presentation slots</div>
        </div>
      </div>

      <div class="date-navigation">
        <ul>
        <?php if(isset($this->prev_day)) { ?>
          <li>
            <a href="<?php echo $this->prev_day['url']; ?>">
              <i class="material-icons">keyboard_arrow_left</i><?php echo $this->prev_day['date']; ?>
            </a>
          </li>
          <li>
            <i class="material-icons">today</i>
            <a href="<?php echo $this->configs['base_path']; ?>">Go to Today</a>
          </li>
          <li>
            <a href="<?php echo $this->next_day['url']; ?>"><?php echo $this->next_day['date']; ?>
              <i class="material-icons">keyboard_arrow_right</i>
            </a>
          </li>
        <?php } ?>
        </ul>
      </div>
      <div class="calendar-container">
        <div class="hours">
          <?php
            $start_hour = $this->configs['start_hour'];
            $end_hour = $this->configs['end_hour'];
            $scale = "+".$this->configs['calendar_delimeters_offset']." Minutes";

            $start_time = strtotime(date("H:i", mktime($start_hour,0,0,1,21,2018)));
            $end_time = strtotime(date("H:i", mktime($end_hour,0,0,1,21,2018)));
            $start = strtotime(date("H:00", $start_time));

            $hours_row_count = 0;

            $delimeters = [];

            while($start < $end_time) {
              $delimeters[] = date("H:i", $start);
              $start = strtotime($scale, $start);
              $hours_row_count++;
            }
            ?>

            <?php foreach ($delimeters as $del) { ?>
              <div class="delimeter" style="height:<?php echo $this->configs['calendar_hours_height'] ?>">
                <div class='text'>
                  <?php echo $del; ?>
                </div>
              </div>
            <?php } ?>
        </div>
        <div class="calendar">
          <?php for($i=0;$i<$hours_row_count;$i++) { ?>
            <div class='delimeter' style="top:<?php echo ($this->configs['calendar_hours_height'] * $i); ?>"></div>
          <?php } ?>
        </div>
      </div>

      <div class="take-slot-modal">
        <div class="form-container">
          <div class="reserving-slot-text" >
            Reserving slot from <span class="slot-start-time"></span> to <span class="slot-end-time"></span>
          </div>

          <form method="POST" action="<?php echo $this->configs['base_path']; ?>/slot/reserve">
            <div>
              <label>Title</label>
              <input type="text" name="title" />
            </div>
            <div>
              <label>Description</label>
              <textarea name="description"></textarea>
              <input name="id" type="hidden" />
            </div>
            <div>
              <button type="submit">Submit</button>
            </div>
          </form>
            <div>
              <button class="take-slot-modal-close">Close</button>
            </div>
        </div>
      </div>
    </div>
    <div class="add-note-modal">
      <div class="modal-container">
        <form action="<?php echo $this->configs['base_path']; ?>/slot/addreview" method="POST">
          <div>
            <label>Score:</label>
            <input name="score" />
          </div>
          <div>
            <label>Start time:</label>
            <input name="start_time" />
          </div>
          <div>
            <label>End time:</label>
            <input name="end_time" />
          </div>
          <div>
            <label>Duration:</label>
            <input name="duration" />
          </div>
          <div>
            <label>Note:</label>
            <textarea name="description">
            </textarea>
          </div>
          <input type="hidden" name="slot_id" value="" >
          <input type="submit" value="Save" />
        </form>
        <button class="close-add-note-modal">Close</button>
      </div>
    </div>
    <div class="notes-modal">
      <div class="notes-modal-close"><i class="material-icons">clear</i></div>
      <div class="notes-modal-wrapper">

      </div>
    </div>

    <script>
      window.isAdmin = <?php if($_SESSION['User']['is_admin']) {echo 'true';} else {echo 'false';}?>;
      window.date = "<?php echo $this->today->format("Y-m-d"); ?>";
      window.server = "<?php echo $this->configs['base_path'] ?>";
      window.startHour = <?php echo $this->configs['start_hour']; ?>;
      window.endHour = <?php echo $this->configs['end_hour']; ?>;
    </script>
  </div>
</div>

<?php include 'footer.tpl'; ?>
