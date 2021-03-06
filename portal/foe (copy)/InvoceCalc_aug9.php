<?php 
	require_once("header.php");
	?>
	
	<!---------------------------------- step process-------------------- -->
	<style>
	body {
		//margin-top:40px;
	}
	.stepwizard-step p {
		margin-top: 10px;
	}
	.stepwizard-row {
		display: table-row;
	}
	.stepwizard {
		display: table;
		width: 50%;
		position: relative;
	}
	.stepwizard-step button[disabled] {
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
	}
	.stepwizard-row:before {
		top: 14px;
		bottom: 0;
		position: absolute;
		content: " ";
		width: 100%;
		height: 1px;
		background-color: #ccc;
		z-order: 0;
	}
	.stepwizard-step {
		display: table-cell;
		text-align: center;
		position: relative;
	}
	.btn-circle {
		width: 30px;
		height: 30px;
		text-align: center;
		padding: 6px 0;
		font-size: 12px;
		line-height: 1.428571429;
		border-radius: 15px;
	}
	</style>
	
	<?php
	require_once("../ConvertNum2Words.php");
	//print_r($id);
	//Invoice Date
	$inv_dt = time();
	
	//Quarter calculation
	$qDate = odbc_exec($conn, "SELECT [Start Date], [End Date] FROM [Academic Year] WHERE [Code]='$AcadYear' AND [Company Name]='$ms'");
	$q1Sdt = strtotime(odbc_result($qDate, "Start Date"));
	$q1Edt = strtotime("+3 months -1 day", strtotime(odbc_result($qDate, "Start Date")));
	$q2Sdt = strtotime("+3 months", strtotime(odbc_result($qDate, "Start Date")));
	$q2Edt = strtotime("+6 months -1 day", strtotime(odbc_result($qDate, "Start Date")));
	$q3Sdt = strtotime("+6 months", strtotime(odbc_result($qDate, "Start Date")));
	$q3Edt = strtotime("+9 months -1 day", strtotime(odbc_result($qDate, "Start Date")));
	$q4Sdt = strtotime("+9 months", strtotime(odbc_result($qDate, "Start Date")));
	$q4Edt = strtotime(odbc_result($qDate, "Start Date"));
	//echo $q1Edt;
	//Get Quarter as per current date
	
	if($inv_dt >= $q1Sdt && $inv_dt <= $q1Edt ) {$Qtr = 2;}
	elseif($inv_dt >= $q2Sdt && $inv_dt <= $q2Edt ) {$Qtr = 3;}
	elseif($inv_dt >= $q3Sdt && $inv_dt <= $q3Edt ) {$Qtr = 4;}
	elseif($inv_dt >= $q4Sdt && $inv_dt <= $q4Edt ) {$Qtr = 5;}
	else{
		$Qtr = 2;
	}
	
	//Get Sequence No
	$seq = odbc_exec($conn, "SELECT MAX([Posting No])+1 AS [Posting] FROM [Ledger Credit] WHERE [Company Name]='$ms'") or die(odbc_errormsg($conn));
	$inv = (odbc_result($seq, "Posting") <> ""?odbc_result($seq, "Posting"): 1 );
	
	$inv_no = str_pad($inv, 10, "0", STR_PAD_LEFT );
	//print_r($inv_no);
	
	//System Genrated No.
	$sys_no = odbc_exec($conn, "SELECT [System Genrated No_] FROM [Temp Application] WHERE [Enquiry No_]='".$id."' AND [Company Name]='$ms' ") or die(odbc_errormsg($conn));
	$cust_no = odbc_result($sys_no, "System Genrated No_");
	//print_r($cust_no);
