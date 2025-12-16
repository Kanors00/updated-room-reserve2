<form id="bookingForm" action="admin.php" method="POST" novalidate>
  <div class="form-row">
    <!-- Full Name -->
    <div class="form-group col-md-6">
      <label for="userName">Full Name</label>
      <input type="text" class="form-control" id="userName" name="user_name" required>
    </div>

    <!-- Company -->
    <div class="form-group col-md-6">
      <label for="userCompany">Company / Agency</label>
      <input type="text" class="form-control" id="userCompany" name="user_company" required>
    </div>

    <!-- Contact -->
    <div class="form-group col-md-6">
      <label for="userContact">Contact Number</label>
      <input type="tel" class="form-control" id="userContact" name="user_contact" pattern="[0-9]{11}" maxlength="11" placeholder="e.g. 09123456789" required>
      <small class="form-text text-muted">Enter 11-digit mobile number.</small>
    </div>

    <!-- Email -->
    <div class="form-group col-md-6">
      <label for="userEmail">Email Address</label>
      <input type="email" class="form-control" id="userEmail" name="user_email" required>
    </div>

    <!-- Booking Date -->
    <div class="form-group col-md-6">
      <label for="bookingDate">Booking Date</label>
      <input type="text" class="form-control" id="bookingDate" name="booking_date" placeholder="Choose Date" required>
    </div>

    <!-- Guests -->
    <div class="form-group col-md-6">
      <label for="guestCount">Number of Guests</label>
      <input type="number" class="form-control" id="guestCount" name="guest_count" min="1" max="99" required>
    </div>

    <!-- Room ID (hidden, filled by JS when modal opens) -->
    <input type="hidden" id="roomId" name="room_id" value="">
  </div>

  <!-- Submit Button -->
  <div class="text-right mt-3">
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-calendar-check mr-2"></i> Book Now
    </button>
  </div>
</form>

<script>
document.getElementById("bookingForm").addEventListener("submit", function(e) {
  e.preventDefault(); // stop normal form submission

  const form = e.target;
  const formData = new FormData(form);

  fetch(form.action, {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    // ✅ Show SweetAlert2 success popup
    Swal.fire({
      icon: 'success',
      title: 'Booking Confirmed!',
      text: 'Your reservation has been successfully submitted.',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    }).then(() => {
      // Optional: redirect after confirmation
      window.location.href = "admin/user-dashboard.php";
    });
  })
  .catch(error => {
    // ❌ Show error popup
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Something went wrong while booking. Please try again.'
    });
  });
});
</script>