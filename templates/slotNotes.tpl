<div class="notes-container">
	<?php if(empty($this->notes)) echo "No notes yet"; ?>
	<?php foreach($this->notes as $note) { ?>
		<div class="note">
			CreatedAt: <?php echo $note->created_at; ?>
			Score: <?php echo $note->score; ?>
			Start: <?php echo $note->start_time; ?>
			End: <?php echo $note->end_time; ?>
			Duration: <?php echo $note->duration; ?>
			Note text: <?php echo $note->description; ?>
		</div>
	<?php } ?>
</div>