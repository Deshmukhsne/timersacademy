<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin1 Login</title>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');

        :root {
            --first-color: linear-gradient(90deg, #ff4040, #470000);
            --first-color-dark: #2c2f33;
            --first-color-light: #A49EAC;
            --first-color-lighten: #FFFFFF;
            --shadow: 0 8px 20px rgba(35, 0, 77, .2);
            --body-font: 'Open Sans', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--body-font);
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login__content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .login__img img {
            width: 200px;
            border-radius: 0.5rem;
        }

        .login__forms {
            width: 100%;
            max-width: 400px;
        }

        .login__form {
            background-color: var(--first-color-lighten);
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .login__title {
            margin-bottom: 1.5rem;
            color: var(--first-color-dark);
            font-size: 1.5rem;
        }

        .login__box {
            display: flex;
            align-items: center;
            background-color: #F8F9FA;
            margin-top: 1rem;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            position: relative;
        }

        .login__box:focus-within {
            border-color: #ff4040;
            box-shadow: 0 0 0 0.2rem rgba(255, 64, 64, 0.25);
        }

        .login__icon {
            font-size: 1.5rem;
            /* background: whitesmoke; */


            margin-right: 0.5rem;
        }

        .login__input {
            border: none;
            outline: none;
            font-size: 1rem;
            font-weight: 600;
            color: var(--first-color-dark);
            background: transparent;
            width: 100%;
            padding-right: 2rem;
        }

        .login__input::placeholder {
            color: var(--first-color-light);
            font-weight: 400;
        }

        .login__toggle-password {
            position: absolute;
            right: 1rem;
            font-size: 1.2rem;
            background: var(--first-color);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            cursor: pointer;
        }

        .login__button {
            width: 100%;
            padding: 1rem;
            margin: 1.5rem 0;
            background: var(--first-color);
            color: #FFF;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login__button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 64, 64, 0.3);
        }

        @media screen and (min-width: 768px) {
            .login__content {
                flex-direction: row;
                gap: 3rem;
            }

            .login__img img {
                width: 280px;
            }
        }
    </style>
</head>

<body>
    <div class="login">
        <div class="login__content">
            <div class="login__img">
                <img src="<?php echo base_url('assets/Images/timeersbadmintonacademy_logo.png'); ?>" alt="Logo">
            </div>
            <div class="login__forms">
                <form action="<?php echo base_url('auth/handle_login'); ?>" method="post" class="login__form" id="login-form">
                    <h1 class="login__title">Login Page</h1>
                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="username" placeholder="Username" class="login__input" required>
                    </div>
                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Password" class="login__input" required>
                        <i class='bx bx-show login__toggle-password'></i>
                    </div>
                    <button type="submit" class="login__button">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.login__toggle-password').addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.replace('bx-show', 'bx-hide');
            } else {
                passwordInput.type = 'password';
                this.classList.replace('bx-hide', 'bx-show');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $error; ?>',
                confirmButtonColor: '#ff4040'
            });
        </script>
    <?php endif; ?>
</body>

</html>