$(document).ready(function () {

    // Initialize an empty array to store selected collaborator objects
    var selectedCollaborators = [];

    // Basic client-side validation (optional)
    function isValidUsername(username) {
        return username.trim().length >= 3; // Enforce minimum username length
    }

    function addUser(username) {
        // Check if user already exists
        var found = false;
        if (selectedCollaborators.includes(username)) { found = true; }


        if (!found) {
            // Username not found, add collaborator
            selectedCollaborators.push(username); // Assuming username is the unique identifier
            $('#collaborator-message').text("Collaborator added successfully!");
            updateSelectedCollaboratorsList(); // Update collaborator list (optional)
        } else {
            $('#collaborator-message').text("User already added.");
        }
    }

    $('#add-collaborator').on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        var username = $('#collaborator-username').val().trim(); // Trim leading/trailing spaces

        if (isValidUsername(username)) {
            // Send AJAX request to validate username
            $.ajax({
                url: '/search-collab', // Replace with your actual route
                data: { username: username },
                dataType: 'json',
                success: function (data) {
                    if (data.valid) {
                        // Username is valid, add collaborator (assuming server response includes collaborator data)
                        // Assuming username is in collaborator data
                        // Username is valid, add collaborator data (assuming data.collaborators holds collaborator objects)


                        addUser(data.collaborators[0].username);

                        updateSelectedCollaboratorsList(); // Update collaborator list (optional)

                        console.log(JSON.stringify(selectedCollaborators));
                    } else {
                        $('#collaborator-message').text("User doesn't exist.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle errors during AJAX request
                    console.error("Error validating collaborator:", textStatus, errorThrown);
                    $('#collaborator-message').text("An error occurred. Please try again.");
                }
            });
        } else {
            $('#collaborator-message').text("Username must be at least 3 characters.");
        }
    });

    $('#reset-collaborator').on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        selectedCollaborators = []; // Clear selected collaborators
        $('#collaborator-username').val(''); // Clear username input
        $('#collaborator-message').text(""); // Clear message
        updateSelectedCollaboratorsList(); // Update collaborator list (optional)
        console.log(selectedCollaborators);
    });

    // Function to update the list of selected collaborators (optional)
    function updateSelectedCollaboratorsList() {
        var html = '';
        for (var i = 0; i < selectedCollaborators.length; i++) {
            var username = selectedCollaborators[i]; // Assuming username is stored directly in the array
            html += '<li>' + username + '</li>';
        }

        $('#selected-collaborators').html(html);
    }

    // Triggering the Save Action (modified)
    $('#store-post').on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Gather selected collaborators


        // Convert usernames to a string (modify if needed)
        var selectedUsernames = selectedCollaborators.join(',');
        console.log(selectedUsernames);

        // Set the hidden input value
        $('#collaborators').val(selectedUsernames);

        // Submit the form
        $('#post-form').submit(); // Replace with your form ID
    });
});
const postTypeSelect = document.getElementById('postType');
const imageUpload = document.getElementById('image-upload');
const mediaAndThumbnail = document.getElementById('media-and-thumbnail');

postTypeSelect.addEventListener('change', function () {
    const selectedType = this.value;
    if (selectedType === 'image') {
        imageUpload.classList.remove('d-none');
        mediaAndThumbnail.classList.add('d-none');
    } else {
        imageUpload.classList.add('d-none');
        mediaAndThumbnail.classList.remove('d-none');
    }
});
