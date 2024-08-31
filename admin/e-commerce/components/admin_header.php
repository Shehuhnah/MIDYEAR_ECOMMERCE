<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">
<style>
        body {
            margin-top: 100px;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #F8F4E1;
            color: aliceblue;
        }

        .message {
            background-color: #ffeb3b;
            color: aliceblue;
            padding: 10px 15px;
            margin: 20px auto;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message i {
            cursor: pointer;
            color: aliceblue;
        }

        .header {
            background-color: #2C1A11;
            color: aliceblue;
            padding: 10px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header .logo {
            font-size: 24px;
            color: #fff;
            text-decoration: none;
            text-align: left;
            flex: 1;
        }

        .header .logo span {
            color: #ffb400;
        }

        .header .hamburger {
            display: none;
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 15px;
            color: aliceblue;
            flex: 2; /* Allow navbar to take up space */
        }

        .navbar a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
            
        }

        .navbar a:hover {
            color: #74512D;
        }

        .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icons .fas {
            font-size: 20px;
            cursor: pointer;
            color: #fff;
        }

        .profile {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            position: absolute;
            top: 60px;
            right: 20px;
            width: 250px;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .profile .btn, .profile .option-btn, .profile .delete-btn {
            display: block;
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .profile .btn {
            background-color: #74512D;
        }

        .profile .option-btn {
            background-color: #5A9BD5;
        }

        .profile .delete-btn {
            background-color: #e74c3c;
        }

        .profile .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .navbar {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #AF8F6F;
                position: absolute;
                top: 60px;
                left: 0;
                z-index: 1000;
            }

            .navbar.active {
                display: flex;
            }

            .header .hamburger {
                display: block;
                font-size: 24px;
                cursor: pointer;
            }

        }

    </style>

    <section class="flex">
        <a href="dashboard.php" class="logo" style="text-align:left; padding-right:10%; color:aliceblue">Admin <br>Side</a>

        <div class="hamburger" onclick="toggleNavbar()">☰</div>

        <nav class="navbar">
            <a href="dashboard.php" style="color: white;">home</a>
            <a href="products.php" style="color: white;">products</a>
            <a href="placed_orders.php" style="color: white;">orders</a>
            <a href="admin_accounts.php" style="color: white;">admins</a>
            <a href="users_accounts.php" style="color: white;">users</a>
            <a href="messages.php" style="color: white;">messages</a>
        </nav>




        <div class="profile">
            <?php
            // Prepare and execute the query using MySQLi
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->bind_param("i", $admin_id);
            $select_profile->execute();
            $result = $select_profile->get_result();

            if ($result->num_rows > 0) {
                $fetch_profile = $result->fetch_assoc(); // No arguments needed here
            ?>
                <p><?= $fetch_profile['name']; ?></p>
                <a href="../admin/update_profile.php" class="btn">update profile</a>
                <div class="flex-btn">
                    <a href="../admin/register_admin.php" class="option-btn">register</a>
                    <a href="../admin/admin_login.php" class="option-btn">login</a>
                </div>
                <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
            <?php
            } else {
                echo '<p>No profile found</p>';
            }
            ?>
        </div>

    </section>

</header>
 