



$(window).on('beforeunload', function () {
  const scrollPosition = $(window).scrollTop();
  localStorage.setItem('scrollPos', scrollPosition);
});

$(document).ready(function () {
  const storedScrollPosition = localStorage.getItem('scrollPos');
  if (storedScrollPosition) {
    $(window).scrollTop(storedScrollPosition);
    localStorage.removeItem('scrollPos'); // Clear storage after use
  }
});