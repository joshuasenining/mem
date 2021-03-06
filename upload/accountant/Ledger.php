<?php require_once("header.php");?>
<div class="container">
	<h2 class="text-primary">Ledger Details</h2>
	<div class="row">
        <div class="col-md-8 col-md-offset-2">
	<form action="#" method="get">
	    <table class="table table-responsive">
                <tr>
                    <td>Enter Customer Name / No.</td>
                    <td><input type="text" name="invoice" class="form-control" placeholder="Customer Name / Customer No" autofill="false" required></td>
                    <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                </tr>
	    </table>
	    </form>
	</div>
	</div>
	
	<table class="table table-responsive">
		<thead>
			<tr>
                            <th>SN</th>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Academic Year</th>
                            <th>Class</th>
                            <th style="text-align: center;">Fee Amount</th>
                            <th style="text-align: center;">Paid</th>
                            <th style="text-align: center;">O/S Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php   // invoice no
                        $query2 = "SELECT * FROM [Ledger Credit] WHERE [Company Name]='$ms' AND ([Invoice No]='".$_GET['invoice']."')";
                        $rs2 = odbc_exec($conn, $query2) or die(odbc_errormsg($conn));	
                       //admission no
                        $query1 = "SELECT * FROM [Temp Student] WHERE [Company Name]='$ms' AND ([No_]='".$_GET['invoice']."')";
                        $rs1 = odbc_exec($conn, $query1) or die(odbc_errormsg($conn));	
			$i = 1;
			if($_SERVER['REQUEST_METHOD'] == 'GET' && $_REQUEST['invoice'] != ""){
				$query = "SELECT * FROM [Temp Application] WHERE [Company Name]='$ms' AND ([System Genrated No_]='".$_GET['invoice']."' OR [System Genrated No_]='".odbc_result($rs1, "Registration No_")."' OR [System Genrated No_]='".odbc_result($rs2, "Customer No")."' OR [Name] LIKE '%".$_GET['invoice']."%')";
				
				$rs = odbc_exec($conn, $query) or die(odbc_errormsg($conn));
				while(odbc_fetch_array($rs)){
		        ?>
		    <tr>
			<td><?php echo $i?></td>
                        <?php $Admission = odbc_exec($conn, "SELECT [No_] FROM [Temp Student] WHERE [Registration No_]='".odbc_result($rs, "System Genrated No_")."' AND [Company Name]='$ms'") or die(odbc_errormsg($conn)); 
			$AdmissionNo = odbc_result($Admission, "No_"); ?>
			<td><a href="LedgerDetails.php?CustomerNo=<?php echo odbc_result($rs, "System Genrated No_")?>"><?php echo $AdmissionNo;?></a></td>
			<!--td><a href="LedgerDetails.php?CustomerNo=<--?php echo odbc_result($rs, "System Genrated No_")?>"><--?php echo odbc_result($rs, "System Genrated No_");?></a></td-->
                        <td><?php echo odbc_result($rs, "Name");?></td>
			<td><?php 
				if(odbc_result($rs, "Gender")==1) echo "Boy";
				if(odbc_result($rs, "Gender")==2) echo "Girl";
			?></td>
			<td><?php echo odbc_result($rs, "Academic Year");?></td>
			<td><?php echo odbc_result($rs, "Class");?></td>
			<td style="text-align: right;">
				<?php 
					$fee_amt = odbc_exec($conn, "SELECT SUM([Credit Amount]) FROM [Ledger Credit] WHERE [Company Name]='$ms' AND [Customer No]='".odbc_result($rs, "System Genrated No_")."'") or die(odbc_errormsg($conn));
					echo number_format(odbc_result($fee_amt, ""), 2, '.', '');
				?>
			</td>
			<td style="text-align: right;">
				<?php 
					$pay_amt = odbc_exec($conn, "SELECT SUM([Debit Amount]+[Adv Fee]) FROM [Ledger Debit] WHERE [Company Name]='$ms' AND [Customer No]='".odbc_result($rs, "System Genrated No_")."'") or die(odbc_errormsg($conn));
					echo number_format(odbc_result($pay_amt, ""), 2, '.', '');
				?>
			</td>
			<td style="text-align: right;">
				<?php 
					echo number_format(odbc_result($fee_amt, ""), 2, '.', '') - number_format(odbc_result($pay_amt, ""), 2, '.', ''); 
				?>
			</td>
		    </tr>
                    <?php 
                    $i++;
                    }
                    }
                    ?>
		</tbody>
	</table>
</div>
<?php require_once("../footer.php"); ?>