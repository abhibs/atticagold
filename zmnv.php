<?php
session_start();
$type = $_SESSION['usertype'];

if ($type == 'Master') {
    include("header.php");
    include("menumaster.php");
} else if ($type == 'VM-AD') {
    include("header.php");
    include("menuvmadd.php");
} else if ($type == 'Zonal') {
    include("header.php");
    include("menuZonal.php");
}else{
       include("logout.php");
}
include("dbConnection.php");
?>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script> -->
<!-- <script src="js/bootstrap.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="styles/amsify.suggestags.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="scripts/jquery.amsify.suggestags.js"></script>
<script>
    function get_branches(val) {
         //alert(val);
        $.ajax({
            type: "POST",
            url: "zvmn.php",
            data: {
                state_id: val
            },
            dataType: 'json',
            success: function(response) {
                 //alert(response);

                $("#br1").html(response.branch_a);
                $("#br2").html(response.branch_b);
                $("#br3").html(response.branch_c);
                $("#br4").html(response.branch_d);
                $("#br5").html(response.branch_e);
                $("#vmnameaccess").html(response.vmnameaccess);
            }
        });
    }

    function getvm(val) {
        $.ajax({
            type: "POST",
            url: "zvmn.php",
            data: 'state_id2=' + val,
            success: function(data) {
                //alert(data);
                $("#getvm2").html(data);
            }
        });
    }
</script>
<?php

if (isset($_POST['uploadvm'])) {

    $agentId = $_POST['agentId'];
    $branches = $_POST['branches'];

    $query = "UPDATE `vmagent` SET `branch`='$branches' WHERE agentId='$agentId'";
    $query_run = mysqli_query($con, $query);
    echo "<script type='text/javascript'>alert('$agentId is access this Branches : $branches')</script>";
    unset($_SESSION['msn']);
}

