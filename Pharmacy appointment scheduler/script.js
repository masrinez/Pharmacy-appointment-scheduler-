// Load the Google API client
gapi.load('client', start);

// Initialize the Google API client
function start() {
  gapi.client.init({
    apiKey: 'AIzaSyCAO90mz-vmc9TGh1TViD-rAS0WjLXqF4c',
    clientId: '127015946605-35vtis3951l4qlks68n92a8l5qe3701p.apps.googleusercontent.com',
    discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
    scope: "https://www.googleapis.com/auth/calendar"
  }).then(function() {
    // Authorization successful
    // TODO: Implement the rest of the application
  }, function(error) {
    // Authorization failed
    console.error(error);
  });
}

// Get the appointment details from the user
function getAppointmentDetails() {
  // Retrieve input values
  const Appointmentdate = document.getElementById('Appointmentdate').value;
  const Scheduledtime = document.getElementById('Scheduledtime').value;
  const Estimated_duration_at_pharmacy = document.getElementById('Estimated_duration_at_pharmacy').value;
  // ...

  // Schedule the appointment in Google Calendar
  const event = {
    'summary': medications,
    'location': `${Pharmacyaddress},${pharmacy_emailaddress}, ${Pharmacycounty}, ${Pharmacycountry}, ${Pharmacypostcode}`,
    'description': `Dosage form: ${dosageform} \n Route of Administration: ${route-of-admin} \n Medication strength: ${Medication_strength}`,
    'start': {
      'dateTime': new Date(`${Appointmentdate}T${Scheduledtime}`).toISOString(),
      'timeZone': 'UTC',
    },
    'end': {
      'dateTime': new Date(`${Appointmentdate}T${Scheduledtime}`).addHours(parseInt(Estimated_duration_at_pharmacy)).toISOString(),
      'timeZone': 'UTC',
    },
    'reminders': {
      'useDefault': false,
      'overrides': [
        {'method': 'sms', 'minutes': 60},
        {'method': 'popup', 'minutes': 10},
      ],
    },
  };

  const request = gapi.client.calendar.events.insert({
    'calendarId': 'primary',
    'resource': event,
  });

  request.execute(function(event) {
    console.log('Event created: ' + event.htmlLink);
  });

  // Send the SMS reminder
  const smsRequest = fetch(`https://api.sms-provider.com/send?number=${Patient_Phone_Number}&message=${encodeURIComponent('Your appointment is coming up soon!')}`);
}
