# Excel Data Import and Certificate Generation System

This repository contains a PHP-based web application that allows you to import Excel files, generate certificates, and send emails to students. 
The application utilizes the PHP server-side scripting language to process data and generate dynamic content.

## Features

- Import Excel files (.xls) containing student information.
- Generate certificates based on student data.
- Search for students by email.
- Download generated certificates in both image (JPEG) and PDF formats.
- Send emails to individual students with their certificates attached.
- Send emails to all students with their certificates attached.

## Usage

1. **Import Excel File**
    - Choose an Excel file (in .xls format) that contains student information.
    - Click the "Import" button to upload the file.

2. **Search Students**
    - Enter a student's email in the search bar and click "Search" to find their details.

3. **Generated Certificates**
    - A table displays student information and generated certificates.
    - Certificates are automatically generated from the student data and saved in both JPEG and PDF formats.

4. **Download Certificates**
    - Download generated certificates by clicking the "Download" or "Download-pdf" links in the table.

5. **Send Emails**
    - Click the "Send Mail" button next to a student's record to send them an email with their certificate attached.
    - To send emails to all students, use the "Send Email to All" button at the bottom of the page.

## Prerequisites

- A web server with PHP support (e.g., Apache, Nginx).
- PHP must be configured to handle file uploads and have the GD extension enabled for image processing.

## Setup

1. Clone this repository to your web server's root directory.
2. Configure your web server to process PHP files.
3. Ensure the `Certificates` directory is writable by the web server (for image and PDF storage).
4. Modify the email sending configuration in `sendmail/automail.php` and `sendmail/automailToAll.php`.

## Credits

This application was developed by [Your Name].

## License

This project is licensed under the [MIT License](LICENSE).
