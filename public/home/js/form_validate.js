/* fungsi validasi form hubungi kami */
	function validateContactForm(sentMessage){
      if ( sentMessage.name.value == ""){
          alert("Please enter your name");
          sentMessage.name.focus();
          return (false);
      }
      if ( sentMessage.email.value == ""){
          alert("Please enter your email address.");
          sentMessage.email.focus();
          return (false);      
      }
      if ( sentMessage.phone.value == ""){
          alert("Please enter your phone number");
          sentMessage.phone.focus();
          return (false);
      }
      if ( sentMessage.message.value == ""){
          alert("Please enter a message.");
          sentMessage.message.focus();
          return (false);
      }
      return (true);
	}	