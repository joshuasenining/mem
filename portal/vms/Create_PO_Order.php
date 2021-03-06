<?php
require_once("Header1.php");

  if ( isset($_GET['success']) && $_GET['success'] == 1)
  {
      echo "Please fill the required fields";
  }

    $inv_dt = time();
    $inv_last_dt = date('t');
    $today = $inv_dt;
    $this_yr = strtotime(date("Y", $today)."-04-01");
    $nxt_yr = strtotime((date("Y", $today)+1)."-03-31");
    $this_yr = strtotime(date("Y", $today)."-04-01");
    $nxt_yr = strtotime((date("Y", $today)+1)."-03-31");

 if($today > strtotime(date("Y", $today)."-04-01") && $today <
            strtotime((date("Y", $today)+1)."-03-31")){
                $FinYr = date('y', $today)."-".(date('y', $today)+1);
        } else if ($today < strtotime(date("Y", $today)."-04-01")  &&
    $today < strtotime((date("Y", $today))."-03-31")) {
        $FinYr = (date('y', $today)-1)."-".date('y', $today);
    }

$fid1=1;
$fid=str_pad($fid1,4,"0",STR_PAD_LEFT);

 $qry="select max(ID) AS ID from [VMS Create PO]";
 $result = odbc_exec($conn, $qry) or die(odbc_errormsg($conn));
 $ltid=odbc_fetch_array($result);
 $resgtid1=$ltid['ID'];
 $resgtid=$resgtid1+1;
 $lpoid=str_pad($resgtid,4,"0",STR_PAD_LEFT);
 if($resgtid>=1)
 {
      $newpoid="PO/".$FinYr."/".$lpoid;
 }
 else 
     
 {
    $newpoid="PO/".$FinYr."/".$fid;
 }
 
  $SQLTAX = "SELECT * FROM [VMS Tax Master]";
  $resulttax = odbc_exec($conn, $SQLTAX) or die(odbc_errormsg($conn));
  
  $SQLServiceTAX = "SELECT * FROM [VMS Service Tax Master]";
  $resultservicetax = odbc_exec($conn, $SQLServiceTAX) or die(odbc_errormsg($conn));
             
  $vdr2="select [Item Name] from [VMS Item Master]";
  $resultvdr2 = odbc_exec($conn, $vdr2) or die(odbc_errormsg($conn));                                                 
?>
 <head>
   
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/i18n/defaults-*.min.js"></script>
 </head>
 <div id="main_area" class="row-fluid">
    <div class="span10 offset1">
        <div id="formAlert" class="alert hide">  
          <a class="close">×</a>  
          <strong>Warning!</strong> Make sure all fields are filled and try again.
        </div>
    
