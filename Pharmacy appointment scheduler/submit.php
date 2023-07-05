<?php

$file1 = '/PHPMailer/Exception.php';

$file2 = '/PHPMailer/PHPMailer.php';

$file3 = '/PHPMailer/SMTP.php';

$basePath = $_SERVER['DOCUMENT_ROOT'];

$fullPath1 = $basePath . $file1;

$fullPath2 = $basePath . $file2;

$fullPath3 = $basePath . $file3;


use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

require $fullPath1;

require $fullPath2;

require $fullPath3;

// Step 1: Retrieve form data

$patientName = $_POST['patientName'];

$patientEmail = $_POST['Patient-email-address'];

$appointmentDate = $_POST['Appointmentdate'];

$Scheduledtime = $_POST['Scheduledtime'];

$patientPhoneNumber = $_POST['Patient_Phone_Number'];

$DurationAtPharmacy = $_POST['Estimated_duration_at_pharmacy'];

$PharmacyName = $_POST['Pharmacy_name'];

$pharmacy_emailaddress = $_POST['pharmacy_emailaddress'];

$PharmacistName = $_POST['Pharmacist_name'];

$Medications = $_POST['medications'];

$DosageForm = $_POST['dosageform'];

$RouteOfAdministration = $_POST['route-of-admin'];

$MedicationStrength = $_POST['Medication_strength'];

$duration = $_POST['duration'];

$PharmacyAddress = $_POST['Pharmacyaddress'];

$PharmacyCounty = $_POST['Pharmacycounty'];

$PharmacyCountry = $_POST['Pharmacycountry'];

$PharmacyPostcode = $_POST['Pharmacypostcode'];

// Step 2: Store data in the database

$servername = "localhost";

$username = "id20730940_id19950619_vitalis";

$password = "Liverpool23?";

$database = "id20730940_id19950619_vitalis";

// Create connection

$conn = new mysqli($servername, $username, $password, $database);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to insert data into the appointment table

$sql = "INSERT INTO appointments (patientName, patientEmail, patientPhoneNumber, appointmentDate, scheduledtime, DurationAtPharmacy, PharmacyName, pharmacy_emailaddress, PharmacistName, Medications, DosageForm, RouteOfAdministration, MedicationStrength, duration, PharmacyAddress, PharmacyCounty, PharmacyCountry, PharmacyPostcode)
    
        VALUES ('$patientName', '$patientEmail', '$patientPhoneNumber', '$appointmentDate', '$Scheduledtime', '$DurationAtPharmacy', '$PharmacyName', '$pharmacy_emailaddress', '$PharmacistName', '$Medications', '$DosageForm', '$RouteOfAdministration', '$MedicationStrength', '$duration', '$PharmacyAddress', '$PharmacyCounty', '$PharmacyCountry', '$PharmacyPostcode')";

// Execute the SQL statement

