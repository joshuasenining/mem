<?php
	require_once("header.php");
	$e=isset($_REQUST['e']);
	$Enqr=isset($_REQUEST['enq']);
	if($Enqr != ""){
		if($e=1){
			echo "<div class='container'>
				<div class='bs-example'>
					<div class='alert alert-success alert-error'>
						<a href='#' class='close' data-dismiss='alert'>&times;</a>
						<strong>Success!</strong> User enquiry has been updated.
					</div>
				</div>
			</div></div>";
		}
		if($e=0){
			echo "<div class='container'>
				<div class='bs-example'>
					<div class='alert alert-danger alert-error'>
						<a href='#' class='close' data-dismiss='alert'>&times;</a>
						<strong>Error!</strong> There is some error, kindly check.
					</div>
				</div>
				</div>";
		}
	}
	    $row = odbc_exec($conn, "SELECT * FROM [Temp Student] WHERE [Company Name]='$ms' AND [No_]='$id'") or die(odbc_errormsg($conn));
	    $cust = odbc_exec($conn, "SELECT * FROM [Temp Customer] WHERE [Company Name]='$ms' AND [No_]='$id'") or die(odbc_errormsg($conn));
	    
	    $StuDOB = date("Y-m-d", strtotime(str_replace("-", "/", odbc_result($row, "Date Of Birth"))));
	    $today = date("Y-m-d");


    $diff = abs(strtotime($today) - strtotime($StuDOB));

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	if($_GET['ePage']=="" || $_GET['ePage']==1){
		$e_min=0;
		$e_max=50;
		$ePrev = 0;
	}
	else{
		$eCurr = $_GET['ePage'];
		$e_max=50*$eCurr;
		$e_min = ($e_max - 50);
	}
?>

<div class="container" style="overflow-x: hidden;">
	<h2 class="text-primary" align="center"><?php echo $_GET['cmp']?> </h2>
	<h3 class="text-primary">Student List for <?php echo $_GET['c']?> - <?php echo $_GET['s']?></h3>
	<div class="table-responsive">
		<table class="table table-striped table-hover  sticky-header">
			<form name="form" method="get">
			<input type="hidden" name="c"  value="<?php echo $_GET['c'];?>" />
			<input type="hidden" name="y"  value="<?php echo $_GET['y'];?>" />
			<input type="hidden" name="cmp"  value="<?php echo $_GET['cmp'];?>" />
			<input type="hidden" name="s"  value="<?php echo $_GET['s'];?>" />
			<tr style="background-color: #ffffff;">
				<td colspan="12" align="right"> Go to page
					<select name="ePage" onchange="this.form.submit()">						
						<?php							
							$enqCount=odbc_exec($conn, "SELECT (COUNT([No_])/50)+1 FROM [Temp Student] WHERE  [Company Name]='".$_GET['cmp']."' AND [Admission For Year]='".$_GET['y']."' AND [Class] LIKE '".$_GET['c']."' AND [Section] LIKE '".$_GET['s']."%' AND [Student Status]=1") or die(odbc_errormsg($conn));
							for($e=1; $e<=odbc_result($enqCount,""); $e++){
								echo "<option value='$e' ";
								if($e == $_GET['ePage']) echo "selected";
								echo ">$e</option>";
							}
						?>
					</select>
				</td>
			</tr>
			</form>
			<thead>
				<tr style="background-color: #d3d3d3; font-weight: bold;">
					<td>SN</td>
					<td>Admission No.</td>
					<td>Student Name</td>
					<td>Academic Year</td>
					<td>Class & Section</td>
					<td>Father's Name</td>
					<td>Contact No</td>
					<td>Date Joined</td>
				</tr>
			</thead>
			<tbody>
				<?php
				
					$i=1;
					$result=odbc_exec($conn, "SELECT *   FROM ( 
							SELECT *, ROW_NUMBER() OVER (ORDER BY [Date Joined] DESC) as row FROM [Temp Student] WHERE  [Company Name]='".$_GET['cmp']."' AND [Admission For Year]='".$_GET['y']."' AND [Class] LIKE '".$_GET['c']."' AND [Section] LIKE '".$_GET['s']."%' AND [Student Status]=1
														) a WHERE a.row > $e_min and a.row <= $e_max ") or die(mysql_error());
					if(!$result){
						exit("Error in SQL execution...");
					}
					while(odbc_fetch_row($result)){
				?>
				<tr style="font-size: 12px;">
					<td><?php echo odbc_result($result, "row");?></td>
					 <td>
						<div class="bs-example">
							
							<a href="RptStudentStrength.php?id=<?php echo odbc_result($result, "No_")?>#myModal<?php echo $i?>" class="text-primary" data-toggle="modal"><?php echo odbc_result($result, "No_")?></a>
							 <?php
								require("ModalEnquiry.php");
							?>
						</div>
					</td>
					<td><?php echo odbc_result($result, "Name"); ?></td>
					<td><?php echo odbc_result($result, "Academic Year"); ?></td>
					<td><?php echo odbc_result($result, "Class")." ".odbc_result($stu3, "Section"); ?></td>
					<td><?php echo odbc_result($result, "Father_s Name"); ?></td>
					<td><?php echo odbc_result($result, "Mobile Number"); ?></td>
					<td><?php echo date('d/M/Y', strtotime(odbc_result($result, "Date Joined"))); ?></td>
				         
				</tr>
				<?php
						$i += 1;
					}
					
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
	require_once("../footer.php");
?>