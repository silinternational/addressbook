/**
 * Namespace object for our custom JavaScript.
 * @type {Object}
 */
var cstm = {};

/**
 * Any time the basic search form's input field might have changed, this checks
 * for whether we should switch to the advanced input field based on the
 * contents of the basic search field.
 * 
 * @param {HTMLInputElement} field The input field from the basic search form.
 * @param {Event} event The event that triggered this call.
 */
cstm.basicInputTrigger = function(field) {
  
  //// TEMP
  //if (typeof(console) !== 'undefined') {
  //  console.log('basicInputTrigger');
  //}
  
  // If a timer started by a previous call to this function is still running,
  // cancel it.
  if (cstm.basicInputTriggerTimeout !== null) {
    clearTimeout(cstm.basicInputTriggerTimeout);
  }
  
  // Start a timer that, if not canceled, will...
  cstm.basicInputTriggerTimeout = setTimeout(function() {
    
    // Check the contents of the basic input field.
    cstm.checkBasicInput(field);
    
    // Clear the timeout ID (so that we know a timer is no longer running).
    cstm.basicInputTriggerTimeout = null;
    
  }, 50); // 50 = 0.05 second delay.
}

cstm.checkBasicInput = function(field) {
  
  //// TEMP
  //if (typeof(console) !== 'undefined') {
  //  console.log('checkBasicInput');
  //}
  
  // If appropriate, switch to the advanced input form.
  cstm.toggleAdvFormIfAppropriate(field, 'last');
}

/**
 * Indicate whether the value of the field contains at least one space character
 * and at least one letter.
 * 
 * @param {HTMLInputElement} field
 * @returns {Boolean}
 */
cstm.containsSpaceAndLetter = function(field) {

  // If the field contains a space character...
  if (field.value.indexOf(' ') >= 0) {
    
    // Indicate whether the field's value also contains a letter.
    return /[A-Za-z]/.test(field.value);
  }
  // Otherwise return false (since it doesn't contain a space).
  else {
    return false;
  }
};

cstm.copyAdvancedToBasic = function() {
  
  // Get the content from the first advanced field that has something in it.
  var value = '';
  if (cstm.first && cstm.first.value) value = cstm.first.value;
  else if (cstm.last && cstm.last.value) value = cstm.last.value;
  else if (cstm.email && cstm.email.value) value = cstm.email.value;
  else if (cstm.phone && cstm.phone.value) value = cstm.phone.value;
  
  // If we have more than an empty string, copy it into the basic field.
  if (value) cstm.any.value = value;
  
  // Empty the advanced fields.
  if (cstm.first) cstm.first.value = '';
  if (cstm.last) cstm.last.value = '';
  if (cstm.email) cstm.email.value = '';
  if (cstm.phone) cstm.phone.value = '';
};

cstm.copyBasicToAdvanced = function() {
  
  // If we don't have the basic search field, or if it has nothing in it, don't
  // bother with the rest of this function.
  if (!(cstm.any && cstm.any.value)) return;
  
  // Get the contents of the basic field, removing any leading/trailing
  // whitespace.
  var vAny = cstm.any.value.replace(/^\s+/, '').replace(/\s+$/, '');
  
  // If that leaves us with an empty string, don't bother with the rest of this
  // function.
  if (!vAny) return;
  
  // If the resulting string looks like a phone number...
  if ((/[0-9]/).test(vAny)) {
    
    // Copy the value into the phone field for the advanced search.
    if (cstm.phone) cstm.phone.value = vAny;
  }
  // OR, if it looks like an email address...
  else if ((/[@.]/).test(vAny)) {
    
    // Copy the value into the email field for the advanced search.
    if (cstm.email) cstm.email.value = vAny;
  }
  // Otherwise...
  else {
    
    // If there's a space in it.
    var vAnyPieces = vAny.split(' ');
    if (vAnyPieces.length > 1) {
      
      // Put the first part in the first name field.
      if (cstm.first) cstm.first.value = vAnyPieces.shift();
      
      // Put the rest in the last name field.
      if (cstm.last) cstm.last.value = vAnyPieces.join(' ');
    }
    // Otherwise, put it all in the first name field.
    else if (cstm.first) cstm.first.value = vAny;
  }
  
  // Empty the basic field.
  cstm.any.value = '';
};

cstm.setup = function() {
  
  // Initialize any necessary variables.
  cstm.basicInputTriggerTimeout = null;
  
  // Get the form, the divs holding both sets of fields, and the fields
  // themselves.
  cstm.f = document.getElementById('search-form');
  cstm.a = document.getElementById('advanced-search');
  cstm.b = document.getElementById('basic-search');
  if (cstm.f) {
    cstm.any = cstm.f.elements['SearchForm[any]'];
    cstm.first = cstm.f.elements['SearchForm[first]'];
    cstm.last = cstm.f.elements['SearchForm[last]'];
    cstm.email = cstm.f.elements['SearchForm[email]'];
    cstm.phone = cstm.f.elements['SearchForm[phone]'];
  }
  
  // Show the form toggler.
  var link = document.getElementById('form-toggler');
  if (link) link.style.display = 'inline';
  
  // Get the results container divs as well.
  cstm.rco = document.getElementById('results-container-outer');
  cstm.rc = document.getElementById('results-container');

  // If we found the results container(s)...
  if (cstm.rco && cstm.rc) {
    
    // Listen for window resizes.
    $(window).resize(function() {

      // When the window is resized, update whether or not we're showing the
      // shadow used to indicate the ability to scroll horizontally.
      cstm.updateHorizScrollShadow();
    });
    
    // Do the same for scroll events.
    $(cstm.rc).scroll(function() {
      cstm.updateHorizScrollShadow();
    });

    // Go ahead and update the horizontal scroll shadow so that the initial view
    // is correct.
    cstm.updateHorizScrollShadow();
  }
};

