<?php
require_once('class/csv.php');

$path = new Upload();
$csv = $path->getPath();

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
                $path = realpath('./') . "/" . $csv . "$file_id" . ".csv";
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
        <form class="upload_file" method="post" enctype="multipart/form-data">
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

<?php include "templates/footer.php"; ?>