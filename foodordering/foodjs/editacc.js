function initAutocomplete() {
    // Initialize the autocomplete object and link it to the address input field
    var addressInput = document.getElementById('address');
    var autocomplete = new google.maps.places.Autocomplete(addressInput, {
        types: ['geocode'],
    });

    // Add a listener to handle place selection
    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            // If no place details are found, show an alert
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }

        // Populate the address field with the selected place, but allow editing
        addressInput.value = place.formatted_address || place.name || '';
    });

    // Optional: Geolocation bias to user's location if available
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy,
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

// Initialize the autocomplete functionality when the page loads
document.addEventListener('DOMContentLoaded', function () {
    initAutocomplete();
});