if (isset($_POST['updatevm'])) {
    $_SESSION['msn'] = $_POST['branchA'];
    $agentId = $_POST['vmid'];
} else {
    $_SESSION['msn'] = '';
}
?>
<style>
	#wrapper{
		background: #f5f5f5;
	}	
	#wrapper h3{
		text-transform:uppercase;
		font-weight:600;
		font-size: 20px;
		color:#123C69;
	}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{
		background-color:#fffafa;
	}
	.text-success{
		color:#123C69;
		text-transform:uppercase;
		font-weight:bold;
		font-size: 12px;
	}
	.btn-primary{
		background-color:#123C69;
	}
	.theadRow {
		text-transform:uppercase;
		background-color:#123C69!important;
		color: #f2f2f2;
		font-size:11px;
	}	
	.dataTables_empty{
		text-align:center;
		font-weight:600;
		font-size:12px;
		text-transform:uppercase;
	}
	.btn-success{
		display:inline-block;
		padding:0.7em 1.4em;
		margin:0 0.3em 0.3em 0;
		border-radius:0.15em;
		box-sizing: border-box;
		text-decoration:none;
		font-size: 11px;
		font-family:'Roboto',sans-serif;
		text-transform:uppercase;
		color:#fffafa;
		background-color:#123C69;
		box-shadow:inset 0 -0.6em 0 -0.35em rgba(0,0,0,0.17);
		text-align:center;
		position:relative;
	}
	.fa_Icon {
		color:#b8860b;
	}
	.row{
	    margin-left:0px;
	    margin-right:0px;
	}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row-content">
            <div class="col-lg-12">
                <?php
                if (empty($_SESSION['msn'])) {

                    $allbranch = $_SESSION['msn'];
                ?>
                    <div class="hpanel">
                        <div class="panel-heading">
                            <!-- <a href="viewstock.php" style="float:left;" class="btn btn-success"><b><i style="color:#ffcf40" class="fa fa-arrow-left"></i> View Stock</b></a> -->
                            <h3 class="text-success"><i style="color:#990000" class="fa fa-dashboard"></i> Branch Access </h3>
                        </div>

                        <div class="panel-body" style="border: 5px solid #fff;border-radius: 10px;padding: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;background-color: #f5f5f5;">

                            <div class="col-sm-6" >
                                <label class="text-success">Language</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span style="color:#990000" class="fa fa-sort-alpha-asc"></span></span>
                                    <select onChange="getvm(this.value);" id="language_id" name="cate" class="form-control">
                                        <option Readonly disabled selected class='bg-white' value="">Select Language</option>
                                        <!-- select * from `users` WHERE designation='VM' -->
                                        <?php $query = mysqli_query($con, "SELECT * FROM `language`");

                                        while ($row = mysqli_fetch_array($query)) { ?>
                                            <option class=' bg-white' value="<?php echo $row['id']; ?>"><?php echo $row['language']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <form action="" method="POST">
                                <div class="col-sm-6">
                                    <!-- <div class="row" style="margin-left:20px;margin-bottom:10px;"> -->
                                    <!-- <div class="col-sm-5"> -->
                                    <label class="text-success">VM Name</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span style="color:#990000" class="fa fa-sort-alpha-asc"></span></span>
                                        <select onChange="get_branches(this.value);" name="vmid" id="getvm2" class="form-control">
                                            <option Readonly disabled selected class='bg-white' value="">Select VM</option>
                                            <!-- select * from `users` WHERE designation='VM' -->
                                        </select>
                                    </div>
                                </div>
                                <!-- </div> -->
                                <div class="row" style="margin-left:10px;">
                                    <div class="col">
                                        <!-- <label class="text-success"> Access Branches</label> -->
                                        <div class="input-group">
                                            <!-- <span class="input-group-addon"><span style="color:#990000" class="fa fa-sort-numeric-asc"></span></span> -->
                                            <div class="" id="br1">
                                                <input type="hidden" class="form-control" name="branchA" id="branch_a" placeholder="Get Branch" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 30px; float:left">
                                    <button type="submit" name="updatevm" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-success">Reset</button>
                                </div>
                        </div>
                        </form>
                    </div>
                <?php
                }
                ?>

                <?php
                if (!empty($_SESSION['msn'])) {
                    $allbranch = $_SESSION['msn'];
                ?>

                    <div class="hpanel">
                        <div class="panel-heading">
                            <a href="zmnv.php" style="float:right;" class="btn btn-success"><b><i style="color:#ffcf40" class="fa fa-arrow-left"></i> Go Back</b></a>
                            <h3 class="text-success"><b><i style="color:#990000" class="fa fa-dashboard"></i> Access Branches</b></h3>
                        </div>
                        <div class="panel-body" style="border: 5px solid #fff;border-radius: 10px;padding: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;background-color: #f5f5f5;">
                            <form action="" method="POST">
                                <?php
                                $rm = mysqli_query($con, "SELECT name FROM `employee` WHERE empId='$agentId';");
                                $emr = mysqli_fetch_array($rm);
                                $emrp = $emr['name'];
                                ?>
                                <div class="row" style="margin-left:15px;margin-right:15px;">
                                    <div class="col-lg-6">
                                        <label class="text-success"> VM Name</label>
                                        <div class="form-group">
                                            <input type="text" readonly class="form-control" name="" value="<?php echo $emrp; ?>" />
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" class="form-control" name="agentId" id="" value="<?php echo $agentId; ?>" />
                                <div class="row" style="margin-left:10px;">
                                    <div class="col-lg-12">
                                        <label class="text-success">Branches Accessing</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="branches" value="<?php echo $allbranch; ?>" />
                                        </div>
                                    </div>
                                    <button type="submit" name="uploadvm" class="btn btn-success">Submit</button>
                                </div>
                        </div>
                        </form>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>
<?php
// include("dbConnection.php");
$query15 = mysqli_query($con, "SELECT branchId,branchName FROM branch");
?>
<script type="text/javascript">
    $('input[name="branches"]').amsifySuggestags({
        type: 'amsify',
        suggestions: [
            <?php
            while ($rom = $query15->fetch_object()) {
            ?> {
                    'tag': '<?php echo $rom->branchName; ?>',
                    'value': '<?php echo $rom->branchId; ?>'
                },
            <?php
            }
            ?>
        ]
        // whiteList: true,
    });
</script>
<div style="clear: both;"></div>
     <div class="hidden">
        <?php
        include("footer.php");
        ?>
    </div>
   