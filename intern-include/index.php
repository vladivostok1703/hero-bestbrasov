       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-laptop"></span></div>
            <div class="pagetitle">
                <h5>Bine ai venit pe HeRo</h5>
                <h1>Bun venit</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
				<div class="row">
				
				
					<div id="dashboard-left" class="col-md-4">

						<h4 class="widgettitle"><span class="iconfa-comments"></span> Ultimele sedinte</h4>
						         <div class="widgetcontent nopadding"  style="overflow-x: hidden;overflow-y: auto;height:200px;">
								    <ul class="commentlist">
<?php
$sql = $mysql->query("select * from sedinte order by data desc limit 0,5");
if($sql->num_rows > 0)
{
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
?>
								       <li>
									    <div class="comment-info" style="margin-left:0px;">
										<h4><b><a href="dashboard.php?pagina=sedinte&show=<?php echo $f['ID']; ?>" ><?php echo $f['nume']; ?></a></b></h4>
										<p><b>Data:</b> <?php echo ddate($f['data']); ?></p>
									    </div>
									</li>
<?php
	}
}
?>
								    </ul>
								</div>
						

						<h4 class="widgettitle"><span class="iconfa-group"></span> Ultimele evenimente</h4>
						         <div class="widgetcontent nopadding"  style="overflow-x: hidden;overflow-y: auto;height:200px;">
								    <ul class="commentlist">
<?php
$sql = $mysql->query("select * from evenimente order by datai desc limit 0,5");
if($sql->num_rows > 0)
{
	while($f = $sql->fetch_array(MYSQLI_ASSOC))
	{
?>
								       <li>
									    <div class="comment-info" style="margin-left:0px;">
										<h4><b><a href="dashboard.php?pagina=evenimente&show=<?php echo $f['ID']; ?>" ><?php echo $f['nume']; ?></a></b></h4>
										<p><b>Perioada:</b> <?php echo ddate($f['datai']); ?> - <?php echo ddate($f['dataf']); ?></p>
									    </div>
									</li>
<?php
	}
}
?>
								    </ul>
								</div>			
					</div>
				
					<div id="dashboard-left" class="col-md-4">
						<h4 class="widgettitle"><span class="iconfa-envelope"></span> Adresele noastre de email</h4>
               <table class="table responsive">
                    <thead>
                        <tr>
                            <th>Adresa</th>
                            <th>Scop</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="#">bvb@BEST.eu.org</a></td>
                            <td>Toti membrii LBG-ului</td>
                        </tr>
                        <tr>
                            <td><a href="#">bva@BEST.eu.org</a></td>
                            <td>Toti membrii activi</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-mdv@googlegroups.com</a></td>
                            <td>Toti membrii cu drept de vot</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-mentori@googlegroups.com</a></td>
                            <td>Toti mentorii ultimei generatii</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-management@googlegroups.com</a></td>
                            <td>Toti membrii din management</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-board@BEST.eu.org</a></td>
                            <td>Toti membrii din board</td>
                        </tr>
                        <tr>
                            <td><a href="#">brasovalumni@BEST.eu.org</a></td>
                            <td>Toti membrii alumni</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-former@googlegroups.com</a></td>
                            <td>Toti membrii former</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-fr@googlegroups.com</a></td>
                            <td>Departamentul FR</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-it@googlegroups.com</a></td>
                            <td>Departamentul IT</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-hr@googlegroups.com</a></td>
                            <td>Departamentul HR</td>
                        </tr>
                        <tr>
                            <td><a href="#">bv-mkt@googlegroups.com</a></td>
                            <td>Departamentul MKT</td>
                        </tr>
		    </tbody>
		</table>

					</div>
					

					
					<div id="dashboard-left" class="col-md-4">

					<?php
					if($_SESSION['intern-dn'] == "" || $_SESSION['intern-tel'] == "" || $_SESSION['intern-databest'] == "" || $_SESSION['intern-generatie'] == "")
					{
					?>
					    <div class="alert alert-block">
                              <p style="margin: 8px 0">Intra <a href="dashboard.php?pagina=setari">aici</a> si actualizeaza-ti datele.</p>
                        </div>
					<?php
					}
					?>
						
						<h4 class="widgettitle"><span class="iconfa-star"></span> Sarbatoritii zilei</h4>
                        <div class="widgetcontent">
                            <?php echo get_birthdays(time()); ?><br /><br /><span class="iconfa-mail-forward"></span> <a href="dashboard.php?pagina=viitoriisarbatoriti">Vezi viitorii sarbatoriti</a>
                        </div>							
					
						<h4 class="widgettitle"><span class="iconfa-wrench"></span> Aplicatiile mele</h4>
						<div class="widgetcontent">
						    <span class="iconfa-user"></span> <a href="dashboard.php?pagina=sediu">E cineva in sediu?</a><br />
						    <span class="iconfa-user"></span> <a href="dashboard.php?pagina=contacte">Importare numere de telefon</a><br />
						</div>

						<h4 class="widgettitle"><span class="iconfa-book"></span> Arhivele noastre</h4>
						<div class="widgetcontent">
						    <span class="iconfa-folder-open"></span> <a href="https://drive.google.com/drive/folders/0B-9rqB5TtibhMjJRaFdwU2oxWWs" target="_blank">Arhiva generala</a><br />
						    <span class="iconfa-folder-open"></span> <a href="https://drive.google.com/drive/folders/0B0ZVbw0XO9oKTzBXMzlKWjlUZ0U" target="_blank">Arhiva departamentului FR</a><br />
						    <span class="iconfa-folder-open"></span> <a href="https://drive.google.com/drive/folders/0Bw3jaWmzjOtvSUstVE9xSGlsT1U" target="_blank">Arhiva departamentului IT</a><br />
						    <span class="iconfa-folder-open"></span> <a href="https://drive.google.com/drive/folders/0B8fk6xLqVu8aWUY3Smt2Si1MbW8" target="_blank">Arhiva departamentului MKT</a><br />
						</div>

						<h4 class="widgettitle"><span class="iconfa-bookmark-empty"></span> Guidelines</h4>
						<div class="widgetcontent">
						    <span class="iconfa-bookmark"></span> <a href="https://drive.google.com/open?id=1ufpQR6Td1LkEsgn3-Vr81J4D6VtPv0N8PVIi1GGULoY" target="_blank">Guideline FRozen</a><br />
						    <span class="iconfa-bookmark"></span> <a href="https://docs.google.com/document/d/1oD6qOk-GYunWP8Ia_bu0fACEfSNLhryQNEiBUjitpfg/edit?usp=sharing" target="_blank">Guideline HeRo</a><br />
						    <span class="iconfa-bookmark"></span> <a href="https://drive.google.com/open?id=1JD762RtGj5pSD6qsJyS1BVEDfgYwQWo57pN4IrEYhGU" target="_blank">Guideline Private Area</a><br />
						    <span class="iconfa-bookmark"></span> <a href="https://drive.google.com/open?id=1E-WQRIoEgNrLn_Ke5uTDPgOZ_4e2aJJDQn5T-QraDW8" target="_blank">Guideline Wordpress</a><br />
						</div>
				</div></div>