cstm.toggleAdvanced = function(opt_advFieldToFocus, opt_transferValues) {
  
  // Note whether to transfer values between the basic and advanced form fields.
  // If not told one way or the other, then do so. If told, then convert the
  // given value to a boolean and use that.
  var transferValues = (opt_transferValues == undefined) ?
                       true :
                       !!opt_transferValues;
  
  // If we have the form itself as well as the divs holding the advanced and the
  // basic search fields...
  if (cstm.f && cstm.a && cstm.b) {
    
    // Get the toggler link.
    var link = document.getElementById('form-toggler');
  
    // If the basic search is NOT currently hidden...
    if (cstm.b.style.display != 'none') {
      
      // Show the advanced search.
      cstm.a.style.display = 'block';
      
      // Hide the basic search.
      cstm.b.style.display = 'none';
      
      // Update the toggle link's text.
      if (link) link.innerHTML = 'basic';
      
      // If told to transfer values...
      if (transferValues) {
        
        // Copy the entered content from the basic field to the advanced field.
        cstm.copyBasicToAdvanced();
      }
      
      // If told a particular advanced field to put focus in
      //    AND
      // if such a field exists...
      if ((opt_advFieldToFocus != undefined) && cstm[opt_advFieldToFocus]) {
        
        // Try to put focus in that field.
        if (cstm[opt_advFieldToFocus].focus) cstm[opt_advFieldToFocus].focus();
      }
      // Otherwise...
      else {
      
        // Put focus in the last non-empty advanced field (defaulting to the
        // first-name field).
        var fieldToFocus;
        if      (cstm.phone && cstm.phone.value) fieldToFocus = cstm.phone;
        else if (cstm.email && cstm.email.value) fieldToFocus = cstm.email;
        else if (cstm.last  && cstm.last.value ) fieldToFocus = cstm.last;
        else                                     fieldToFocus = cstm.first;
        if (fieldToFocus && fieldToFocus.focus) fieldToFocus.focus();
      }
    }
    // Otherwise...
    else {
      
      // If told to transfer values...
      if (transferValues) {
        
        // Copy (at least some of) the entered content from advanced fields to
        // the basic field.
        cstm.copyAdvancedToBasic();
      }
      
      // Show the basic search.
      cstm.b.style.display = 'block';
      
      // Hide the advanced search.
      cstm.a.style.display = 'none';
      
      // Update the toggle link's text.
      if (link) link.innerHTML = 'advanced';
      
      // Put focus in the basic field.
      if (cstm.any && cstm.any.focus) cstm.any.focus();
    }
  }
};

cstm.toggleAdvFormIfAppropriate = function(field, opt_advFieldToFocus) {
  
  //// TEMP
  //if (typeof(console) !== 'undefined') {
  //  console.log('toggleAdvFormIfAppropriate');
  //}
  
  // If the given field contains both at least one space and at least one
  // letter...
  //
  // NOTE: We're allowing spaces with numbers because phone numbers may contain
  //       spaces.
  //
  if (cstm.containsSpaceAndLetter(field)) {
    
    // Switch to showing the advanced search fields (optionally putting focus
    // in a particular field).
    cstm.toggleAdvanced(opt_advFieldToFocus);
  }
};

cstm.updateHorizScrollShadow = function() {
  
  // If we have the results container(s)...
  if (cstm.rco && cstm.rc) {

    // Get jQuery objects for the results container divs.
    var jRCO = $(cstm.rco), jRC = $(cstm.rc);

    // If the RIGHT edge of the results container is scrolled out of sight
    // (i.e. - if we CAN scroll horizontally and if we aren't fully scrolled
    // horizontally)...
    if ((cstm.rc.scrollWidth > (cstm.rc.clientWidth + 2)) &&
        (cstm.rc.scrollLeft < (cstm.rc.scrollWidth - cstm.rc.clientWidth))) {

      // Add a shadow to the right edge to indicate that.
      jRC.addClass('horizScrollRight');
    }
    // Otherwise...
    else {

      // Remove the shadow from the right edge.
      jRC.removeClass('horizScrollRight');
    }

    // If the LEFT edge of the results container is scrolled out of sight...
    if (cstm.rc.scrollLeft > 0) {

      // Add a shadow to the left edge to indicate that.
      jRCO.addClass('horizScrollLeft');
    }
    // Otherwise...
    else {

      // Remove the shadow from the left edge.
      jRCO.removeClass('horizScrollLeft');
    }
  }
}
