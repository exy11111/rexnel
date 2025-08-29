<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">

            <a href="supplier.php" class="logo text-white fw-bold">
                <img src="assets/img/holicon.png" alt="navbar brand" class="navbar-brand" height="40">&nbsp;House of Local
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>

        </div>
        <!-- End Logo Header -->
    </div>
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">

        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <!-- notif area -->
                <?php 
                    if(isset($_SESSION['user_id'])){
                        $sql = "SELECT username, email, firstname, lastname, created_at, is_verified, profile_photo FROM users JOIN userdetails ON users.user_id = userdetails.user_id WHERE users.user_id = :user_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(":user_id", $_SESSION['user_id']);
                        $stmt->execute();
                        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($user_data) {
                            $username = $user_data['username'];
                            $email = $user_data['email'];
                            $firstname = $user_data['firstname'];
                            $lastname = $user_data['lastname'];
                            $fullname = $firstname . " " . $lastname;
                            $created_at = $user_data['created_at'];
                            $profile_photo = $user_data['profile_photo'];
                            $is_verified = $user_data['is_verified'];
                        } else {
                            $username = "User not found.";
                            $email = "User not found.";
                        }
                    }

                    if (isset($_GET['notif_id'])) {
                        $notif_id = $_GET['notif_id'];
                    
                        $sql = "UPDATE notifications SET seen = 1 WHERE notification_id = :notif_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':notif_id', $notif_id, PDO::PARAM_INT);
                        $stmt->execute();

                        $sql = "SELECT target_url FROM notifications WHERE notification_id = :notif_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':notif_id', $notif_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $notif = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($notif && isset($notif['target_url'])) {
                            $target_url = $notif['target_url'];
                            echo "<script>window.location = '$target_url';</script>";
                            exit();
                        }
                    }
                
                    $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $notifications = $stmt->fetchAll();
                    $notif_count = count(array_filter($notifications, function($notif) {
                        return $notif['seen'] == 0;
                    }));

                    function timeAgo($datetime) {
                        date_default_timezone_set('Asia/Manila');
                    
                        $timestamp = strtotime($datetime);
                        if (!$timestamp || $timestamp > time()) return 'just now';
                    
                        $diff = time() - $timestamp;
                    
                        $units = [
                            'year'   => 31536000,
                            'month'  => 2592000,
                            'week'   => 604800,
                            'day'    => 86400,
                            'hour'   => 3600,
                            'minute' => 60,
                            'second' => 1
                        ];
                    
                        foreach ($units as $unit => $seconds) {
                            $value = floor($diff / $seconds);
                            if ($value >= 1) {
                                $label = $value == 1 ? $unit : $unit . 's';
                                return "$value $label ago";
                            }
                        }
                    
                        return 'just now';
                    }
                    
                ?>
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="notifDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fa fa-bell"></i>
                        <?php if ($notif_count > 0): ?>
                            <span class="notification"><?= $notif_count ?></span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">You have <?= $notif_count ?> new notification</div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    <?php foreach($notifications as $row):?>
                                        <a href="?notif_id=<?php echo $row['notification_id']; ?>" class="<?= $row['seen'] == 0 ? 'bg-light' : '' ?> p-2 rounded">
                                            <div class="notif-icon notif-primary flex-shrink-0" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi <?php echo $row['icon']?>"></i>
                                            </div>
                                            <div class="notif-content">
                                                <span class="block"> <?php echo $row['message']?> </span>
                                                <span class="time"><?= timeAgo($row['created_at']) ?></span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                        <img src="<?php echo !empty($profile_photo) ? $profile_photo : 'assets/img/profile.png'; ?>" 
                            alt="..." 
                            class="avatar-img rounded-circle">
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span> <span class="fw-bold"><?php echo $username?></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                    <img src="<?php echo !empty($profile_photo) ? $profile_photo : 'assets/img/profile.png'; ?>" 
                                        alt="..." 
                                        class="avatar-img rounded-circle">
                                    </div>
                                    <div class="u-text">
                                        <h4><?php echo $username?></h4>
                                        <p class="text-muted"><?php echo $email?></p><a href="#" class="btn btn-xs btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <?php if($_SESSION['user_id'] == 17):?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="adminportal.php">Change Branch</a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" id="accountSetting" data-bs-toggle="modal" data-bs-target="#editAccountModal" data-id="<?php if(isset($_SESSION['user_id'])){echo $_SESSION['user_id'];}  ?>">Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a id="logoutBtn" class="dropdown-item" href="#">Logout</a>
                            </li>
                            <script>
                                document.getElementById('logoutBtn').addEventListener('click', function() {
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "Do you want to logout?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, Logout!',
                                        cancelButtonText: 'Cancel',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "logout.php";
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </ul>
                </li>
                
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>