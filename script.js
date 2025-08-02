// script.js

// Fade in product cards on scroll
document.addEventListener('DOMContentLoaded', () => {
  const productCards = document.querySelectorAll('.product-card');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-fade-in');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  productCards.forEach(card => {
    observer.observe(card);
  });

  // Attach cart add-to-cart buttons
  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
      const productName = button.getAttribute('data-product');
      alert(`${productName} has been added to your cart!`);
      // Optional: Add to localStorage or session cart array here
    });
  });

  // Menu toggle for mobile
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu = document.querySelector('nav ul');

  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  }

  // Track package
  const trackForm = document.getElementById('track-form');
  const trackResult = document.getElementById('track-result');

  if (trackForm && trackResult) {
    trackForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const trackingId = document.getElementById('tracking-id').value;
      // Simulate tracking lookup
      setTimeout(() => {
        trackResult.innerText = `Package ${trackingId} is currently in transit.`;
      }, 1000);
    });
  }
});

// Animate header text on load
window.addEventListener('load', () => {
  const header = document.querySelector('header h1');
  if (header) {
    header.classList.add('text-slide-in');
  }
});

// Simple feedback success message
function submitFeedback(event) {
  event.preventDefault();

  const feedbackForm = document.getElementById('feedback-form');
  const feedbackMessage = document.getElementById('feedback-message');

  setTimeout(() => {
    feedbackMessage.textContent = 'Thank you for your feedback!';
    feedbackMessage.classList.add('success');
    feedbackForm.reset();
  }, 800);
}

document.addEventListener('DOMContentLoaded', () => {
  const feedbackForm = document.getElementById('feedback-form');
  if (feedbackForm) {
    feedbackForm.addEventListener('submit', submitFeedback);
  }
});