?>
<!------------------------------------------------------------------------------>
<div class="container">	
	<h1 class="text-primary">Admission<small> - Fees &#38; Discount</small></h1>
 	
 	 <!---------------------------------- step process-------------------- -->
  <div class="stepwizard col-md-offset-3">
    <div class="stepwizard-row setup-panel">
      <div class="stepwizard-step">
        <a href="#step-1" type="button" class="btn btn-default btn-circle" disabled="disabled">1</a>
        <p style="font-weight: bold; color:red;">Information</p>
      </div>
      <div class="stepwizard-step">
        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
        <p style="font-weight: bold; color:red;">Fee & Discount</p>
      </div>
      <div class="stepwizard-step">
        
        <a href="#step-3" type="button" class="btn btn-primary btn-circle">3</a>
        <p style="font-weight: bold; color:red;">Confirm</p>
      </div>
      
      </div>
  </div>
  
  
   <!---------------------------------- step process-------------------- -->
 	
 	<div class="tab-content" id="StuTabContent">
				
		<div class="tab-pane face in active" id="StuTab2" >
			<table id="results2" class="table table-responsive">
	<tr style="font-weight: bold;">
		<td style="text-align: center;">SN</td>
		<td>Description</td>
		<td>Amount</td>
		<td style="text-align:center">Discounted Amount</td>
		<td style="text-align:center">Net Payable Amount</td>
	</tr>
	
	<?php
	
		$today = $inv_dt;
		$this_yr = strtotime(date("Y", $today)."-04-01");
		$nxt_yr = strtotime((date("Y", $today)+1)."-03-31");
		
		$this_yr = strtotime(date("Y", $today)."-04-01");
		$nxt_yr = strtotime((date("Y", $today)+1)."-03-31");
		
		//Financial Year Calculation
		if($today > strtotime(date("Y", $today)."-04-01") && $today < strtotime((date("Y", $today)+1)."-03-31")){
		    $FinYr = date('y', $today)."-".(date('y', $today)+1);
		}
		
		//Q1 Calculation
		if($today > strtotime(date("Y", $today)."-04-01") && $today < strtotime((date("Y", $today))."-06-30")){
		    $Qtr = "Q1";
		}
		//Q2 Calculation
		if($today > strtotime(date("Y", $today)."-07-01") && $today < strtotime((date("Y", $today))."-09-30")){
		    $Qtr = "Q2";
		}
		//Q3 Calculation
		if($today > strtotime(date("Y", $today)."-10-01") && $today < strtotime((date("Y", $today))."-12-31")){
		    $Qtr = "Q3";
		}
		//Q1 Calculation
		if($today > strtotime(date("Y", $today)."-01-01") && $today < strtotime((date("Y", $today))."-03-31")){
		    $Qtr = "Q4";
		}
		
		//Half Yearly Calculation
		
		//H1 Calculation
		if($today > strtotime(date("Y", $today)."-04-01") && $today < strtotime((date("Y", $today))."-09-30")){
		    $Hfl = "H1";
		}
		//H2 Calculation
		if($today > strtotime(date("Y", $today)."-10-01") && $today < strtotime((date("Y", $today))."-03-31")){
		    $Qtr = "H2";
		}
		
		//End of Calculation
	
		$f=1;
		$gTotal = 0;
		for($f=0; $f<=$_REQUEST['fee_count']; $f++){
			if($_REQUEST['fee'.$f] != ""){
				$rs = odbc_exec($conn, "SELECT * FROM [Class Fee Line] WHERE [Company Name]='$CompName' AND [id]='".$_REQUEST['fee_Id'.$f]."' ") or die(odbc_errormsg($conn));
				while(odbc_fetch_array($rs)){
			
			
			//Fee INSERT
			/*echo "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
					[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount])
					VALUES('$inv_dt', '$inv_no', '$cust_no', '". odbc_result($rs, "Description")."', '".odbc_result($rs, "Total Amount")."', '-', 0, 0, '$LoginID', '$LoginID', '$ms', '', 0)";
					*/
					$one_ins = "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
					[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount], [FinYr], [Month], [Year], [Qtr])
					VALUES('$inv_dt', '$inv_no', '$cust_no', '". odbc_result($rs, "Description")."', '".odbc_result($rs, "Total Amount")."', '-', 0, 0, '$LoginID', '$LoginID', '$ms', '', 0, '$FinYr', '".date('m', $today)."', '".date('Y', $today)."', '$Qtr')";
					odbc_exec($conn, $one_ins) or exit(odbc_errormsg($conn)); // OneTime
					
		?>
		<tr style="font-weight: bold;">
		<td style="text-align: center;"><?php echo $f;?></td>
		<td><?php echo odbc_result($rs, "Description");?></td>
		<td><?php echo number_format(odbc_result($rs, "Total Amount"),2,'.',','); ?></td>
		<td></td>
		<td></td>	
		</tr>
		<?php			
			
			$new_amt = odbc_result($rs, "Total Amount");
			
			for($d=0; $d<=$_REQUEST['Dis_count']; $d++){
				$e=0;
					if($_REQUEST['discount'.$d] != ""){
					$disc_fee_hdr = odbc_exec($conn, "SELECT [No_] FROM [Discount Fee Header] WHERE [id]='".$_REQUEST['discount_Id'.$d]."' AND [Company Name]='$CompName'") or die(odbc_errormsg($conn));
					$disc_fee_line = odbc_exec($conn, "SELECT [Description], [Discount%], [Document No_] FROM [Discount Fee Line] WHERE [Company Name]='$CompName' AND [Fee Code]='".odbc_result($rs, "Fee Code")."' AND [Academic Year]='$AdmissionYear' AND [Document No_]='".odbc_result($disc_fee_hdr, "No_")."' ") or die(odbc_errormsg($conn));
					if(odbc_result($disc_fee_line, "Description") != ""){
						$e = ($new_amt * odbc_result($disc_fee_line, "Discount%"))/100;
						$new_amt = $new_amt - $e;
						
						
						//Discount Fee INSERT
						/*
						echo "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
						[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount])
						VALUES('$inv_dt', '$inv_no', '$cust_no', '".odbc_result($disc_fee_line, "Description")."', 0, '".odbc_result($disc_fee_line, "Document No_")."', $e, 0, '$LoginID', '$LoginID', '$ms', '', 0)";
						*/
						$Discount = "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
						[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount], [FinYr], [Month], [Year], [Qtr])
						VALUES('$inv_dt', '$inv_no', '$cust_no', '".odbc_result($disc_fee_line, "Description")."', 0, '".odbc_result($disc_fee_line, "Document No_")."', $e, 0, '$LoginID', '$LoginID', '$ms', '', 0, '$FinYr', '".date('m', $today)."', '".date('Y', $today)."', '$Qtr')";
						odbc_exec($conn, $Discount) or exit(odbc_errormsg($conn)); // OneTime
						
						
						echo "<tr style='text-decoration: italics; '><td></td>";
						echo "<td>".odbc_result($disc_fee_line, "Description")." // ". odbc_result($disc_fee_line, "Document No_") ."</td>";
						echo "<td></td>";
						//echo "<td style='text-align:center;'>".number_format(odbc_result($disc_fee_line, "Discount%"),0,'.',',')."%</td>";
						echo "<td style='text-align:right;'>".number_format($e,0,'.',',')."</td>";
						echo "<td>";
						
						
						//echo $e;
						//echo number_format(odbc_result($disc_fee_line, "Discount%"),2,'.',',');
						echo "</td></tr>";
						}
					}
			}
		echo "<tr style='font-weight: bold;'><td></td><td>Net ".odbc_result($rs, "Description")." payable </td><td></td><td></td>";
		echo "<td style='text-align:right;'>".number_format($new_amt,0,'.',',')."</td>";
		echo "</tr>";
		
		
		// Net payable INSERT
		/*
		echo "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
		[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount])
		VALUES('$inv_dt', '$inv_no', '$cust_no', 'Net ".odbc_result($rs, "Description")." payable', 0, '', 0, $new_amt, '$LoginID', '$LoginID', '$ms', '', 0)";
		*/
		
		$Net_payable = "INSERT INTO [Ledger Invoice]([Invoice Date], [Invoice No], [Customer No], [Fee Description], [Amount],
		[Discount Code1],[Discount Code1 Amount], [Net Amount],[User ID], [Portal ID], [Company Name], [Discount Code2], [Discount Code2 Amount], [FinYr], [Month], [Year], [Qtr])
		VALUES('$inv_dt', '$inv_no', '$cust_no', 'Net ".odbc_result($rs, "Description")." payable', 0, '', 0, $new_amt, '$LoginID', '$LoginID', '$ms', '', 0, '$FinYr', '".date('m', $today)."', '".date('Y', $today)."', '$Qtr')";
		odbc_exec($conn, $Net_payable) or exit(odbc_errormsg($conn)); // OneTime
		
		
		$gTotal = $gTotal + $new_amt;
			}
			}
			
		}
		/*
		echo "INSERT INTO [Ledger Credit]([Invoice Date], [Invoice No], [Customer No], [Description],
		[Credit Amount], [Company Name], [User ID], [Portal ID], [Posting No], [Adv Fee])
		VALUES
		('$inv_dt', '$inv_no', '$cust_no', 'Admission & Other Fees', $gTotal, '$ms', '$LoginID',
		'$LoginID',$inv,0)";
		*/
	
		$InvCrd = "INSERT INTO [Ledger Credit]([Invoice Date], [Invoice No], [Customer No], [Description],
		[Credit Amount], [Company Name], [User ID], [Portal ID], [Posting No], [Adv Fee], [FinYr], [Month], [Year], [Qtr])
		VALUES
		('$inv_dt', '$inv_no', '$cust_no', 'Admission & Other Fees', $gTotal, '$ms', '$LoginID',
		'$LoginID',$inv, 0, '$FinYr', '".date('m', $today)."', '".date('Y', $today)."', '$Qtr')";
		odbc_exec($conn, $InvCrd) or exit(odbc_errormsg($conn));
		
		?>
	<tr style="font-weight: bold;">
		<td colspan="2">TOTAL</td>
		<td></td>
		<td></td>
		<td  style='text-align:right;'><?php echo number_format($gTotal,0,'.',','); ?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000;" colspan="5">				
			Amount : <b>Rupees <?php echo strtoupper(convert_number_to_words(round($gTotal)));?> Only</b>
		</td>
	</tr>
</table>

		</div>
		
	</div>
	
	
</div>

<script>
	var childWindows = [];
	
	var win = window.open("ReceiptAdmission.php?id=<?php echo $cust_no?>&ms=<?php echo $ms?>&inv=<?php echo $inv_no ?>","windowName", "width=900,height=500,scrollbars=no");
	win.focus();
	childWindows.push(win);	
	
</script>
<!--?php 
if($InvCrd){
		echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Generate invoce successfully Please go Fee menu And Collect Invoce Receipt')
    window.setTimeout('history.go(-3)', 5000);
  
    </SCRIPT>");
	}
	// window.history.go(-2);
	?-->
 