<form action="Create_PO_Order_Add.php" enctype="multipart/form-data" method="POST" name="form">
    
   <div class="container">
    <div class="alert alert-danger" style="display:none;" id="subalert">
            <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
    </div>
     <div class="row">
     
       <div class="col-sm-12 col-md-12">
            <div class="row"> 
                <div class="col-md-8">
                    <h3 class="text-primary">Create Purchase Order</h3>
                </div>
                <div class="col-md-4">
                    <div class="text-danger">
                   <?php if ( isset($_GET['success']) && $_GET['success'] == 1 )
                        {
                             // treat the succes case ex:
                             echo "*Please fill the required fields.";
                        }
                     ?>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">              
                        <ul class="nav nav-tabs" id="Enq">
                           <li class="active"><a href="#EnqTab1" data-toggle="tab">Create PO</a></li>
                           <li><a href="#EnqTab2" data-toggle="tab">ADD Terms & Conditions</a></li>
                       </ul>
                </div>
            </div>    
                       <div class="tab-content" id="EnqContent">
                           <div class="tab-pane face in active" id="EnqTab1">
                                    <table style="margin-top: 10px;">
                              <input type="hidden"  id="rowcount">
                                   <tr class="form-group">
                                     <input type="hidden" name="financialyr" value="<?php echo $FinYr; ?>"/>
                                        <td class="col-md-2">Vendor Code</td>
                                        <td class="col-md-2">
          <select   class="selectpicker form-group" data-live-search="true" name="vendorcode" id="vendorname"  onchange="getaddress();" >
          <option value=""></option>
          <?php
           echo  $vdr="select [VendorCode] from [VMS Vendor Master]";
           $resultvdr = odbc_exec($conn, $vdr) or die(odbc_errormsg($conn));
            while(odbc_fetch_array($resultvdr)){?>
              <option value="<?php echo odbc_result($resultvdr, "vendorCode");?>">
                 <?php echo odbc_result($resultvdr, "vendorCode");?>
              </option>
           <?php  }?>
          ?>
        </select>


                                        </td>
                                   <input type="hidden" name="pono" value="<?php echo $newpoid;?>"/>
                                          <td  class="col-md-2">PO Status</td>
                                          <td  class="col-md-2"> <select name="postatus" class="form-control">
                                                                        <option value="Open">Open</option>
                                                                                                                                           
                                                                      </select> 
                                          </td>
                                          <td class="col-md-2">Vendor</td>
                                          <td class="col-md-2" class="form-control"><input type="textbox" name="vendorsname"  class="form-control" id="vendorsname" readonly="" /></td>
                                          
                                           </tr>

                                    <tr class="form-group">
                                          <td class="col-md-2">Purchase Date</td>
                                          <td class="col-md-2"><input type="text" id="date1" name="purchasedate" class="form-control" readonly=""/></td>
                                          <td class="col-md-2">Requested Received Date</td>
                                          <td class="col-md-2"><input type="text" id="date2" name="needdate" class="form-control" readonly=""/></td>
                                          <td class="col-md-2">Expected Date</td>
                                          <td class="col-md-2"><input type="text" id="date3" name="expecteddate" class="form-control" readonly=""/></td>
                                           
                                    </tr>
                                    
                                    <tr class="form-group">
                                          <td class="col-md-2">Vendor Name</td>
                                          <td class="col-md-2"><input type="text" id="vendorcontactname" name="vendorcontactname" class="form-control" readonly=""/></td>
                                           <td class="col-md-2">Vendor Quote No</td>
                                          <td class="col-md-2"><input type="text" id="vendorquoteno" name="vendorquoteno" class="form-control" /></td>
                                          <td class="col-md-2">Mobile</td>
                                          <td class="col-md-2"><input type="text" id="mobile" name="mobile" class="form-control" readonly=""/></td>
                                          
                                    </tr>
                                 
                                 
                                    <tr class="form-group">
                                          <td class="col-md-2">Email</td>
                                          <td class="col-md-2"><input type="text" name="email" id="email" class="form-control" readonly=""/></td>
                                          <td class="col-md-2">Address</td>
                                          <td class="col-md-2"><input type="text" name="address" id="address"class="form-control" readonly=""/></td>
                                          <td class="col-md-2">Postal Code</td>
                                          <td class="col-md-2"><input type="text" id="postcode" name="postcode" class="form-control"  readonly=""/></td>
                                             
                                    </tr>
                                   
                                    <tr class="form-group">
                                            
                                            <td class="col-md-2">State</td>
                                           <td class="col-md-2"><input type="text" name="state" id="state" class="form-control" readonly=""/></td>
                                            <td class="col-md-2">City</td>
                                            <td class="col-md-2"><input type="text" name="city" id="city" class="form-control" readonly=""/></td>
                                            <td class="col-md-2">Country</td>
                                            <td class="col-md-2"><input type="text" name="country" id="country" class="form-control" readonly=""/></td>
                                            
                                    </tr>
                                    <tr class="form-group">
                                          <td class="col-md-2">Store Code</td>
                                          <td class="col-md-2">
                                          <select   class="selectpicker form-group" data-live-search="true" id="shippingname" name="storecode" class="form-control"  onchange="getaddress1()" >
          <option value=""></option>
          <?php
           echo  $stdr="select [StoreCode] from [VMS Shipping Master]";
           $resultstvdr = odbc_exec($conn, $stdr) or die(odbc_errormsg($conn));
            while(odbc_fetch_array($resultstvdr)){?>
              <option value="<?php echo odbc_result($resultstvdr, "StoreCode");?>">
                 <?php echo odbc_result($resultstvdr, "StoreCode");?>
              </option>
           <?php  }?>
          ?>
        </select>

                                          </td>
                                          <td class="col-md-2">Shipping Contact Name</td>
                                            <td class="col-md-2"><input type="text" name="shippingname" id="shippingcontactname" class="form-control" readonly="" /></td>
                                          <td class="col-md-2">Mobile</td>
                                          <td class="col-md-2"><input type="text" id="shippingmobile" name="shippingmobile" class="form-control" readonly=""/></td>
                                         
                                          
                                    </tr>
                                 
                                 
                                    <tr class="form-group">
                                          <td class="col-md-2">Address</td>
                                          <td class="col-md-2"><input type="text" name="shippingaddress" id="shippingtoaddress" class="form-control" readonly=""/></td>
                                          <td class="col-md-2">Postal Code</td>
                                          <td class="col-md-2"><input type="text" id="shippingpostcode" name="shippingpostcode" class="form-control"  readonly=""/></td>
                                          <td class="col-md-2">Email</td>
                                          <td class="col-md-2"><input type="text" name="shippingemail" id="shippingemail" class="form-control" readonly=""/></td>
                                              
                                    </tr>
                                   
                                    <tr class="form-group">
                                            
                                            <td class="col-md-2">City</td>
                                            <td class="col-md-2"><input type="text" name="shippingcity" id="shippingcity" class="form-control" readonly=""/></td>
                                            <td class="col-md-2">Country</td>
                                            <td class="col-md-2"><input type="text" name="shippingcountry" id="shippingcountry" class="form-control" readonly=""/></td>
                                            <td class="col-md-2">State</td>
                                         <td class="col-md-2"><input type="text" name="shippingstate" id="shippingstate" class="form-control" readonly=""/></td>
                                            
                                    </tr>
                                    
                                    <tr class="form-group">
                                    <td class="col-md-2">Release</td>
                                    <td class="col-md-2"><input type="checkbox" name="release"  /></td>
                                    <td class="col-md-2"><span id="user-availability-status"></span> </td>
                                      <td colspan="4"></td>
                                    </tr>                               
                          

                                </table>
                               <div style="overflow-x: auto">
                               <table class="form-table" id="customFields" style="margin-top:10px;" >
                                    <tr>
                                                        <th>Item Type</th>
                                                        <th>Item Code</th>
                                                        <th>Item Description</th>
                                                        <th style="text-align: center">UOM</th>
                                                        <th>PO Qty</th>
                                                        <th>Unit Price</th>
                                                        <th>VAT/CST</th>
                                                        <th>Service Tax</th>
                                                        <th style="text-align: center">Line Amount</th>
                                                        <th>Warranty</th>
                                                        <th>&nbsp;</th>
                                                        <th>&nbsp;</th>
                                                       
                                                        
                                                        
                                    </tr>
                                    <tr id="1">
                                                           <td><select name="itemtype[]" id="itemtype1">
                                                                      
                                                                        <option value="Item">Item</option>
                                                                        <option value="GL Master">GL Master</option>
                                                            </select>
                                                           </td>
                                                           <td>

  <select    name="itemname[]" id="itemname1" onchange="check_s(1);"  >
        <option value="select">-select-</option>
          <?php
           echo  $vdr1="select [Item Name] from [VMS Item Master]";
           $resultvdr1 = odbc_exec($conn, $vdr1) or die(odbc_errormsg($conn));
            while(odbc_fetch_array($resultvdr1)){?>
              <option value="<?php echo odbc_result($resultvdr1, "Item Name");?>">
                 <?php echo odbc_result($resultvdr1, "Item Name");?>
              </option>
           <?php  }?>
          ?>
     </select>

                                                           </td>
                                                            <td><input type="text" name="specifications[]" readonly id="specifications1" onchange="specifications(<?php echo odbc_result($result,"ID")?> )" />
                                                                <input type="hidden" name="hiddenitemid" value="<?php echo odbc_result($result,"Item ID") ?>"/>
                                                            </td>
                                                            <td><input type="text"  id="uom1"   name="uom[]" readonly size="10" class="items"/> </td>
                                                            <td><input type="text"  id="qty1"   name="qty[]"  size="10" value="0" onkeyup="vat(1)"  required="" /> </td>
                                                            <td><input type="text"  id="price1" name="price[]" value="0.00" onkeyup="vat(1);"  required="" size="10"/> </td>
                                                            <td>
                                                            <?php 
                                                              $SQLTAX1 = "SELECT * FROM [VMS Tax Master]";
                                                              $resulttax1 = odbc_exec($conn, $SQLTAX1) or die(odbc_errormsg($conn));?>
                                                                <select  id="vatcst1" name="vatcst[]"  onchange="vat(1);">
                                                                    <option value="0">--select--</option>
                                                                      <?php   while(odbc_fetch_array($resulttax1)) {?>
                                                               
                                                                        <option value="<?php echo odbc_result($resulttax1,"Value") ?>"><?php echo odbc_result($resulttax1,"Code") ?></option>
                                                              <?php } ?>
                                                                      </select>
                                                            </td>
                                                            
                                                             <td>
                                                            <?php 
                                                              $SQLServiceTAX1 = "SELECT * FROM [VMS Service Tax Master]";
                                                              $resultservicetax1 = odbc_exec($conn, $SQLServiceTAX1) or die(odbc_errormsg($conn));?>
                                                                <select id="vatservice1" name="vatservice[]"    onchange="vat(1);">
                                                                <option value="0">--select--</option>                                                           
                                                                 <?php   while(odbc_fetch_array($resultservicetax1)) {?>

                                                                        <option value="<?php echo odbc_result($resultservicetax1,"Total Service Tax") ?>"><?php echo odbc_result($resultservicetax1,"Total Service Tax") ?></option>
                                                              <?php } ?>
                                                                      </select>
                                                            </td>
                                                            <td><input  type="text" class="subtotal"  id="subtotal1" name="subtotal[]" size="10" value="0.00" readonly=""/> </td>
                                                           <td>
                                                               <input type="checkbox" id="chk1" name="chktk[]" onclick="ShowHideDiv(1)" />
                                                          </td>
                                                          <td>
                                                              
                                                              <select id="site1" style="display: none" name="site[]">
                                                                    <option value="">-select-</option>
                                                                    <option value="On-Site">On-Site</option>
                                                                    <option value="Off-Site">Off-Site</option>
                                                                    
                                                             </select>
                                                               
                                                          </td>
                                                          <td>
                                                              
                                                            <select id="warranty1" style="display: none" name="warranty[]">
                                                                    <option value="">-select-</option>
                                                                    <option value="1">1 Yr</option>
                                                                    <option value="2">2 Yr</option>
                                                                    <option value="3">3 Yr</option>
                                                                    <option value="4">4 Yr</option>
                                                                    <option value="5">5 Yr</option>
                                                             </select>
                                                               
                                                          </td>
                                                         
                                                            
                                                            <td id="add1"> <a href="javascript:void(0);"  onclick="addrow();">Add</a>
                                                            </td>
                                    </tr>
                             </table>
                               </div>
                             <table style="width: 95%;">
                                 <tr>
                                     <td style="text-align: right;" class="col-md-10"><b>Grand Total</b></td>
                                 <td><input  type="text" size="10" class="form-control" value="0.00" name="gtotal" readonly="" id="gtotal" readonly=""/></td>
                                 </tr>
                            </table>
                           </div>
                           <div class="tab-pane face in" id="EnqTab2">
                               <table style="margin-top: 10px;">
                               <tr>
                                   <td><b>Terms and Conditions</b></td>
                               </tr>
                              <?php $qterm="select * from [VMS Common Term Condition]";
                                   $resultterm = odbc_exec($conn, $qterm) or die(odbc_errormsg($conn));?>
                               
                               <tr>
                                   <td>



