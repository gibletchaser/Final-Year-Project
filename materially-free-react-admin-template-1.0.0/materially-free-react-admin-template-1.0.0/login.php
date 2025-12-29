<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YobYong</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
<style>
        .form-inner form .field{
          height: 50px;
          width: 100%;
          margin-top: 20px;
        }
        .form-inner form .field input{
          height: 100%;
          width: 100%;
          outline: none;
          padding-left: 15px;
          border-radius: 15px;
          border: 1px solid lightgrey;
          border-bottom-width: 2px;
          font-size: 17px;
          transition: all 0.3s ease;
        }
        .form-inner form .field input:focus{
          border-color: #1a75ff;
        }
        .form-inner form .field input::placeholder{
          color: #999;
          transition: all 0.3s ease;
        }
        form .field input:focus::placeholder{
          color: #1a75ff;
        }
        </style>
</head>

<body data-theme="light">

    <nav>
        <div class="logo" aria-label="Portfolio Logo">YobYong</div>
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle-Menu" aria-expanded="false" aria-controls="navbarMenu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <ul id="navbarMenu" role="menu">
            <li role="none"><a href="Ahmad.php" role="menuitem" class="active">Home</a></li>
            <li role="none"><a href="Menu.php" role="menuitem" class="active">Customize</a></li>
            <li role="none"><a href="#project" role="menuitem" class="active">Sales</a></li>
            <li role="none"><a href="#contact" role="menuitem" class="active">Contact</a></li>
        </ul>

        <a href="login.php" class="login-btn">
          <i class="fa-solid fa-right-to-bracket"></i> Login</a>
          
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle Dark/Light theme" title="Toggle Dark/Light Theme">
            <i class="fa-solid fa-moon"></i>
        </button>
    </nav>

      <section class="project" id="project">
        <h2>login</h2>
        <div class="form-container">
                    <div class="slider-tab"></div>
                      </div>
                        <div class="form-inner">
        <!-- Login Form -->
         <form method="POST" action="login_process.php" class="login">
            <div class="field">
            <input type="text" name="username" placeholder="username" required>
              </div>
                <div class="field">
                  <input type="password" name="password" placeholder="Password" required>
                    </div>
                        <div class="field btn">
                    <div class="btn-layer"></div>
                <input type="submit" name="login" value="Login">
            </div>
      </form>
    </div>
    <script src="script.js"></script>

    <script>
  const clickSound = new Audio('Sound/click_button.mp3'); // Make sure path & extension match exactly

  document.querySelectorAll('a, button, #hamburgerBtn, #themeToggle, #project-card, #navbarMenu, #project-link, #active')
    .forEach(el => {
      el.addEventListener('click', () => {
        clickSound.currentTime = 0; // Start from the beginning
        clickSound.play();
      });
    });
</script>

<script>

  const hoverSound = new Audio('Sound/hover_button.mp3'); 
  const clickableElements = document.querySelectorAll('a, button, #project-link, #navbarMenu, #themeToggle');

  clickableElements.forEach(element => {
    element.addEventListener('mouseenter', () => {
      hoverSound.currentTime = 0; 
      hoverSound.play();
    });
  });
</script>
</body>
</html>