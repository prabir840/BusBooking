<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $server = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "bookbus"; // Replace with your database name

    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Database connection
    $conn = new mysqli($server, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is logging in
    if (isset($_POST['loginbtn']) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM `bus` WHERE `email` = ? AND `Password` = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Redirect to travel1.html if credentials are valid
            header("Location: travel1.html");
            exit();
        } else {
            echo "<span style='color: red;'>Invalid email or password. Please try again.</span>";
        }
        $stmt->close();
    }

    // Check if the user is signing up
    if (isset($_POST['signupbtn']) && !empty($email) && !empty($password) && !empty($name)) {
        $stmt = $conn->prepare("INSERT INTO `bus` (`name`, `email`, `Password`, `Date`) VALUES (?, ?, ?, current_timestamp())");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<span style='color: green;'>Signup details saved successfully!</span>";
        } else {
            echo "<span style='color: red;'>Error: Could not save signup details. Please try again later.</span>";
        }
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stylish Login & Sign Up</title>
  <style>
    body {
      background: linear-gradient(to top, #e8eff3, #92b3c1, #7acbdd);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
      height: 100vh;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    .glowing-circle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.6;
      z-index: 0;
      animation: pulse 3s ease-in-out infinite alternate;
    }

    .circle1 {
      width: 600px;
      height: 600px;
      background: conic-gradient(#6a11cb, #2575fc, #6a11cb);
      border: 8px solid rgba(106, 17, 203, 0.8);
      box-shadow: 0 0 40px #6a11cb, 0 0 80px #2575fc, 0 0 120px #6a11cb;
      animation: rotateCircle 5s linear infinite, pulse 3s ease-in-out infinite alternate;
    }

    .circle2 {
      width: 400px;
      height: 400px;
      background: linear-gradient(to top, #e8eff3, #92b3c1, #7acbdd);
      border: 6px solid rgba(255, 0, 204, 0.7);
      box-shadow: 0 0 30px #ff00cc, 0 0 60px #333399, 0 0 90px #ff00cc;
      animation: rotateCircleReverse 7s linear infinite, pulse 4s ease-in-out infinite alternate;
    }

    @keyframes rotateCircle {
      0% {
        transform: rotate(0deg) scale(1);
      }

      100% {
        transform: rotate(360deg) scale(1);
      }
    }

    @keyframes rotateCircleReverse {
      0% {
        transform: rotate(360deg) scale(1);
      }

      100% {
        transform: rotate(0deg) scale(1);
      }
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 0.6;
      }

      100% {
        transform: scale(1.1);
        opacity: 0.8;
      }
    }

    .form-wrapper {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 30px 20px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      text-align: center;
      animation: fadeIn 1s ease-in-out;
      width: 250px;
      z-index: 1;
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: scale(0.8);
      }

      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    h2 {
      margin-bottom: 15px;
      color: #ffffff;
      font-size: 22px;
      letter-spacing: 1px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    input:focus {
      background: rgba(31, 124, 239, 0.228);
      outline: none;
      transform: scale(1.05);
    }

    button {
      width: 100%;
      padding: 10px;
      margin-top: 12px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      transition: transform 0.3s, background 0.3s;
    }

    button:hover {
      transform: scale(1.05);
      background: linear-gradient(to right, #2575fc, #6a11cb);
    }

    .toggle-btn {
      margin-top: 15px;
      color: #ccc;
      font-size: 13px;
      cursor: pointer;
      text-decoration: underline;
      transition: color 0.3s;
    }

    .toggle-btn:hover {
      color: #fff;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body>

  <div class="glowing-circle circle1"></div>
  <div class="glowing-circle circle2"></div>
  <!-- Login Form -->
  <div class="form-wrapper" id="loginForm">
    <h2>Login</h2>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="loginbtn">Login</button>
    </form>
    <div class="toggle-btn" onclick="toggleForm('signup')">Don't have an account? Sign Up</div>


  </div>
  <!-- Sign-Up Form -->
  <div class="form-wrapper hidden" id="signupForm">
    <h2>Sign Up</h2>
    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="signupbtn">Sign Up</button>
    </form>
    <div class="toggle-btn" onclick="toggleForm('login')">Already have an account? Login</div>


  </div>



  <script>
    function toggleForm(form) {
      if (form === 'signup') {
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('signupForm').classList.remove('hidden');
      } else {
        document.getElementById('signupForm').classList.add('hidden');
        document.getElementById('loginForm').classList.remove('hidden');
      }
    }

    function login() {
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      alert(`Logged in with Email: ${email}`);
    }

    function signup() {
      const username = document.getElementById('signupUsername').value;
      const email = document.getElementById('signupEmail').value;
      const password = document.getElementById('signupPassword').value;
      alert(`Signed up with Username: ${username}`);
    }
  </script>

</body>

</html>