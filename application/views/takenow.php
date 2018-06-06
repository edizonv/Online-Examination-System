<?php if($this->session->userdata('userPositionSessId') == 1): // examiners only ?>
<div class="panel panel-primary<?php if($topic->status == 0) echo " disable";  ?>" id="topicInfo">
	<div class="panel-heading text-uppercase"><strong><?php echo $topic->title ?></strong>
	<div class="dropdown pull-right">
	  <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">ACTION <span class="caret"></span></a>
	  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	  	<?php if($questions[0]->status): ?>
		    <li><a href="#" class="white" data-toggle="modal" data-target="#newQuestionModal"><i class="fa fa-plus"></i> NEW QUESTION</a></li>
		    <li><a href="#" class="white" data-toggle="modal" data-target="#newExaminersModal"><i class="fa fa-plus"></i> ADD EXAMINER</a></li>
	    	<li class="divider"></li>
	    <?php endif; ?>
	    <li><a tabindex="-1" href="/questions/status/<?php echo $this->uri->segment(3); ?>/<?php echo $topic->status ?>"><i class="fa fa-power-off"></i><?php if($topic->status == 1): ?> DISABLE <?php else: ?> ENABLE <?php endif; ?></a></li>
	  </ul>
	</div>
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
				<?php if($questions[0]->status): ?>
					<?php $columns = array(); ?>
					<?php foreach($questions as $rows): ?>
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
							<p class="<?php if($rows->qstat == 0): ?> disabled <?php endif; ?>"><label><input type="radio" name="choice" value="<?php echo $rows->choices; ?>"><?php echo $rows->choices . '. ' . $rows->choicesText; ?></label></p>
						<?php else: ?>
							<?php if($rows->type == 3): ?>
								<textarea name="paragraph" class="form-control"></textarea>
							<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>

				
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>