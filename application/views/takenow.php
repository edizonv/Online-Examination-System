<?php if($this->session->userdata('userPositionSessId') == 1): // examiners only ?>

<div class="panel panel-primary<?php if($topic->status == 0) echo " disable";  ?>" id="topicInfo">
	<div class="panel-heading"><strong><?php echo $topic->title ?></strong>
		<p id="demo" class="pull-right"></p>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?php if($this->session->flashdata('questionStatusUpdated') ): ?>
					<?php echo $this->session->flashdata('questionStatusUpdated'); ?>
				<?php endif;?>
			</div>
			<div class="col-md-12">
				<?php if($this->session->flashdata('questionAdded') ): ?>
					<?php echo $this->session->flashdata('questionAdded'); ?>
				<?php endif;?>
			</div>
			<div class="col-md-12">
				<?php if($this->session->flashdata('updateExaminersByTopic') ): ?>
					<?php echo $this->session->flashdata('updateExaminersByTopic'); ?>
				<?php endif;?>
			</div>
			<div class="col-md-12">
		
					<?php $columns = array(); ?>

					<?php foreach($questions as $rows): ?>
	
						<?php if($this->session->userdata('topicid') == $rows->id): ?>

							<?php if(isset($topic->duration) ): ?>
								<?php $duration = explode(':', $topic->duration); ?>
								<input type="hidden" name="hiddenH" id="hiddenH" value="<?php echo $duration[0]; ?>">
								<input type="hidden" name="hiddenM" id="hiddenM" value="<?php echo $duration[1]; ?>">
								<input type="hidden" name="startDate" id="startDate" value="<?php echo $this->session->userdata('startDate'); ?>">
							<?php endif; ?>

							<?php if ($rows->questions): ?>
								<?php if (!isset($columns[$rows->questions]) ): ?>
									<p class="hr <?php if($rows->qstat == 0): ?> disabled <?php endif; ?>">
									<?php if($this->session->userdata('userPositionSessId') == 0): // examiners only ?>
										<a href="/questions/edit/<?php echo $rows->id ?>/<?php echo $rows->no ?>"><i class="fa fa-pencil"></i> </a>
									<?php endif; ?>
									<strong><?php echo $rows->questions; ?></strong></p>
									<?php $columns[$rows->questions] = true; ?>
								<?php endif; ?>
							
							<?php endif; ?>
							<?php if($rows->choices && $rows->choicesText): ?>
								<?php foreach($getChoices as $c): ?>
									<?php if ($c->no == $rows->no): ?>
										<p><?php echo $c->choice. '. ' . $c->choices ?></p>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else: ?>
								<?php if($rows->type == 3): ?>
									<textarea name="paragraph" class="form-control"></textarea>
								<?php endif; ?>
							<?php endif; ?>

						<?php else: ?>

							<div class="alert alert-warning">
								<p>You can only take 1 exam at a time.</p>
							</div>

						<?php endif; ?>

					<?php endforeach; ?>

				<input type="hidden" name="hiddenId" id="hiddenId" value="<?php echo $this->uri->segment(3); ?>">
				<input type="hidden" name="hiddenDate" id="hiddenDate">
				
			</div>
		</div>
	</div>
</div>

<?php echo $links; ?>

<script>
	Date.prototype.addHours= function(h){
	    this.setHours(this.getHours()+h);
	    return this;
	}
	Date.prototype.addMinutes= function(m){
	    this.setMinutes(this.getMinutes()+m);
	    return this;
	}

	

	// Set the date we're counting down to

	var getH = document.getElementById('hiddenH').value,
		getM = document.getElementById('hiddenM').value;
		startDate = document.getElementById('startDate').value;


	var countDownDate = new Date(startDate).addHours(parseInt(getH) ).addMinutes(parseInt(getM) );

	// Update the count down every 1 second
	var x = setInterval(function() {	

	  // Get todays date and time
	  var now = new Date().getTime();

	  // Find the distance between now an the count down date
	  var distance = countDownDate - now;

	  // Time calculations for days, hours, minutes and seconds
	  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	  // Display the result in the element with id="demo"



	  var duration = hours + ":" + minutes + ":" + seconds;

	  document.getElementById("demo").innerHTML = duration;

	  var hiddenId = document.getElementById("hiddenId").value;

	
	   // $.ajax({
	   // 		type: "POST",
    //         url: "/questions/updateRemainingTime/",
    //         data: {
    //             "duration": duration,
    //             "topicId" : hiddenId
    //         }, success: function(res) {
    //         	console.log(duration );
    //         }

    //    });
	  
	  // If the count down is finished, write some text 
	  if (distance < 0) {
	    clearInterval(x);
	    document.getElementById("demo").innerHTML = "TIMES UP";
	    //window.location = "";
	  }
	}, 1000);
</script>


<?php endif; ?>