<?php include 'db_conn.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Front Page</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="front.css">
</head>

<body>

  <div class="container text-center fade-in mt-4">
    <h1 class="display-5">Welcome to the Front Page</h1>
    <p class="lead">This is the main area accessible to all users.</p>
    <nav class="my-3">
      <ul class="nav justify-content-center">
        <li class="nav-item">
          <button class="nav-link btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            Login
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#registerModal">
            Sign Up
          </button>
        </li>
      </ul>
    </nav>
  </div>

  <!-- Carousel Section -->
  <div class="container fade-in">
    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="room1.jpg" class="d-block w-100" alt="Front Image 1" data-bs-toggle="modal" data-bs-target="#modal1" />
        </div>
        <div class="carousel-item">
          <img src="room2.jpg" class="d-block w-100" alt="Front Image 2" data-bs-toggle="modal" data-bs-target="#modal2" />
        </div>
        <div class="carousel-item">
          <img src="room3.jpg" class="d-block w-100" alt="Front Image 3" data-bs-toggle="modal" data-bs-target="#modal3" />
        </div>
        <div class="carousel-item">
          <img src="room4.jpg" class="d-block w-100" alt="Front Image 4" data-bs-toggle="modal" data-bs-target="#modal4" />
        </div>
        <div class="carousel-item">
          <img src="room5.jpg" class="d-block w-100" alt="Front Image 5" data-bs-toggle="modal" data-bs-target="#modal5" />
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-4">
        <h2 class="text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> Login</h2>
        <form action="login.php" method="POST">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
            <label for="username"><i class="bi bi-person"></i> Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
            <label for="password"><i class="bi bi-lock"></i> Password</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-arrow-right-circle"></i> Login
          </button>
        </form>
        <p class="text-center mt-3">Don't have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register here</a>.
        </p>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-4" style="max-width: 450px; margin: auto;">
        <h2 class="text-center mb-4"><i class="bi bi-person-plus"></i> Register</h2>
        <form action="register.php" method="POST">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="fName" name="fName" placeholder="Full Name" required />
            <label for="fName"><i class="bi bi-person"></i> Full Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="regUsername" name="username" placeholder="Username" required />
            <label for="regUsername"><i class="bi bi-person-badge"></i> Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
            <label for="email"><i class="bi bi-envelope"></i> Email</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="regPassword" name="password" placeholder="Password" required />
            <label for="regPassword"><i class="bi bi-lock"></i> Password</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
            <label for="confirm_password"><i class="bi bi-lock"></i> Confirm Password</label>
          </div>
          <div class="form-floating mb-3">
            <select class="form-select" id="role" name="role" required>
              <option value="user" selected>User</option>
              <option value="admin">Admin</option>
            </select>
            <label for="role"><i class="bi bi-person-gear"></i> Role</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-circle"></i> Register
          </button>
        </form>
        <p class="text-center mt-3">Already have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login here</a>.
        </p>
      </div>
    </div>
  </div>

  <footer class="text-center mt-4 mb-3">
    <p class="text-muted">&copy; 2025 OWYESSSSSSSSSS</p>
  </footer>

  <!-- Toast Container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
    <div id="signupToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          🎉 Registration successful! You can now log in.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get("register") === "success") {
        const toast = new bootstrap.Toast(document.getElementById("signupToast"));
        toast.show();

        const loginModal = new bootstrap.Modal(document.getElementById("loginModal"));
        loginModal.show();

        // Clean up URL
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    });
  </script>
</body>

</html>