<textarea id="txtTinyMCE" rows="20" cols="200" name="termncondition"></textarea>
<br />
<div id="character_count">
</div>
<br />

<script type="text/javascript" src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script type="text/javascript">
    window.onload = function () {
        tinymce.init({
            selector: 'textarea',
            width: 400,
            setup: function (ed) {
                ed.on('keyup', function (e) { 
                    var count = CountCharacters();
                    document.getElementById("character_count").innerHTML = "Characters: " + count;
                });
            }
        });
    }
    function CountCharacters() {
        var body = tinymce.get("txtTinyMCE").getBody();
        var content = tinymce.trim(body.innerText || body.textContent);
        return content.length;
    };
    function ValidateCharacterLength() {
      
        var min = 20;
        var count = CountCharacters();
        if (count < min) {
            alert("Minimum " + min + " characters required for Term and Conditions.")
            return false;
        }
        return;
    }
</script>




                                          </td>
                               </tr>
                               </table>
                                
                           </div>
                                                   
                       </div>

         
         <div class="row">
         <div class="col-md-12">
            <table style="margin-top: 10px;">
                                <tr>
                                    <td>
                                                    <input class="btn btn-primary" type="submit" name="submit" value="submit" id="submit" align="center" onclick="return myFunction()"/>
                                    </td>          
                               </tr>
                              </table>  
         </div>
         </div>
       </div>  
     </div> 
     </div> 
        
