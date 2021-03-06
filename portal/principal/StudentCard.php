<?php
    require_once 'header.php';
    $id = $_REQUEST['id'];
    if($_REQUEST['msg'] != ""){
        if($_REQUEST['msg']==1){
            echo "<div class='container'><div class='bs-example'>
                        <div class='alert alert-danger alert-error'>
                                <a href='#' class='close' data-dismiss='alert'>&times;</a>
                                <strong>Error!</strong> There is some error, kindly check.<br />".odbc_errormsg($conn)."
                        </div>
                </div></div>";
        }
        if($_REQUEST['msg']==0){
            echo "<div class='container'><div class='bs-example'>
                            <div class='alert alert-success alert-error'>
                                    <a href='#' class='close' data-dismiss='alert'>&times;</a>
                                    <strong>Success!</strong> Student card updated successfully.
                            </div>
                    </div></div>";
        }
        if($_REQUEST['msg']==2){
            echo("<div class='container'><div class='bs-example'>
                <div class='alert alert-danger alert-error'>
                    <a href='#' class='close' data-dismiss='alert'>&times;</a>
                    <strong>Error!</strong> Class Section code not defined for Academic Year: ".odbc_result($row, "Academic Year").", Class: ".  odbc_result($row, "Class")." Section: ".odbc_result($row, "Section").". Kindly contact your administrator ...<br />".odbc_errormsg($conn)."
                </div>
            </div></div>");
        }
        if($_REQUEST['msg']==3){
            echo("<div class='container'><div class='bs-example'>
                <div class='alert alert-danger alert-error'>
                    <a href='#' class='close' data-dismiss='alert'>&times;</a>
                    <strong>Error!</strong> Approver ID un-defined. Contact your ERP Administrator ...
                </div>
            </div></div>");
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
    
?>
<script type="text/javascript" charset="utf-8">
    $(function()
    {
        $("#datepicker1").datepicker({ minDate: -5, maxDate: 0});
        $("#datepicker2").datepicker({ minDate: -5, maxDate: 0});
    });
</script>


<div class="right_col" role="main">
<div class="">
<div class="page-title">
<div class="title_left">
</div>

<div class="clearfix"></div>

<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
<div class="x_title">
<h2>Student Card </h2>
<ul class="nav navbar-right panel_toolbox">
<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
<ul class="dropdown-menu" role="menu">
<li><a href="#">Settings 1</a></li>
<li><a href="#">Settings 2</a></li>
</ul>
</li>
</ul>
<div class="clearfix"></div>
</div>
<div class="x_content">
   
    <form name="form" action='StudentCardUpdate.php' method='GET'>
        <input type="hidden" value="<?php echo $id; ?>" name="id">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">General</a></li>
            <li><a data-toggle="tab" href="#tab2">Communication</a></li>
            <li><a data-toggle="tab" href="#tab3">Personal</a></li>
            <li><a data-toggle="tab" href="#tab4">Other details</a></li>
            <li><a data-toggle="tab" href="#tab5">Parent Info</a></li>
            <li><a data-toggle="tab" href="#tab6">Sibling & Discount</a></li>
            
        </ul>
        <div class="tab-content">
            <!-- General -->
            <div id="tab1" class="tab-pane fade in active">
                <?php require_once("StudentCard01.php"); ?>
            </div>        
            <!-- Communication -->
            <div id="tab2" class="tab-pane fade">
                <?php require_once("StudentCard02.php"); ?>
            </div>
            <!-- Personal -->
            <div id="tab3" class="tab-pane fade">
                <?php require_once("StudentCard03.php"); ?>
            </div>
            <!-- Other details -->
            <div id="tab4" class="tab-pane fade">
                <?php require_once("StudentCard04.php"); ?>
            </div>
            <!-- Parent Info -->
            <div id="tab5" class="tab-pane fade">
                <?php require_once("StudentCard05.php"); ?>
            </div>
            <!-- Sibling & Discount -->
            <div id="tab6" class="tab-pane fade">
                <?php require_once("StudentCard06.php"); ?>
            </div>
        </div>  
        <p style="border-top: 1px solid #d2d2d2; padding: 10px;text-align: right;">
            <input type="button" value="Cancel" class="btn btn-warning" onclick="javascript:history.go(-1);">
            <?php if(odbc_result($row, "Student Status")!=3){?>
            <input type="submit" value="Approve" class="btn btn-success">
            <?php }?>
        </p>
    </form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- jQuery -->
<script src="../vendors/jquery/dist/jquery.min.js"></script>
<script src="../vendors/jquery/dist/jquery-ui.js"></script>
<link href="../vendors/jquery/dist/jquery-ui.css" rel="stylesheet">
<!-- Bootstrap -->
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
<?php
    require_once '../footer.php';
?>