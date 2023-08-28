<?php
require 'config.php';
require('fpdf.php');
include_once('TCPDF-main/tcpdf.php');

// Function to format date based on the format used in the Excel file
function formatDate($excelDate)
{
    $UNIX_DATE = ($excelDate - 25569) * 86400;
    return gmdate("Y-m-d", $UNIX_DATE);
}

if (isset($_POST["import"])) {
    $fileName = $_FILES["excel"]["name"];
    $fileExtension = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExtension));
    $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

    $targetDirectory = "uploads/" . $newFileName;
    move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

    error_reporting(0);
    ini_set('display_errors', 0);

    require 'ExcelReader/excel_reader2.php';
    require 'ExcelReader/SpreadsheetReader.php';

    $reader = new SpreadsheetReader($targetDirectory);
    foreach ($reader as $key => $row) {
        $sname = $row[0];
        $cdate = formatDate($row[1]); // Call formatDate function to handle date
        $cnumber = $row[2];
        $uemail = $row[3];
        $udomain = $row[4];

        // Use prepared statements to insert data safely into the database
        $stmt = $con->prepare("INSERT INTO event_certificate (sname, cdate, cnumber, uemail, domain) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $sname, $cdate, $cnumber, $uemail, $udomain);
        $stmt->execute();
        $stmt->close();
    }

    echo "
    <script>
    alert('Successfully Imported');
    document.location.href = '';
    </script>
    ";
}

// Get the search keyword from the input field
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';

// Fetch rows based on the search keyword
$query = "SELECT * FROM event_certificate WHERE uemail LIKE '%$searchKeyword%'";
$result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Excel Data Generator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .search-button {
            background-color: #4b4c4c;
            border: none;
            color: #FFF;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .search-button i {
            margin-right: 5px;
        }

        .search-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .search-input {
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            flex-grow: 1;
            margin-right: 10px;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            border-radius: 2px;
            color: #f0f0f0;
            cursor: pointer;
            padding: 5px 20px;
            font-size: 0.9em;
        }

        .outer-container {
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 40px 20px;
            border-radius: 2px;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            border-radius: 2px;
            color: #f0f0f0;
            padding: 5px 20px;
            font-size: 0.9em;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 18px;
        }

      

        #th {
            padding-top: 19px;
            padding-bottom: 19px;
            margin: 20px;
            text-align: left;
            background-color: #800080;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
/* Style for the download and send email buttons */
 .send-email-button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #3e1669; /* Green background */
  color: white; /* White text */
  border: none; /* No borders */
  cursor: pointer; /* Add a pointer on hover */
  border-radius: 5px; /* Rounded corners */
  transition: background-color 0.3s; /* Smooth transition */
  margin: 5px; /* Add some margin between the buttons */
  text-decoration: none; /* Remove underline */
}
.download-button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #9f149f; /* Green background */
  color: white; /* White text */
  border: none; /* No borders */
  cursor: pointer; /* Add a pointer on hover */
  border-radius: 5px; /* Rounded corners */
  transition: background-color 0.3s; /* Smooth transition */
  margin: 5px; /* Add some margin between the buttons */
  text-decoration: none; /* Remove underline */
}
.pdf-download-button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #740074; /* Green background */
  color: white; /* White text */
  border: none; /* No borders */
  cursor: pointer; /* Add a pointer on hover */
  border-radius: 5px; /* Rounded corners */
  transition: background-color 0.3s; /* Smooth transition */
  margin: 5px; /* Add some margin between the buttons */
  text-decoration: none; /* Remove underline */
}
.download-button i, .send-email-button i {
  margin-right: 5px; /* Add some space between the icon and text */
}

.download-button:hover, .send-email-button:hover, .pdf-download-button {
    border-radius: 8px; /* Increase the border radius on hover */
}


    </style>
    </head>