</form>
</div>
</div>
<?php require_once("../footer.php");?>

 <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
   <script>tinymce.init({ selector:'textarea' });</script>
  
<script>
function myFunction() {
  
  var row=$('#rowcount').val()
  
  for(var i=1; i<=row; i++){
    var item = $('#itemname'+i).val();
    var spec = $('#specifications'+i).val();
    var uom = $('#uom'+i).val();
    var qty = $('#qty'+i).val();
    var price = $('#price'+i).val();
    if(item==""){
      alert('please fill item name for row '+i);
      return false;
    }
    else if(spec==""){
      alert('please fill item description for row '+i);
      return false;
    }
    else if(uom==""){
      alert('please fill UOM for row '+i);
      return false;
    }
    else if(qty < 1){
      alert('please fill qty for row '+i);
      return false;
    }
    else if(price<=0.00){
      alert('please fill price for row '+i);
      return false;
    }
  }
  
   var textinput1 = $('#mobile').val();
    var textinput2 = $('#email').val();
    var textinput3 = $('#address').val();
    var textinput4 = $('#postcode').val();
    var textinput5 = $('#state').val();
    var textinput6 = $('#city').val();
    var textinput7 = $('#country').val();
    var textinput8 = $('#shippingmobile').val();
    var textinput9 = $('#shippingemail').val();
    var textinput10 = $('#shippingtoaddress').val();
    var textinput11 = $('#shippingstate').val();
    var textinput12 = $('#shippingcity').val();
    var textinput13 = $('#shippingcountry').val();
    var textinput14 = $('#shippingpostcode').val();
    var textinput15 = $('#shippingcontactname').val();
    var textinput16 = $('#date1').val();
    var textinput17 = $('#date2').val();
    var textinput18 = $('#date3').val();
    var textinput22 = $('#vendorquoteno').val();
    var textinput23 = $('#itemname1').val();
    var textinput24 = $('#specifications1').val();
    var textinput25 = $('#uom1').val();
    var textinput26 = $('#qty1').val();
    var textinput27 = $('#price1').val();
    
    if(textinput1=="" )
      {
      alert('pls fill mobile no');
      return false;
    }
    else if(textinput2=="")
      {
      alert('pls fill email');
      return false;
    }
    else if(textinput3=="")
      {
      alert('pls fill address');
      return false;
    }
    else if(textinput4=="")
      {
      alert('pls fill postcode');
      return false;
    }
    else if(textinput5=="")
      {
      alert('pls fill state');
      return false;
    }
    else if(textinput6=="")
      {
      alert('pls fill city');
      return false;
    }
    else if(textinput7=="")
      {
      alert('please fill country');
      return false;
    }
    else if(textinput8=="")
      {
      alert('Shipping mobile number is empty');
      return false;
    }
    else if(textinput9=="" )
      {
      alert('shipping email is empty');
      return false;
    }
      else if(textinput10=="")
      {
      alert('shipping address is empty');
      return false;
    }
    else if(textinput11=="")
      {
      alert('shipping state is empty');
      return false;
    }
    else if(textinput12=="")
      {
      alert('shipping city is empty');
      return false;
    }
    else if(textinput13=="")
      {
      alert('shipping country is empty');
      return false;
    }
    else if(textinput14=="")
      {
      alert('pls fill post code');
      return false;
    }
    else if(textinput15=="")
      {
      alert('pls fill contact name');
      return false;
    }
    else if(textinput16=="")
      {
      alert('pls fill purchase date');
      return false;
    }
    else if(textinput17=="")
      {
      alert('pls fill requested date');
      return false;
    }
    else if(textinput18=="")
      {
      alert('pls fill expected date');
      return false;
    }
  
   else if(textinput22=="")
      {
      alert('pls fill vendor Quote No');
      return false;
    }
    else if(textinput23=="")
      {
      alert('pls fill Item Name');
      return false;
    }
     else if(textinput24=="")
      {
      alert('pls fill Description');
      return false;
    }
     else if(textinput25=="")
      {
      alert('pls fill UOM');
      return false;
    }
    else if(textinput26<1)
      {
      alert('pls fill qty');
      return false;
    }
     else if(textinput27<=0.00)
      {
      alert('pls fill price');
      return false;
    }
   return ValidateCharacterLength();
      
}
</script>
    <script type="text/javascript">
     
     function ShowHideDiv(id) {
         //alert(id);
      if(document.getElementById('chk'+id).checked) {
            $("#warranty"+id).show();
             $("#site"+id).show();
        } 
        else {
            $("#warranty"+id).hide();
            $("#site"+id).hide();
        }
    }
      
      
      function vat(id){
           
            var  varqty = $('#qty'+id).val();
            var  varprice = $('#price'+id).val();
            var  varvat = $('#vatcst'+id).val();
            var  varservice = $('#vatservice'+id).val();
            var varqtyprice=(parseInt(varqty) * parseFloat(varprice));
            var varvattax=((varqtyprice * varvat) / 100);
            var servicetax=((varqtyprice * varservice) / 100);
            var total2=(parseFloat(servicetax) + parseFloat(varvattax)+parseFloat(varqtyprice));
                       
            $('#subtotal'+id).val(total2.toFixed(2));
            
            var sum = 0.00;
            $('.subtotal').each(function() {
                
                sum += parseFloat($(this).val());
                           
               
            });
            $('#gtotal').val(sum.toFixed(2));

            }  
                
         function getaddress(){
             //alert('g');
            var vendorinput = $('#vendorname').val();
            var datastring='vendorinput='+vendorinput;
            //alert(datastring);
           
                $.ajax
                ({
                    type: "POST",
                    url: "SearchVendorAddress.php",
                    data: datastring,
                    cache: false,
                    success: function(result)

                    {
                       
                      var str=result.split(",");
                    // alert(str);
                      document.getElementById('mobile').value=str[0];
                      document.getElementById('email').value=str[1];
                      document.getElementById('address').value=str[2];
                      document.getElementById('postcode').value=str[3];
                      document.getElementById('state').value=str[4];
                      document.getElementById('city').value=str[5];
                      document.getElementById('country').value=str[6];
                      document.getElementById('vendorsname').value=str[7];
                      document.getElementById('vendorcontactname').value=str[8];
                  
                    }
                });
           
                }
          
           function addrow()
           {
            var rowCount = $('#customFields tr').length; 
             $('#rowcount').val(rowCount);
            var validID=rowCount-1;
            var textinput25 = $('#itemname'+validID).val();
            var textinput26 = $('#specifications'+validID).val();
            var textinput27 = $('#uom1'+validID).val();
            var textinput28 = $('#qty1'+validID).val();
            var textinput29 = $('#price1'+validID).val();
               
            if(textinput25!="" && textinput26!="" && textinput27!="" && textinput28!=0 && textinput29!=0.00){
             $("#customFields").append('<tr id="'+rowCount+'" valign="top"> <td><select name="itemtype[]" id="itemtype'+rowCount+'"><option value="Item">Item</option><option value="GL Master">GL Master</option></select></td><td><select    name="itemname[]" id="itemname'+rowCount+'" onchange="check_s('+rowCount+');" ><option value="">-select-</option><?php   while(odbc_fetch_array($resultvdr2)) {?><option value="<?php echo odbc_result($resultvdr2, "Item Name"); ?>"><?php echo odbc_result($resultvdr2, "Item Name"); ?></option> <?php } ?></select></td><td><input type="text" id="specifications'+rowCount+'" readonly name="specifications[]" onkeypress="check('+rowCount+');" onchange="check_s('+rowCount+'); " /></td><td><input type="text" class="uom" id="uom'+rowCount+'" readonly name="uom[]" size="10"/></td><td><input type="text" class="qty" id="qty'+rowCount+'"  name="qty[]" size="10" value="0" onkeyup="vat('+rowCount+')" required=""/></td><td><input type="text" class="price" id="price'+rowCount+'" name="price[]" value="0.00" onkeyup="vat('+rowCount+');" size="10" required=""/></td><td><select  id="vatcst'+rowCount+'" name="vatcst[]"  onchange="vat('+rowCount+');"><option value="0.00">--select--</option><?php   while(odbc_fetch_array($resulttax)) {?><option value="<?php echo odbc_result($resulttax,"Value") ?>"><?php echo odbc_result($resulttax,"Code") ?></option> <?php } ?></select></td><td><select  id="vatservice'+rowCount+'" name="vatservice[]"  onchange="vat('+rowCount+');"><option value="0.00">--select--</option><?php   while(odbc_fetch_array($resultservicetax)) {?><option value="<?php echo odbc_result($resultservicetax,"Total Service Tax") ?>"><?php echo odbc_result($resultservicetax,"Total Service Tax") ?></option> <?php } ?></select></td><td><input type="text" class="subtotal" id="subtotal'+rowCount+'" class="subtotal" name="subtotal[]" size="10" value="0.00" readonly=""/></td><td><input type="checkbox" id="chk'+rowCount+'" onclick="ShowHideDiv('+rowCount+')" /></td><td><select name="site[]" id="site'+rowCount+'" style="display: none"> <option value="">-select-</option><option value="On-Site">On-Site</option><option value="Off-Site">Off-Site</option></select></td><td><select name="warranty[]" id="warranty'+rowCount+'" style="display: none"><option value="">-select-</option><option value="1">1 Yr</option><option value="2">2 Yr</option><option value="3">3 Yr</option><option value="4">4 Yr</option><option value="5">5 Yr</option></select></td></td><td> <a href="javascript:void(0);" class="remCF">Remove</a></td></tr>');
        $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
        rowCount=rowCount-1;
         $('#rowcount').val(rowCount);
         var sum = 0.00;
            $('.subtotal').each(function() {
                
                sum += parseFloat($(this).val());
                           
               
            });
            $('#gtotal').val(sum.toFixed(2));
      });
     
       
      }
      
     
      }
       
   
        function check(id){
            $(function()
            {        
              
                var availableTags1 = [ <?php
                    $Itemname=odbc_exec($conn, "SELECT DISTINCT([Item Name]) FROM [VMS Item Master] ") or die(odbc_errormsg($conn));
                    while(odbc_fetch_array($Itemname)){
                        echo "'". odbc_result($Itemname, "Item Name")."', ";
                }
               
            ?> ];
        
                $('#itemname'+id).autocomplete({
                    source: availableTags1
                });

            });

 
        }
     function shipping(){
        
             $(function()
    {        
       
        var availableTags2 = [ <?php
                $shippingname=odbc_exec($conn, "SELECT ([StoreCode]) FROM [VMS Shipping Master] ") or die(odbc_errormsg($conn));
                while(odbc_fetch_array($shippingname)){
                    echo "'". odbc_result($shippingname, "StoreCode")."', ";
                }
               
            ?> ];
        
        $('#shippingname').autocomplete({
            source: availableTags2
        });
        
    });
         
        }
        function qty(id){
       
           document.getElementById('qtyreceived'+id).value=0;
           var poqty = $('#qty'+id).val();
           var qtyrcv = $('#qtyreceived'+id).val();
            var outstanding=poqty-qtyrcv;
           if(qtyrcv > poqty)
           {
            alert('f');
           }
          
           //alert(outstanding);
           document.getElementById('outstanding'+id).value=outstanding;
        }
        
           $("#qtytoreceived").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
                 $("#qtyreceived").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
        
        function vendor(){
        
             $(function()
    {        
       
        var availableTags = [ <?php
                $Vendorname=odbc_exec($conn, "SELECT [VendorCode],[Vendor Name] FROM [VMS Vendor Master] ") or die(odbc_errormsg($conn));
                while(odbc_fetch_array($Vendorname)){
                    echo "'". odbc_result($Vendorname, "VendorCode")."',";
                }
               
            ?> ];
        
        $('#vendorname').autocomplete({
            source: availableTags
        });
        
    });
         
        }
    
   
      </script>
       <script>
     $(document).ready(function(){
              
           
            
       $(function() {

      $("#date1").datepicker({ minDate: 0,});
        $("#dt1").datepicker({ 
            //minDate: "<--?php echo date('d/M/Y', strtotime(odbc_result($Student, "Date of Birth")))?>", 
            minDate:0,
            //maxDate: 0, 
            changeMonth: true, 
            changeYear: true
        });
  
        });
         $(function() {

      $("#date2").datepicker({ minDate: 0,});
        $("#dt1").datepicker({ 
            //minDate: "<--?php echo date('d/M/Y', strtotime(odbc_result($Student, "Date of Birth")))?>", 
            minDate:0,
            //maxDate: 0, 
            changeMonth: true, 
            changeYear: true
        });
  
           });
 $(function() {

      $("#vinvoicedate").datepicker({ minDate: 0,});
        $("#dt1").datepicker({ 
            //minDate: "<--?php echo date('d/M/Y', strtotime(odbc_result($Student, "Date of Birth")))?>", 
            minDate:0,
            //maxDate: 0, 
            changeMonth: true, 
            changeYear: true
        });
  
           });
  $(function() {

      $("#grndate").datepicker({ minDate: 0,});
        $("#dt1").datepicker({ 
            //minDate: "<--?php echo date('d/M/Y', strtotime(odbc_result($Student, "Date of Birth")))?>", 
            minDate:0,
            //maxDate: 0, 
            changeMonth: true, 
            changeYear: true
        });
  
           });

           $(function() {

      $("#date3").datepicker({ minDate: 0,});
        $("#dt1").datepicker({ 
            //minDate: "<--?php echo date('d/M/Y', strtotime(odbc_result($Student, "Date of Birth")))?>", 
            minDate:0,
            //maxDate: 0, 
            changeMonth: true, 
            changeYear: true
        });
  
           });
           
    });

       function check_s(id){
       
          var textinput = $('#itemname'+id).val();
          var datastring='textinput='+textinput+'&id='+id;
           
                $.ajax
                ({
                    type0: "POST",
                    url: "SearchItem.php",
                    data: datastring,
                    cache: false,
                    success: function(result)
                    {
                      var str=result.split(",");
                      document.getElementById('specifications'+str[2]).value=str[0];
                      document.getElementById('uom'+str[2]).value=str[1];
                      
                       $('#specifications'+id).html(html);
                       $('#uom'+id).html(html);
                    }
                });
           

}