if ($conn->query($sql) === TRUE) {
    echo "Appointment scheduled successfully. A confirmation mail will be sent out to the pharmacy soon";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection

$conn->close();

// Step 3: Send confirmation and reminder emails

try {
    $mail = new PHPMailer(true);

    // Set SMTP server settings
    
    $mail->isSMTP();
    
    $mail->Host = 'smtp.office365.com';
    
    $mail->SMTPAuth = true;
    
    $mail->Username = 'pharambook@outlook.com';
    
    $mail->Password = '@Liverpool23';
    
    $mail->SMTPSecure = 'STARTTLS';
    
    $mail->Port = 587;

    // Set email content and headers

    $mail->setFrom('pharambook@outlook.com', 'Pharmacy Appointment');

    $mail->addAddress($patientEmail, $patientName);

    $mail->addReplyTo('pharambook@outlook.com', 'Pharmacy Appointment');

    $mail->isHTML(true);

    // Confirmation email

    $confirmationSubject = "Appointment Confirmation";

    $confirmationMessage = "Dear <tr><td><strong>$patientName,</strong></td></tr><br><br>\n\nThis is a confirmation message of your upcoming pharmacy appointment at <tr><td><strong>$PharmacyName</strong></td></tr> pharmacy with Pharm <tr><td><strong>$PharmacistName.</strong></td></tr> <tr><td><strong>$PharmacyName pharmacy</strong></td></tr> appreciate your trust in our services and are committed to providing you with the best possible scheduling reminder.\n\n<br>This email is to confirm your appointment on <tr><td><strong>$appointmentDate</strong></td></tr> at <tr><td><strong>$PharmacyName</strong></td></tr> pharmacy<br>\n\nTime: <tr><td><strong>$Scheduledtime.</strong></td></tr><br>\n\n<br><br> <strong>Medication Information</strong><br><br>\n\n Medication: <tr><td>$Medications</td></tr><br>\n\nDosage Form: <tr><td>$DosageForm</td></tr><br>\n\nRoute of Administration: <tr><td>$RouteOfAdministration</td></tr><br>\n\n Medication strength: <tr><td>$MedicationStrength </td></tr><br> \n\n Duration: <tr><td>$duration</td></tr><br> \n\n<br><tr><td><strong>$PharmacyName</strong></td></tr> pharmacy looks forward to seeing you at <tr><td><strong>$PharmacyAddress,</strong></td></tr> <tr><td><strong>$PharmacyCounty,</strong></td></tr> <tr><td><strong>$PharmacyCountry.</strong></td></tr> <tr><td><strong>$PharmacyPostcode.</strong></td></tr><br><br>\n\nBest regards,<br><br>\n\n<tr><td><strong>$PharmacyName</strong></td></tr> pharmacy.";
    
    $mail->Subject = $confirmationSubject;
    
    $mail->Body = $confirmationMessage;
    
    $mail->send();
    
    echo 'Confirmation email sent successfully!';

    // Reminder email
    
    // Combine the appointment date and time into a single datetime string
    
    $scheduledDateTime = $appointmentDate.' '.$Scheduledtime;
    
    // Calculate the reminder time by subtracting 1 hour (3600 seconds)
    
    $reminderDateTime = date('Y-m-d H:i:s', strtotime($scheduledDateTime)-3600);
    
    // Create the email content
    
    $reminderSubject = "Appointment Reminder";
    
    $reminderMessage = "Dear <tr><td><strong>$patientName,</strong></td></tr><br><br>\n\nJust a friendly reminder that <tr><td><strong>$PharmacyName</strong></td></tr>pharmacy have scheduled an appointment for you.<br><br>\n\nAppointment Details:<br><br>\n\nDate: <tr><td><strong>$appointmentDate</strong></td></tr><br><br>\n\nTime: <tr><td><strong>$Scheduledtime.</strong></td></tr><br><br>\n\nSee you soon!<br><br>\n\nBest regards,<br><br>\n\n<tr><td><strong>$PharmacyName</strong></td></tr>pharmacy";
    
    // Send the reminder email
    
    $mail->Subject = $reminderSubject;
    
    $mail->Body = $reminderMessage;
    
    $mail->send($reminderDateTime);
    
    echo 'Reminder email sent successfully!';

    // Notification email to pharmacy
    
    $notificationSubject = "New Appointment Scheduled for $patientName";
    
    $notificationMessage = "Dear <tr><td>$PharmacyName</td></tr> Pharmacy,<br><br>\n\n\n I am writing to inform you that<tr><td>$patientName</td></tr> has made an appointment to meet with Pharm <tr><td>$PharmacistName</td></tr> at <tr><td>$PharmacyName</td></tr> Pharmacy, <tr><td>$PharmacyAddress,</td></tr> <tr><td>$PharmacyCounty,</td></tr> <tr><td>$PharmacyCountry,</td></tr> <tr><td>$PharmacyPostcode.</td></tr><br>\n\n<tr><td>$patientName</td></tr> will also be picking up their prescription during the visit.<br><br> \n\n Here are the patient details:<br><br>\n\n Patient name: <tr><td>$patientName</td></tr><br>\n\n Patient number: <tr><td>$patientPhoneNumber</td></tr><br>\n\nPatient email: <tr><td>$patientEmail</td></tr><br>\n\n Appointment time: <tr><td>$Scheduledtime</td></tr><br>\n\n Appointment date: <tr><td>$appointmentDate</td></tr><br><br> \n\n <strong> Medication Information </strong><br><br> \n\n Medication: <tr><td>$Medications</td></tr><br>\n\nDosage Form: <tr><td>$DosageForm</tr></td><br>\n\nRoute of Administration: <tr><td>$RouteOfAdministration</td></tr><br>\n\n Medication strength: <tr><td>$MedicationStrength</td></tr><br>\n\n Duration: <tr><td>$duration.</td></tr><br><br> \n\n <tr><td>$patientName</td></tr> kindly requests to spend <tr><td>$DurationAtPharmacy</td></tr> minute(s) at the pharmacy. It would be greatly appreciated if you could make the necessary arrangements to ensure that the medication is ready for pickup and that Pharmacist <tr><td>$PharmacistName</td></tr> is available on <tr><td>$appointmentDate</td></tr> by <tr><td>$Scheduledtime.</td></tr><br> \n\n In case there is any unforeseen delay of more than 24 hours after the scheduled time and date, please contact <tr><td>$patientName</td></tr> at <tr><td><strong>$patientEmail</strong></td></tr> or <tr><td><strong>$patientPhoneNumber.</strong></td></tr><br><br> \n\n Best regards,<br><br> \n\n <strong> Pharambook appointment scheduler.</strong>";
    
    $mail->clearAddresses();
    
    $mail->addAddress($pharmacy_emailaddress);
    
    $mail->Subject = $notificationSubject;
    
    $mail->Body = $notificationMessage;
    
    $mail->send();
    
    echo 'Notification email sent successfully!';

    
} catch (Exception $e) {
    
    echo 'Email could not be sent. Error: ', $mail->ErrorInfo;

    
}

?>
