<?php

include_once 'custom_modules/common.php';
include_once 'flash_message.php';

global $Profile_ID;
global $Session_ID;

$notifications = loadPatientNotifications($Profile_ID);

?>
	<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth">
		<div class="row">
			<div class="tg-docinfo tg-haslayout">
				<div class="tg-box">		
					<div class="tg-description">
						<div class="col-md-3">
							<a href="javascript:" class="tg-btn" style="width: 100%;" id="book_dr"><span style="font-size: 14px;">Book Dr</span></a>
						</div>
						
						<div class="col-md-3">
							<a href="/patients/patient-notifications.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Notifications</a>
						</div>
						
						<div class="col-md-3">
							<a href="/patients/past-appointments-and-reviews.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Reviews</a>
						</div>
						
						<div class="col-md-3">
							<a href="/patients/settings.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Settings</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<div  class="col-sm-12">
	<h3>Patient Notifications</h3>
	<hr/>
	<div class="container">
		<?php if (!empty($notifications)) : ?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php foreach ($notifications as $key => $notification) :?>
					<?php 
						$expanded = $key == 0 ? 'true' : 'false';
						$collapse = $key == 0 ? 'in' : '';
					?>
					<div class="panel panel-default">
					  <div class="panel-heading" role="tab" id="heading<?=$key?>">
					    <h4 class="panel-title">
					      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>" aria-expanded="<?=$expanded?>" aria-controls="collapse<?=$key?>">
					        Dr. <?=$notification['doctorName']?> <?=$notification['created']?>
					      </a>
					    </h4>
					  </div>
					  <div id="collapse<?=$key?>" class="panel-collapse collapse <?=$collapse?>" role="tabpanel" aria-labelledby="heading<?=$key?>">
					    <div class="panel-body">
					      <?=$notification['message']?>
					    </div>
					  </div>
					</div>
				<?php endforeach; ?>
			</div>
	<?php else: ?>
			<div class="alert alert-info">
				No notifications found.
			</div>
	<?php endif;?>
	</div>
</div>

<form action="/search&Session_ID=<?=$Session_ID?>" method="post" id="specialty-form">
	
	<input type="hidden" name="speciality" value="1" id="speciality1">
	<input type="hidden" name="lat" id="lat1" value="">
	<input type="hidden" name="lng" id="lng1" value="">
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$('#book_dr').click(function () {
	     	var value = $(this).attr("id");
           	$('#specialty-form').submit();
        	
    	});

	});
</script>