function chk(id){
//alert();
          var textinput = $('#itemname'+id).val();
          var datastring='textinput='+textinput+'&id='+id;
           
                $.ajax
                ({
                    type0: "POST",
                    url: "SearchItem.php",
                    data: datastring,
                    cache: false,
                    success: function(result)
                    {
                      var str=result.split(",");
                      document.getElementById('specifications'+str[2]).value=str[0];
                      document.getElementById('uom'+str[2]).value=str[1];
                      
                       $('#specifications'+id).html(html);
                       $('#uom'+id).html(html);
                    }
                });
           

}
function comp(id){
  //alert("ID : " + id);
 
  $('#qtytoreceived'+id).keypress(function (e) {
    
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       //display error message
      $("#errmsg").html("Digits Only").show().fadeOut("slow");
           return false;
   }
   });

  var qtytoreceived=$('#qtytoreceived'+id).val();
  var qty=$('#qty'+id).val();
  
    if (+qtytoreceived > +qty) {
       alert('max value');
       $('#submit').prop('disabled', true);
  }
   else{
     $('#submit').prop('disabled', false);
  }
 
  $('#qtyreceived'+id).val()='0';
}
function checkAvailability() {
var textinput1 = $('#vinvoicegrn').val();
var textinput2 = $('#vendorname').val();
var datastring='textinput1='+textinput1+'&textinput2='+textinput2;
jQuery.ajax({
url: "check_availability.php",
data: datastring,
type: "POST",
success:function(data){
 if(data>0){
  alert('This Invoice No Already Exist');
   $('#submit').prop('disabled', true);
 }
  else{
   $('#submit').prop('disabled', false);
 }
},
error:function (){}
});
}

function getaddress1(){
    var textinput = $('#shippingname').val();
    var datastring='textinput='+textinput;
                    
                $.ajax
                ({
                    type: "POST",
                    url: "ShippingAddress.php",
                    data: datastring,
                    cache: false,
                    success: function(result)
                    {
                       
                      var str=result.split(",");
                      //alert(str);
                      document.getElementById('shippingmobile').value=str[0];
                      document.getElementById('shippingemail').value=str[1];
                      document.getElementById('shippingtoaddress').value=str[2];
                      document.getElementById('shippingstate').value=str[3];
                      document.getElementById('shippingcity').value=str[4];
                      document.getElementById('shippingcountry').value=str[5];
                      document.getElementById('shippingpostcode').value=str[6];
                      document.getElementById('shippingcontactname').value=str[7];
                    }
                });
           
}

  </script>
  
