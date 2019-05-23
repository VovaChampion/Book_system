<?php

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
                //$date = date('Ymd_His');
                $file_id = uniqid();
                // $path = realpath('./') . '/upload/' . "$date" . "_" . $_FILES['books_file']['name'];

                $path = realpath('./') . '/upload/' . "$file_id" . ".csv";

                move_uploaded_file($_FILES['file']['tmp_name'], "$path");
            }
            //var_dump($check);
        }
    }
} 

?>

<?php include "templates/header.php"; ?>
    <div class="container">
        <table width="600">
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
                <?php echo $msg; ?>    
            </div>
        </div>
    </div>

    <a href="checkout.php" class="btn btn-primary" role="button">Go to checkout</a><br>

<?php include "templates/header.php"; ?>

