var myCarousel = document.querySelector('#carouselExampleDark')

var carousel = new bootstrap.Carousel(myCarousel, {
    interval: 5000,
    wrap: true
})



/* const copyButton = document.getElementById('copyLinkButton');
const copySuccess = document.getElementById('copySuccess');

copyButton.addEventListener('click', async () => {
    // Replace 'your_link_to_copy' with the actual link you want to copy
    const textToCopy = 'your_link_to_copy';

    try {
        await navigator.clipboard.writeText(textToCopy);
        copySuccess.classList.remove('visually-hidden'); // Show notification
        setTimeout(() => {
            copySuccess.classList.add('visually-hidden'); // Hide notification after a timeout
        }, 500); // Adjust timeout as needed (in milliseconds)
    } catch (err) {
        console.error('Failed to copy to clipboard:', err);
        // Handle potential errors (optional)
    }
}); */

