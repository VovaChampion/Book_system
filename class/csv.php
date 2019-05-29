<?php

require_once 'upload_csv.php';
require_once 'download_csv.php';

class CSV 
{
    protected $dir;

    public function getPath()
    {
        return $this->dir;
    }

    public function fill_book($isbn)
    {
        $book = [];

        $book[0] = $isbn;
        $url = 'https://5ce8007d9f2c390014dba45e.mockapi.io/books/' . "$isbn";

        // Create a curl instance.
        $ch = curl_init($url);
        
        // Setup curl options
        // To set GET we need to specify a custom request.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // This is the data we want to get.
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // We want curl to return whatever response we get from the server.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // We want to specify that we're sending JSON.
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // Perform the request and get the response.
        $content = curl_exec($ch);
        curl_close($ch);

        $result  = json_decode($content);

        $book[1] = $result->title ?? 'no information available';
        $book[2] = $result->pages ?? '-';
        $book[3] = $result->categories[0] ?? '-';
        $book[4] = $result->author_id ?? '-';
        
        $id = $book[4];

        if (isset($id)) 
        {
            $url = 'https://5ce8007d9f2c390014dba45e.mockapi.io/authors/' . "$id";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $content = curl_exec($ch);
            curl_close($ch);

            $result1  = json_decode($content);

            $book[4] = $result1->firstName ?? '-';
            $book[5] = $result1->lastName ?? '-';
            $book[6] = $result1->email ?? '-';
        }

        $book[7] = $result->publisher_id ?? '-';
        $pub_id = $book[7];

        if (isset($pub_id)) 
        {
            $url = 'https://5ce8007d9f2c390014dba45e.mockapi.io/publishers/' . "$pub_id";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $content = curl_exec($ch);
            curl_close($ch);

            $result  = json_decode($content);

            $book[7] = $result->name ?? '-';
            $book[8] = $result->email ?? '-';
        }

        return $book;
    }

    // public function fill_book($isbn)
    // {
    //     $book = [];

    //     $book[0] = $isbn;
    //     $url = 'http://obrorsson.se/api.php?book/ISBN/' . "$isbn";
    //     $data = '{"apikey":{"apikey":"5ce669ef69dae", "email":"vova@gmail.com"}}';

    //     // Create a curl instance.
    //     $ch = curl_init($url);
        
    //     // Setup curl options
    //     // To set GET we need to specify a custom request.
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //     // This is the data we want to get.
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    //     // We want curl to return whatever response we get from the server.
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     // We want to specify that we're sending JSON.
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    //     // Perform the request and get the response.
    //     $content = curl_exec($ch);
    //     curl_close($ch);

    //     $result  = json_decode($content);

    //     $book[1] = $result->results[0]->Name ?? 'no information available';
    //     $book[2] = $result->results[0]->YearOfRelease ?? '-';
    //     $book[3] = $result->results[0]->Id ?? '-';

    //     return $book;
    // }

    // Take the latest updated csv file from my folder upload
    public function getLatestFile()
    {
        $dir = $this->dir;
        $dh = opendir($dir);
        $last = 0;
        $name = "";

        while (($file = readdir($dh)) !== false)
        {
            if (is_file($dir.$file))
            {
                $mt = filemtime($dir.$file);
                if ($mt > $last)
                {
                    $last = $mt;
                    $name = $file;
                }
            }
        }
        closedir($dh);
        return $name;
    }

    // Show the file's content as a table
    public function showFile()
    {
        $myFile = $this->getLatestFile();

        echo "<table>";
        $f = fopen($this->dir . $myFile, "r");
        while (($line = fgetcsv($f)) !== false) 
        {
            echo "<tr>";
            foreach ($line as $cell) 
            {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
        echo "</tr>"; 
        }

        fclose($f);
        echo "</table>";
    }
}  
        
?>