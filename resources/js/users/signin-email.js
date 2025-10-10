import axios from 'axios';

export function collectAndSendEmail(email) {
  // Collect the email from the user
  // ...

  // Send the email via Axios to the UserController
  axios.post('/signin-email', { email })
    .then(response => {
      // Handle the response from the UserController
      console.log('Email sent successfully:', response.data);
    })
    .catch(error => {
      // Handle any errors
      console.error('Error sending email:', error.response.data);
    });
}