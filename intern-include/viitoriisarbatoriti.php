       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-star"></span></div>
            <div class="pagetitle">
                <h5>Zile de nastere in urmatoarele 30 de zile</h5>
                <h1>Viitorii sarbatoriti</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                                      <h4 class="widgettitle">Viitorii sarbatoriti</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">
					<?php
					for($i=0;$i<30;$i++)
					{
						$time = time() + $i * 86400;
						?>
						    <p>
                                <label><?php echo cdate(date("d.m.Y",$time)); ?></label>
                                <span class="field">
                                    <?php echo get_birthdays($time); ?>
                                </span>
                            </p>
						<?php
					}
					?>
                    </form>
                </div><!--widgetcontent-->
				