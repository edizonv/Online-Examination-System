
<div class="panel panel-primary<?php if($topic->status == 0) echo " disable";  ?>" id="topicInfo">
	<div class="panel-heading text-uppercase"><strong><?php echo $topic->title . ' (' . $topic->duration.')'; ?></strong>
	<?php $count = ""; ?>
	<?php foreach($questions as $key => $rows): ?>
		<?php if($rows->choices == $rows->recordAnswer): ?>
			<?php if($rows->recordAnswer == $rows->answer): ?>
				<?php $count[] = $key; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<span class="pull-right score">Score : <b><?php echo count($count);  ?></b></span>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?php if(isset($questions[0]->status) ): ?>
					<?php $columns = array(); ?>
					<?php foreach($questions as $rows): ?>
						<?php if (!isset($columns[$rows->questions]) ): ?>
							<p class="hr <?php if($rows->qstat == 0): ?> disabled <?php endif; ?>">
								<strong><?php echo $rows->questions; ?></strong>
								<?php if($rows->answer != $rows->recordAnswer): ?>
									<?php if($rows->answer): ?><strong class="pull-right correction"><?php echo $rows->answer; ?></strong><?php endif; ?>
								<?php endif; ?>
							</p>
							<?php $columns[$rows->questions] = true; ?>
						<?php endif; ?>
						
						<?php if($rows->choices == $rows->recordAnswer): ?>
							<?php if($rows->recordAnswer == $rows->answer): ?>
								<p class="<?php if($rows->qstat == 0): ?> disabled <?php endif; ?> correct"><?php echo $rows->choices . '. ' . $rows->choicesText; ?></p>
							<?php else: ?>
								<p class="<?php if($rows->qstat == 0): ?> disabled <?php endif; ?> wrong"><?php echo $rows->choices . '. ' . $rows->choicesText; ?></p>
							<?php endif; ?>
						<?php else: ?>
							<p class="<?php if($rows->qstat == 0): ?> disabled <?php endif; ?> "><?php echo $rows->choices . '. ' . $rows->choicesText; ?></p>
						<?php endif; ?>
						

					<?php endforeach; ?>
				<?php else: ?>
					<div class="alert alert-warning"><p>You dont have access in this topic!</p></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
