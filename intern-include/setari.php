       <div class="pageheader">
            <div class="pageicon"><span class="iconfa-cog"></span></div>
            <div class="pagetitle">
                <h5>Schimba-ti datele de la cont</h5>
                <h1>Setari de cont</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <h4 class="widgettitle">Date generale</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2">
						    <p>
                                <label>Nume</label>
                                <span class="field">
                                     <input type="text" class="form-control input-default" id="exampleInputEmail1" readonly value="<?php echo $_SESSION['intern-name']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Email</label>
                                <span class="field">
                                     <input type="text" class="form-control input-default" id="exampleInputEmail1" readonly value="<?php echo $_SESSION['intern-email']; ?>">
                                </span>
                            </p>
                    </form>
                </div>
				
				<div class="row">
				<div id="dashboard-left" class="col-md-6">
				<?php if($_POST['submit']=="y"){ echo save_settings_data($_POST['telefon'],$_POST['datanasterii'], $_POST['csrf']); } ?>
                <h4 class="widgettitle">Date personale</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
						    <p>
                                <label>Numar de telefon<br /><small>07XXXXXXXX</small></label>
                                <span class="field">
                                     <input name="telefon" type="text" class="form-control input-default" id="exampleInputEmail1" value="<?php echo $_SESSION['intern-tel']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Data nasterii<br /><small>AAAA-LL-ZZ</small></label>
                                <span class="field">
                                     <input name="datanasterii" type="text" class="form-control input-default" id="exampleInputEmail1" value="<?php echo $_SESSION['intern-dn']; ?>">
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit" value="y"><button class="btn btn-primary">Modifica datele</button>
                            </p>
                    </form>
                </div>
				</div>
				
				<div id="dashboard-left" class="col-md-6">
				<?php if($_POST['submit2']=="y"){ echo save_best_settings_data($_POST['databest'],$_POST['generatie'],$_POST['csrf']); } ?>
                <h4 class="widgettitle">Detalii generatie</h4>
                <div class="widgetcontent nopadding">
                    <form class="stdform stdform2" method="post">
						    <p>
                                <label>Cand ai intrat in BEST<br /><small>luna si anul</small></label>
                                <span class="field">
                                     <input name="databest" type="text" class="form-control input-default" id="exampleInputEmail1" value="<?php echo $_SESSION['intern-databest']; ?>">
                                </span>
                            </p>
						    <p>
                                <label>Numele generatiei tale</label>
                                <span class="field">
                                     <input name="generatie" type="text" class="form-control input-default" id="exampleInputEmail1" value="<?php echo $_SESSION['intern-generatie']; ?>">
                                </span>
                            </p>
                            <p class="stdformbutton">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['intern-csrf']; ?>"><input type="hidden" name="submit2" value="y"><button class="btn btn-primary">Modifica datele</button>
                            </p>
                    </form>
                </div>				
				</div>
				</div>
