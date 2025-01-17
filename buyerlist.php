<?php
session_start();
$type = $_SESSION['usertype'];
if ($type == 'Master') {
    include("header.php");
    include("menumaster.php");
} else if ($type == 'ApprovalTeam') {
    include("header.php");
    include("menuapproval.php");
} else {
    include("logout.php");
}
include("dbConnection.php");
$date = date("Y-m-d");
?>
<style>
    #wrapper h3 {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 20px;
        color: #123C69;
    }

    .hpanel .panel-body {
        box-shadow: 10px 15px 15px #999;
        border: 1px solid #edf2f9;
        background-color: #f5f5f5;
        border-radius: 3px;
        padding: 20px;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #fffafa;
    }

    .text-success {
        color: #123C69;
        text-transform: uppercase;
        font-weight: bold;
        font-size: 12px;
    }

    .btn-primary {
        background-color: #123C69;
    }

    .theadRow {
        text-transform: uppercase;
        background-color: #123C69 !important;
        color: #f2f2f2;
        font-size: 11px;
    }

    .btn-success {
        display: inline-block;
        padding: 0.7em 1.4em;
        margin: 0 0.3em 0.3em 0;
        border-radius: 0.15em;
        box-sizing: border-box;
        text-decoration: none;
        font-size: 12px;
        font-family: 'Roboto', sans-serif;
        text-transform: uppercase;
        color: #fffafa;
        background-color: #123C69;
        box-shadow: inset 0 -0.6em 0 -0.35em rgba(0, 0, 0, 0.17);
        text-align: center;
        position: relative;
    }

    .fa_Icon {
        color: #990000;
    }

    .table-responsive .row {
        margin: 0px;
    }
</style>
<div id="wrapper">
    <div class="row content">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    </div>
                    <h3><b><i class="fa_Icon fa fa-institution"></i> Branch Details &nbsp;</b></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered table-hover">
                            <thead class="theadRow">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Dial Code</th>
                                    <th>Company</th>
                                    <th>Address Line 1</th>
                                    <th>Address Line 2</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Zip</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $sql = mysqli_query($con, "SELECT * FROM buyer_profile");
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    echo "<tr>";
                                    echo "<td>" . $i .  "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['contact'] . "</td>";
                                    echo "<td>" . $row['dial_code'] . "</td>";
                                    echo "<td>" . $row['company'] . "</td>";
                                    echo "<td>" . $row['address_line1'] . "</td>";
                                    echo "<td>" . $row['address_line2'] . "</td>";
                                    echo "<td>" . $row['city'] . "</td>";
                                    echo "<td>" . $row['state'] . "</td>";
                                    echo "<td>" . $row['country'] . "</td>";
                                    echo "<td>" . $row['zip'] . "</td>";
                                    echo "<td>" . $row['date'] . "</td>";
                                    echo "<td>" . $row['time'] . "</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("footer.php"); ?>