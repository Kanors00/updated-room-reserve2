<?php
session_start();

include 'sql.php';
include 'header.php';
include 'sidebar.php';

// Protect route
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'user' && $_SESSION['role'] !== 'admin')) {
  header("Location: ../login.php");
  exit;
}

// --- Dynamic Query Logic (Securely filtered) ---
if ($_SESSION['role'] === 'admin') {
  $stmt = $conn->prepare("SELECT * FROM reservations");
  $dashboard_title = "All Reservation Records";
} else {
  // CRITICAL: Filter data using the logged-in user's email
  $stmt = $conn->prepare("SELECT * FROM reservations WHERE user_email = ?");
  $stmt->bind_param("s", $_SESSION['user_email']);
  $dashboard_title = "My Reservation Records";
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content pt-4">
    <div class="container-fluid">

      <!-- Personalized Greeting Widget -->
      <div class="alert alert-info shadow-sm mb-4">
        <h4 class="alert-heading">
          Welcome Back, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['username']) ?></strong>!
        </h4>
        <p>This is your personalized dashboard view showing only your data.</p>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary font-weight-bold">
          <i class="fas fa-calendar-alt mr-2"></i>
          <?= htmlspecialchars($dashboard_title); ?>
        </h3>
      </div>

      <!-- Table Structure -->
      <table id="reservationTable" class="table table-hover table-bordered table-striped shadow-sm rounded">
        <thead class="thead-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Company</th>
            <th>Contact #</th>
            <th>Email</th>
            <th>Date</th>
            <th>Guests</th>
            <th>Payment Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['user_name']) ?></td>
              <td><?= htmlspecialchars($row['user_company']) ?></td>
              <td><?= htmlspecialchars($row['user_contact']) ?></td>
              <td><?= htmlspecialchars($row['user_email']) ?></td>
              <td><?= htmlspecialchars($row['booking_date']) ?></td>
              <td><?= htmlspecialchars($row['guest_count']) ?></td>
              <td>
                <?php if ($row['payment_status'] === 'paid'): ?>
                  <span class="badge badge-success">Paid</span>
                <?php else: ?>
                  <span class="badge badge-warning">Pending</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                  <!-- Admin: Mark Paid -->
                  <?php if ($row['payment_status'] !== 'paid'): ?>
                    <form method="POST" action="mark_paid.php" style="display:inline;">
                      <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="btn btn-success btn-sm m-1">
                        <i class="fas fa-check"></i> Mark Paid
                      </button>
                    </form>
                  <?php endif; ?>

                  <!-- Admin: View Receipt -->
                  <?php if (!empty($row['receipt_path'])): ?>
                    <a href="<?= htmlspecialchars($row['receipt_path']) ?>" target="_blank" class="btn btn-secondary btn-sm m-1">
                      <i class="fas fa-file-alt"></i> View Receipt
                    </a>
                  <?php endif; ?>

                <?php else: ?>
                  <!-- User: Pay Now -->
                  <?php if ($row['payment_status'] !== 'paid'): ?>
                    <form method="POST" action="pay_now.php" style="display:inline;">
                      <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="btn btn-primary btn-sm m-1">
                        <i class="fas fa-credit-card"></i> Pay Now
                      </button>
                    </form>

                    <!-- User: Upload Receipt -->
                    <form method="POST" action="upload_receipt.php" enctype="multipart/form-data" style="display:inline;">
                      <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                      <input type="file" name="receipt" accept="image/*,.pdf" class="d-none" id="receiptInput<?= $row['id'] ?>" onchange="this.form.submit()">
                      <button type="button" class="btn btn-info btn-sm m-1" onclick="document.getElementById('receiptInput<?= $row['id'] ?>').click();">
                        <i class="fas fa-upload"></i> Upload Receipt
                      </button>
                    </form>
                  <?php endif; ?>

                  <!-- User: View Receipt -->
                  <?php if (!empty($row['receipt_path'])): ?>
                    <a href="<?= htmlspecialchars($row['receipt_path']) ?>" target="_blank" class="btn btn-secondary btn-sm m-1">
                      <i class="fas fa-file-alt"></i> View Receipt
                    </a>
                  <?php endif; ?>
                <?php endif; ?>


                <!-- Cancel Button (both roles) -->
                <button class="btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#cancelModal<?= $row['id'] ?>">
                  <i class="fas fa-trash-alt"></i> Cancel
                </button>
              </td>
            </tr>
            <!-- Cancel Modal -->
            <div class="modal fade" id="cancelModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel<?= $row['id'] ?>">Confirm Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to cancel this reservation?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form method="POST" action="cancel-reservation.php" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <button type="submit" class="btn btn-danger">Cancel Reservation</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
<?php include 'footer.php'; ?>
</body>

</html>