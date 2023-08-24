<!DOCTYPE html>
<html>
<body>

<h2>Dear Student,</h2>
<p>
We are pleased to inform you that your course Certificate Ready to Download. The Soft Copy of the Certificate.
<?php if (isset($_POST['pdfPath'])) {
?>
<form class="form-inline" action="www.asterisc/genc/view" method="POST">
  <label for="email">Register Email:</label>
  <input type="email" id="email" placeholder="Enter email" name="email">
  <!-- Include the hidden input field for the PDF path -->
  <button type="submit">Submit</button><br><br>
</form>
<!-- Convert $pdfPath into an anchor tag -->
<a href='<?php echo $pdfPath; ?>'>Download PDF</a><br><br>

We thank you for signing up for the course at our institution and placing your faith in us.
We hope that this course helps you achieve your objectives and wish you all the best in your future endeavors.

<?php echo $pdfPath; }?>

Best regards,
Chandrakant D. Bobade
Director
Asterisc Computer Institute
Call on : 8669804213 / 7743822228</p>

</body>
</html>
<?php echo $pdfPath; ?>
