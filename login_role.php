<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Role</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* Sama seperti halaman sebelumnya */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(120deg, #f0f2f5 30%, #1a237e 100%);
      overflow: hidden;
    }

    .container {
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: flex-end;
      padding: 0 50px;
      flex-direction: row;
    }

    .background-image {
      width: 100%;
      height: 100%;
      background-image: url('teknik-industri.png');
      background-size: cover;
      background-position: center;
      filter: brightness(0.9);
      position: absolute;
      top: 0;
      left: 0;
      z-index: -1;
    }

    .login-container {
      background: #ffd700;
      padding: 40px;
      border-radius: 20px;
      width: 450px;
      height: 500px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-left: auto;
    }

    .login-container h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
      font-size: 30px;
      font-weight: 800;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 600;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: white;
      box-shadow: 5px 5px 10px 5px rgba(0, 0, 0, 0.1);
    }

    .login-btn {
      width: 100%;
      margin-top: 20px;
      padding: 14px;
      background: #ff5722;
      color: #27224d;
      border: none;
      border-radius: 22px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .login-btn:hover {
      background: #f4511e;
    }

    .register-btn {
      margin-top: 20px;
      text-align: center;
    }

    .register-btn a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .register-btn a:hover {
      color: #ff5722;
    }

    @media (max-width: 768px) {
      .container {
        padding: 20px;
        flex-direction: column;
        justify-content: center;
      }

      .background-image {
        display: none;
      }

      .login-container {
        width: 100%;
        margin-top: 80px;
      }
    }
  </style>
</head>

<body>
  <div class="background-image"></div>
  <div class="container">
    <div>
      <div class="login-container">
        <h1>LOGIN WITH ROLE</h1>
        <form id="loginForm" action="login_role_process.php" method="POST">
          <div class="form-group">
            <label>Role:</label>
            <select id="role" name="role" required>
              <option value="">-- Select Role --</option>
              <option value="kahim">Ketua Himpunan</option>
              <option value="sekhim">Sekretaris Himpunan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
          </div>
          <div class="form-group">
            <label>Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="login-btn">LOGIN</button>
        </form>
        <div class="register-btn">
          <p>Login Sebagai Anggota? <a href="index.html">Login di sini</a></p>
        </div>
      </div>
    </div>
  </div>
</body>

<script>
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    if (username === '') {
      alert('Username cannot be empty');
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      alert('Password must be at least 6 characters long');
      e.preventDefault();
      return;
    }

    if (role === '') {
      alert('Please select a role');
      e.preventDefault();
      return;
    }
  });
</script>

</html>
