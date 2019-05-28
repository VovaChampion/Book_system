<?php 
include "templates/header.php"; 
require_once('class/csv.php');

$csv = new Upload();
$csv2 = new Download();

$file_name = $csv->getLatestFile();
$path_upl = $csv->getPath();

$path_dow = $csv2->getPath();
?>

<div class="container">
    <?php
        //Create books array
        $books = [];
        $books[] = ['ISBN', 'Book title', 'Publish Year', 'Id'];

        // Open the file for reading
        if ($file_handle = fopen($path_upl . $file_name, 'r'))
        {
            // Read one line from csv file, use comma as separator
            while ($data = fgetcsv($file_handle, 0, ','))
            {
                $books[] = $csv->fill_book($data[0]);
            }
            fclose($file_handle);
        }

        if ($books)
        {
            $filename = $path_dow . 'updated_' . "$file_name";
            $file_to_write = fopen($filename, 'w');

            $everything = true;

            foreach ($books as $book)
            {
                $everything = $everything && fputcsv($file_to_write, $book);
            }
            fclose($file_to_write);
            ?>
            
    <div class="output">
        <h4>Your books</h4>
        <?php
        $file_details = $csv2->showFile();
        echo $file_details;

            if ($everything)
                {
                    echo '<a class="download_file" href= "' . $filename . '" download>Download the CSV-file</a>';
                } else {
                    echo 'Something goes wrong';
                }
            }
        ?>
        <a href="index.php" class="btn btn-primary" role="button">Go home page</a>
    </div>
</div>

<?php include "templates/footer.php"; ?>
