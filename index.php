<?php
require_once('class/csv.php');

$csv = new CSV();

$msg = NULL;

if ( isset($_POST["submit"]) ) 
{
    if (!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) 
    {
        $msg = '<div class="alert alert-danger">Please, select a file</div>';

    } else {

        if (isset($_FILES)) 
        {
            $check = true;

            if ($_FILES['file']['type'] !== 'text/csv') 
            {
                $check = false;
                $msg = '<div class="alert alert-danger">Please, select only CSV file</div>';
            }
            if ($check) 
            {
                $file_id = uniqid();
                $path = realpath('./') . "/" . $csv->dir . "$file_id" . ".csv";
                move_uploaded_file($_FILES['file']['tmp_name'], "$path");

                header("Location:checkout.php");
            }
            
        }
    }
    
} 

?>

<?php include "templates/header.php"; ?>
    <div class="container">
        <div class="select_file">
            <form method="post" enctype="multipart/form-data">
                <span>Select file</span>
                <input type="file" name="file" id="file" />
                <span>Submit</span>
                <input type="submit" name="submit" />
            </form>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <?php echo $msg; ?>    
                </div>
            </div>
        </div> 
    </div>

    <a href="checkout.php" class="btn btn-primary" role="button">Go to checkout</a>

<?php include "templates/footer.php"; ?>


        <!-- <table class="select_file" width="600">
            <form method="post" enctype="multipart/form-data">
                <tr>
                    <td width="20%">Select file</td>
                    <td width="80%"><input type="file" name="file" id="file" /></td>
                </tr>
                <tr>
                    <td>Submit</td>
                    <td><input type="submit" name="submit" /></td>
                </tr>
            </form>
        </table> 
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
            <?php //echo $msg; ?>    
            </div>
        </div>-->