<body style="padding:20px">
    <div class="container">
        <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label>Choose Excel File (Only .xls file):</label>
            <input type="file" name="excel" required value="">
            <button type="submit" class="btn-submit" name="import">Import</button>
        </form>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="search-container">
        <input type="text" id="search" class="search-input" name="search" placeholder="Search by student email">
        <button type="submit" class="search-button" name="submit-search">
            <i class="fa fa-search"></i> Search
        </button>
    </div>
</form>

        <hr>

        <table id="student">
            <tr id="th">
                <td>Sr. No</td>
                <td>Student Name</td>
                <td>Date</td>
                <td>Certificate No.</td>
                <td>Email</td>
                <td>Domain</td>
                <td>Offer Letter</td>
                <td>Offer Letter as Pdf</td>
                <td>Send Email</td>
            </tr>
            <?php

            if (!$result || mysqli_num_rows($result) === 0) {
                echo '<tr><td colspan="9">No records found.</td></tr>';
            } else {
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                    echo '<td>' . strtolower($row["sname"]) . '</td>';
                    echo '<td>' . $row["cdate"] . '</td>';
                    echo '<td>AST/22/' . $row["cnumber"] . '</td>';
                    echo '<td>' . $row["uemail"] . '</td>';
                    echo '<td>' . $row["domain"] . '</td>';

                    $email = $row["uemail"];
                    $font = "./arial.ttf";
                    $image = imagecreatefromjpeg("OfferLetter2.jpg");
                    $color = imagecolorallocate($image, 19, 21, 22);
                    $domain = $row["domain"] . '.';

                    $name = strtolower($row["sname"]);
                    $date = $row["cdate"];
                    $cno  = "AST/22/" . $row["cnumber"];

                    imagettftext($image, 20, 0, 180, 530, $color, $font, $name);
                    imagettftext($image, 20, 0, 180, 568, $color, $font, $email);
                    imagettftext($image, 15, 0, 817, 732, $color, $font, $domain);
                    imagettftext($image, 18, 0, 180, 476, $color, $font, $date);
                    imagettftext($image, 20, 0, 1760, 1220, $color, $font, $cno);
                    imagejpeg($image, "Certificates/" . $name . ".jpg");

                    // PDF generation
                    $pdf = new FPDF('L', 'in', [20.7, 20.27]);
                    $pdf->AddPage();
                    $pdf->Image("Certificates/" . $name . ".jpg", 0, 0, 20.7, 20.27);
                    $pdfPath = "Certificates/" . $name . ".pdf";
                    $pdf->Output($pdfPath, "F");
                    // End PDF generation

                    imagedestroy($image);
                    echo "<td>
                    <a href='Certificates/" . $name . ".jpg' download='" . $name . ".jpg'  class='download-button ' >
                     Download
                    </a>
                  </td>";
                  echo "<td>
                  <a href='Certificates/" . $name . ".pdf' download='" . $name . ".pdf' class='pdf-download-button '>
                    Download_pdf
                  </a>
                </td>";
            
                    //echo "<td>&nbsp;<br/><form action='sendmail/demo.php' method='post' enctype='multipart/form-data'>
                    echo "<td>&nbsp;<br/><form action='sendmail/automail.php' method='post' enctype='multipart/form-data'>
                    <input type='hidden' name='name' value='$name'>
                    <input type='hidden' name='email' value='$email'>
                    <input type='hidden' name='pdfPath' value='$pdfPath'>
                    <button type='submit' class='send-email-button'>
                    <i class='fa fa-envelope'></i> Send Mail
                </button>                </form></td>";

                    echo '</tr>';
                }
            }
            ?>
        </table>
      
<body style="padding:20px">
<div class="container">
<div class="container">
            <!-- Add the button for sending emails to all -->
            <form action="sendmail/automailToAll.php" method="post">
                <input type="submit" value="Send Email to All" class="send-email-button">
            </form>
        </div>
    </div>
</body>
    </div>
</body>

</html>
