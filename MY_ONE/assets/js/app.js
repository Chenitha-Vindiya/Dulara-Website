//Scroll to Top Button
const scrollTopBtn = document.getElementById('scrollTopBtn');

window.addEventListener('scroll', () => {
  if (window.scrollY > 75) {
    scrollTopBtn.classList.add('show');
  } else {
    scrollTopBtn.classList.remove('show');
  }
});

scrollTopBtn.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

//Mobile Menu
function toggleMenu() {
  const overlay = document.getElementById('mobileMenuOverlay');
  const body = document.body;

  // Toggle the 'show' class to control visibility (display: flex)
  overlay.classList.toggle('show');

  // Toggle a class on the body to prevent background scrolling
  body.classList.toggle('menu-open');
}

//Sign in Effect
const signinBtn = document.querySelector('.btn-primary');
const heroContent = document.querySelector('.hero-content');
const formBox = document.querySelector('.form-container');

signinBtn.addEventListener('click', () => {
  // Move welcome text left and hide sign-in button
  heroContent.classList.add('move-left');
  signinBtn.classList.add('disappearBtn');

  // Activate the form with animation
  formBox.classList.add('form-active');
});
