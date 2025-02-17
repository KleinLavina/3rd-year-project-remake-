<style>
/* Wrapper for the whole section */
.background-container {
  position: relative;
  width: 100%;
  height: 70vh; /* Full height of the viewport */
  background-image: url('images/R.gif');
  background-size: cover;
  background-position: center;
  overflow: hidden;
}

/* Hue overlay */
.background-container::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 45, 45, 0.7); /* Change color and opacity */
  z-index: 1;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 -4px 8px rgba(0, 0, 0, 0.3), 
              4px 0 8px rgba(0, 0, 0, 0.3), -4px 0 8px rgba(0, 0, 0, 0.3);
}

/* Text Content */
/* Text Content */
.text-content {
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%); /* Center the text */
  text-align: center;
  color: white; /* Text color */
  z-index: 2; /* Ensure it appears above the overlay */
  text-shadow: 0 0 8px rgba(255, 255, 255, 0.5); /* Reduced glow for text */
}

.text-content h1 {
  font-size: 3rem; /* Adjust font size for the heading */
  margin-bottom: 10px;
}

.text-content p {
  font-size: 1.5rem; /* Adjust font size for the paragraph */
}

  </style>
<div class="background-container">
  <!-- Text Content Section -->
  <div class="text-content">
    <h1>Welcome to Craken Tech</h1>
    <p>Your technology partner for innovation and growth.</p>
  </div>
</div>