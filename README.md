Hospital Appointment System
The Hospital Appointment System is a web-based application designed to streamline the process of scheduling, managing, and tracking hospital appointments. It supports different user roles, such as patients, doctors, and administrators, each with specific functionalities.

Features
Patient Features:
Register/Login to the system.
View nearby hospitals using OpenStreetMap integration.
Book, reschedule, or cancel appointments.
View appointment status (e.g., Approved, Pending).
Doctor Features:
Login to the system.
View and manage patient appointments.
Approve or reject appointment requests.
Admin Features:
Manage users (patients, doctors, and other administrators).
View hospital system analytics.
Installation Guide
Prerequisites:
XAMPP or a similar PHP and MySQL server.
A web browser for testing the application.
PHP 7.4+ and MySQL 5.7+ (comes pre-installed with XAMPP).
Steps:
Download the project files and place them in the htdocs folder of your XAMPP installation (e.g., C:\xampp\htdocs\hospitalappointmentsys).
Place your default background images in the default_images folder (e.g., C:\xampp\htdocs\hospitalappointmentsys\default_images).
Import the database:
Open phpMyAdmin (http://localhost/phpmyadmin).
Create a database named hospitalappointmentsys.
Import the SQL file (database/hospitalappointmentsys.sql) provided in the project files.
Update the database connection settings:
Open db.php and ensure the following values are correct:
php
Copy code
$host = 'localhost';
$dbname = 'hospitalappointmentsys';
$username = 'root';
$password = ''; // Default for XAMPP
Start the XAMPP server:
Start Apache and MySQL services via the XAMPP control panel.
Access the application:
Open your browser and navigate to http://localhost/hospitalappointmentsys.
Project Structure
bash
Copy code
hospitalappointmentsys/
├── admin/                # Admin-specific pages
├── doctor/               # Doctor-specific pages
├── user/                 # Patient-specific pages
├── assets/               # CSS, JS, and shared resources
├── default_images/       # Background images and other static resources
├── database/             # SQL file for database import
├── db.php                # Database connection file
├── login.php             # Login page
├── register.php          # Registration page
├── README.md             # Project documentation
Technologies Used
Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL
Mapping: Leaflet.js with OpenStreetMap
Hosting: AwardSpace (Optional)
Deployment on AwardSpace
Steps:
Upload the project files to the /htdocs directory of AwardSpace.
Update the database connection in db.php with AwardSpace database credentials.
Import the SQL file to your AwardSpace database via phpMyAdmin.
Test your site by navigating to the provided AwardSpace URL.
Troubleshooting
Common Issues:
Blank Page or Error:

Check your db.php for correct database credentials.
Verify your PHP version is compatible with the project.
Map Not Displaying:

Ensure you have included the Leaflet.js CSS and JS files properly.
Image Not Showing:

Verify the file paths are correct.
Check browser console for any file-related errors.
Future Enhancements
Add notifications for patients and doctors.
Integrate payment gateways for premium services.
Implement a chatbot for FAQs and assistance.
License
This project is open-source and can be freely used and modified. Attribution is appreciated but not required.