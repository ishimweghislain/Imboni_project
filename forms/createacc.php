<?php
ob_start(); 
$redirect_url = '';

if (isset($_POST["submit"])) {
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';
    $role = isset($_POST["role"]) ? $_POST["role"] : '';

    $errors = array();

    // Check if it's a sign-in attempt
    if (!isset($_POST["fname"])) {
        // Sign-in process
        if (empty($email) || empty($password)) {
            array_push($errors, "Please fill in all fields!");
        }

        if (empty($errors)) {
            require_once "db.php";

            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                // Valid credentials
                if ($user['role'] == 'teacher') {
                    header("Location: ../tchers_dashboard/tdash.html");
                } elseif ($user['role'] == 'student') {
                    header("Location: ../stdents_dashboard/stdash.html");
                }
                exit();
            } else {
                array_push($errors, "Invalid email or password. Please put correct credentials !");
            }
        }
    } else {
        // Registration process
        $fname = isset($_POST["fname"]) ? $_POST["fname"] : '';
        $lname = isset($_POST["lname"]) ? $_POST["lname"] : '';

        // Validation checks
        if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($role)) {
            array_push($errors, "Please all fields are required !");
        }

        require_once "db.php";

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowcount = $result->num_rows;

        if ($rowcount > 0) {
            array_push($errors, "Email has been used, use another email!");
        }

        if (!empty($fname) && preg_match('/[0-9]/', $fname)) {
            array_push($errors, "First name must not contain numbers!");
        }

        if (!empty($lname) && preg_match('/[0-9]/', $lname)) {
            array_push($errors, "Last name must not contain numbers!");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid!");
        }

        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long!");
        }

        if ($role != 'teacher' && $role != 'student') {
            array_push($errors, "Please select a valid role!");
        }
        
        // If no errors, proceed with registration
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fname, $lname, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success custom-alert'>New account created successfully</div>";
                
                // Redirect based on role
                if ($role == 'student') {
                    header("Location: students_auth.html");
                } else {
                    header("Location: teachers_auth.html");
                } 
                exit(); 
            } else {
                array_push($errors, "Error: " . $stmt->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>LOGIN AND REGISTRATION FORM</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">

  <link href="../assets/css/main.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/createacc.css" rel="stylesheet">
</head>

<body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">

    <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container-fluid d-flex align-items-center justify-content-between">
    
    <a href="../index.html" class="logo d-flex align-items-center me-auto me-xl-0">
       <div class="mylogo">
      <i class="fas fa-book-open" style="color: #f44336; font-size: 40px; "></i>
      <h6 id="myHeading" style="font-size: 30px; color: black;">ImBoni</h6>

     </div>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="../index.html#hero" class="active" style="color: black;">Home</a></li>
        <li><a href="../about.html" style="color: black;">About</a></li>
        <li><a href="../pricing.html" style="color: black;">Pricing</a></li>
        <li><a href="../prof.html" style="color: black;">Professors</a></li>
        <li><a href="../team.html" style="color: black;">Team</a></li>
        <li><a href="../services.html" style="color: black;">Services</a></li>
        <li class="dropdown has-dropdown"><a href="../blogpage.html"><span style="color: black;">Blog</span> <i class="bi bi-chevron-down"></i></a>
          <ul class="dd-box-shadow">
            <li><a href="../blogpage.html" style="color: black;">Current Areas</a></li>
            <li><a href="../blog.html" style="color: black;">All Blogs</a></li>
            <li><a href="#" style="color: black;">View Posts</a></li>
           
      </ul>
      <li><a href="../contact.html" style="color: black;">Contact</a></li>
      <li class="dropdown has-dropdown"><a href="#"><span style="color: black;">Language</span> <i class="bi bi-chevron-down"></i></a>
        <ul class="dd-box-shadow">
          <li><a href="#">Kinyarwanda</a></li>
          <li><a href="#">English</a></li>
          <li><a href="#">French</a></li>
          <li><a href="#">Kiswahili</a></li>
    </ul>
   
    </nav>
    <i class="mobile-nav-toggle d-xl-none bi bi-list" style="color: black;"></i>
    <a class="btn-getstarted" href="../forms/login&register.php">Log In !</a>

  </div>
    </header>

    <main id="ishimwe-main">
        <div class="ishimwe-container" id="ishimwe-container">
            <div class="ishimwe-form-container ishimwe-sign-up">
                <form id="signupForm" action="createacc.php" method="post">
                    <h1>Create Account</h1>
                    <div class="ishimwe-social-icons">
                        <a href="#" class="icon"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fab fa-github"></i></a>
                        <a href="#" class="icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your email for registration</span>
                    <?php
                    if (isset($errors) && !empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger custom-alert'>$error</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="First Name" name="fname">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Last Name" name="lname">
                        </div>
                    </div>
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <select id="roleSelect" class="form-control" name="role" required style="background-color: #eee; cursor: pointer;">
                        <option value="" disabled selected>Select your role</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    <button type="submit" style="width: 100%;" name="submit">Sign Up</button>
                </form>
            </div>
            <div class="ishimwe-form-container ishimwe-sign-in">
                <form id="ishimwe-signInForm" action="createacc.php" method="POST">
                    <h1>Sign In</h1>
                    <div class="ishimwe-social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your email and password</span>
                 
                    <input type="email" placeholder="Email" name="email" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <a href="#">Forgot your password?</a>
                    <button type="submit" style="width: 100%;" name="submit">Sign In</button>
                </form>
            </div>
            <div class="ishimwe-toggle-container">
                <div class="ishimwe-toggle">
                    <div class="ishimwe-toggle-panel ishimwe-toggle-left">
                        <h1 style="color: #fff;">Welcome Back!</h1>
                        <p>Enter your personal details to use all of site features and get satisfied while using IMBONI </p>
                        <p>Don't have an account?</p>
                        <button class="ishimwe-hidden" id="ishimwe-login">Sign Up</button>
                    </div>
                    <div class="ishimwe-toggle-panel ishimwe-toggle-right">
                        <h1 style="color: #fff;">Work to succeed!</h1>
                        <p>Register with your personal details to use all of site features and keep your continuous growth through process. </p>
                        <p>Already have an account?</p>
                        <button class="ishimwe-hidden" id="ishimwe-register">Sign In</button>
                    </div>
                </div>
            </div>
        </div>
    </main>


 
<footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="../index.html" class="logo d-flex align-items-center">
            <span>ImBoni</span>
          </a>
          <p>Feel free to join us create your free account and start viewing updates and exploring the country's whole education and use it to level up.</p>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="../index.html#hero">Home</a></li>
            <li><a href="../about.html">About us</a></li>
            <li><a href="../services.html">Services</a></li>
            <li><a href="../contact.html">Contact</a></li>
            <li><a href="../team.html">Team</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="../Services/webd.html">Web Design</a></li>
            <li><a href="../Services/gd.html">Graphic Designs</a></li>
            <li><a href="../Services/pm.html">Project Management</a></li>
            <li><a href="../Services/tn.html">Training</a></li>
            <li><a href="../Services/mk.html">Marketing</a></li>
            <li><a href="../Services/invcard.html">Invitations</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
          <h4>Contact Us</h4>
          <p>Kamonyi district</p>
          <p>Ruyenzi , Rugazi</p>
          <p>Rwanda</p>
          <p class="mt-4"><strong>Phone:</strong> <span>+250 781262526</span></p>
          <p><strong>Email:</strong> <span>ishimweghislain82@gmail.com</span></p>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>&copy; <span>Copyright</span> <strong class="px-1">ImBoni</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
    
        Designed by <a href="#">@Ishimweghislain</a><p>See my portfolio</p>
      </div>
    </div>

  </footer>

  <!-- Scroll Top Button -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ishimweContainer = document.getElementById('ishimwe-container');
        const ishimweRegisterBtn = document.getElementById('ishimwe-register');
        const ishimweLoginBtn = document.getElementById('ishimwe-login');
    
        ishimweRegisterBtn.addEventListener('click', () => {
            ishimweContainer.classList.add("ishimwe-active");
        });
    
        ishimweLoginBtn.addEventListener('click', () => {
            ishimweContainer.classList.remove("ishimwe-active");
        });
    });
    // check if it is teacher or student the direct u to the right path
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const roleSelect = document.getElementById('roleSelect');

    form.addEventListener('submit', function(event) {
        // Remove this line to allow form submission
        // event.preventDefault();

        const selectedRole = roleSelect.value;
        if (selectedRole === 'teacher') {
            // Set a hidden input field instead of redirecting
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'role';
            hiddenInput.value = 'teacher';
            form.appendChild(hiddenInput);
        } else if (selectedRole === 'student') {
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'role';
            hiddenInput.value = 'student';
            form.appendChild(hiddenInput);
        }
    });
});
    </script>
  
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>

</body>

</html>