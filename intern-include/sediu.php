       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-user"></span></div>
            <div class="pagetitle">
		<h5>Vezi daca e cineva in sediu pe baza calculatorului</h5>
                <h1>E cineva in sediu?</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
				<div class="maincontentinner">
<div class="row">
				
					<div id="dashboard-left" class="col-md-8">
						<h4 class="widgettitle">Descriere</h4>
						<div class="widgetcontent">
Prin aceasta aplicatie, se poate vedea daca este cineva in sediu, pe baza calculatorului.<br /><br />
<strong>Cum functioneaza</strong><br />
Pe calculatorul din sediu ruleaza o aplicatie care trimite ping la HeRo din minut in minut. Pe baza acestui ping, se calculeaza informatiile din dreapta, astfel incat:<br /><br />
&bull; Daca calculatorul a fost deschis in ultimele 5 minute, apare mesajul <span style="color:green;">e cineva</span><br />
&bull; Daca nu, apare mesajul <span style="color:red;">nu e nimeni</span><br /><br />
Daca esti interesat de codul sursa, poti intra <a href="https://github.com/dragosgaf/BEST-is-someone-in-the-office" target="_blank">aici</a>.
</div></div>

<div id="dashboard-left" class="col-md-4">
						<h4 class="widgettitle">Status</h4>
						<div class="widgetcontent">
<?php
$fh = file_get_contents("lastcheck.txt");
if(time() <= $fh + 60*5)
{
	$status = '<span style="color:green;">e cineva</span>';
}else{
	$status = '<span style="color:red;">nu e nimeni</span>';
}
$verificare = return_time($fh);
 ?>
<strong>Status:</strong> <?php echo $status; ?><br />
<strong>Ultima verificare:</strong> <?php echo $verificare; ?>
</div>
					
					
				</div> </div>
