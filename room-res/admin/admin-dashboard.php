<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: /room-res/index.php");
  exit;
}

include 'query.php';
$rows = [];
while ($row = $result->fetch_assoc()) {
  $rows[] = $row;
}
include 'headers.php';
include 'sidebars.php';
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content pt-4">
    <div class="container-fluid">

      <!-- Dashboard Header -->
      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <h3 class="text-primary font-weight-bold mb-0">
            <i class="fas fa-calendar-alt mr-2"></i> Reservation Records
          </h3>
        </div>
      </div>

      <!-- Responsive Table -->
      <div class="table-responsive">
        <table id="reservationTable" class="table table-hover table-bordered table-striped shadow-sm rounded">
          <thead class="thead-light text-center">
            <tr>
              <th>ID</th>
              <th>Room</th>
              <th>Name</th>
              <th>Company</th>
              <th>Contact #</th>
              <th>Email</th>
              <th>Date</th>
              <th>Guests</th>
              <th>Payment Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row):
              $id = htmlspecialchars($row['id']);
              $isPast = strtotime($row['booking_date']) < strtotime(date('Y-m-d'));
              $rowClass = $isPast ? 'table-secondary' : 'table-light font-weight-bold';
            ?>
              <tr class="<?= $rowClass ?>">
                <td><?= $id ?></td>
                <td><?= htmlspecialchars($row['room_id']) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['user_company']) ?></td>
                <td><?= htmlspecialchars($row['user_contact']) ?></td>
                <td><?= htmlspecialchars($row['user_email']) ?></td>
                <td><?= htmlspecialchars($row['booking_date']) ?></td>
                <td><?= htmlspecialchars($row['guest_count']) ?></td>
                <td>
                  <?php if ($row['payment_status'] === 'paid'): ?>
                    <span class="badge badge-success">Paid</span>
                  <?php elseif (!empty($row['payment_requested']) && $row['payment_requested'] == 1): ?>
                    <span class="badge badge-info">Payment Requested</span>
                  <?php else: ?>
                    <span class="badge badge-warning">Pending</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <!-- Mark Paid (Admin Only) -->
                    <?php if ($row['payment_status'] !== 'paid' && !empty($row['payment_requested']) && $row['payment_requested'] == 1): ?>
                      <form method="POST" action="mark_paid.php" style="display:inline;">
                        <input type="hidden" name="reservation_id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-success btn-sm">
                          <i class="fas fa-check"></i> Mark Paid
                        </button>
                      </form>
                    <?php endif; ?>

                    <!-- Edit Button -->
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $id ?>">
                      <i class="fas fa-edit"></i> Edit
                    </button>

                    <!-- Cancel Button -->
                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelModal<?= $id ?>">
                      <i class="fas fa-trash-alt"></i> Cancel
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php foreach ($rows as $row):
          $id = htmlspecialchars($row['id']);
        ?>
          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="editLabel<?= $id ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <form method="POST" action="edit-reservation.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="modal-content">
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                      <i class="fas fa-pencil-alt mr-2"></i> Edit Reservation #<?= $id ?>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" name="user_name" class="form-control" value="<?= htmlspecialchars($row['user_name']) ?>" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Company</label>
                        <input type="text" name="user_company" class="form-control" value="<?= htmlspecialchars($row['user_company']) ?>">
                      </div>
                      <div class="form-group col-md-6">
                        <label>Contact #</label>
                        <input type="text" name="user_contact" class="form-control" value="<?= htmlspecialchars($row['user_contact']) ?>" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="user_email" class="form-control" value="<?= htmlspecialchars($row['user_email']) ?>" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" value="<?= htmlspecialchars($row['booking_date']) ?>" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Number of Guests</label>
                        <input type="number" name="guest_count" class="form-control" min="1" max="20" value="<?= htmlspecialchars($row['guest_count']) ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Cancel Modal -->
          <div class="modal fade" id="cancelModal<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="cancelLabel<?= $id ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <form method="POST" action="cancels-reservation.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash-alt mr-2"></i> Cancel Reservation #<?= $id ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to cancel this reservation?</p>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>

<?php include 'footer.php'; ?>
<script src="scripts.js"></script>
<?php $conn->close(); ?>