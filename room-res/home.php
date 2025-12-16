<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

require_once __DIR__ . '/db.php';

// Fetch all rooms
$result = $conn->query("SELECT room_id, room_name, capacity, image_path FROM room_details ORDER BY room_id ASC");
if (!$result) {
    die("Database query failed: " . $conn->error);
}

?>

<?php include 'head.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h2 class="mb-4 text-dark font-weight-bold">Available Rooms</h2>

      <!-- Add Room Button (Admin Only) -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-success" data-toggle="modal" data-target="#addRoomModal">
          <i class="fas fa-plus-circle mr-1"></i> Add Room
        </button>
      <?php endif; ?>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <?php if ($result->num_rows > 0): ?>
          <?php while ($room = $result->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card shadow-sm border-0">
                <?php
                  $base = '/room-res/'; // site base URL
                  $img = $room['image_path'] ?: 'uploads/default.png';
                  // if already absolute or external, use as-is; otherwise prefix base
                  if (!preg_match('#^(https?:)?/#i', $img)) {
                    $img = $base . ltrim($img, '/');
                  }
                ?>
                <img src="<?= htmlspecialchars($img) ?>"
                     class="card-img-top"
                     alt="<?= htmlspecialchars($room['room_name']) ?>">
                <div class="card-body">
                  <h5 class="card-title text-primary"><?= htmlspecialchars($room['room_name']) ?></h5>
                  <p class="card-text text-muted">
                    Can accommodate up to <?= htmlspecialchars($room['capacity']) ?> guests
                  </p>
                  <button class="btn btn-outline-primary"
                          data-toggle="modal"
                          data-target="#bookingModal"
                          data-room="<?= htmlspecialchars($room['room_name']) ?>"
                          data-roomid="<?= $room['room_id'] ?>">
                    <i class="fas fa-calendar-plus mr-1"></i> Book Now
                  </button>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-muted">No rooms available yet.</p>
        <?php endif; ?>

      </div>
    </div>
  </section>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="bookingModalLabel">
          <i class="fas fa-calendar-check mr-2"></i> Room Booking Form
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php include 'form.php'; ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Room Modal (Admin Only) -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
  <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="addRoomModalLabel">
            <i class="fas fa-plus-circle mr-2"></i> Add New Room
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="save-room.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="roomName">Room Name</label>
              <input type="text" class="form-control" id="roomName" name="room_name" required>
            </div>
            <div class="form-group">
              <label for="roomCapacity">Capacity</label>
              <input type="number" class="form-control" id="roomCapacity" name="room_capacity" min="1" required>
            </div>
            <div class="form-group">
              <label for="roomImage">Room Image</label>
              <input type="file" class="form-control-file" id="roomImage" name="room_image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save mr-1"></i> Save Room
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
</body>
</html>