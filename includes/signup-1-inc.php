

<?php require_once ("../meta/$page-$lang.php");?>

<style>
    @media (prefers-color-scheme: light) {
        .app-signup-banner {
            background: url('<?= htmlspecialchars($app_info['signup_top_img_url']) ?>?v=2') no-repeat center;
            background-size: contain;
        }
    }

    @media (prefers-color-scheme: dark) {
        .app-signup-banner {
            background: url('<?= htmlspecialchars($app_info['signup_top_img_dark_url']) ?>?v=2') no-repeat center;
            background-size: contain;
        }
    }
</style>






<?php require_once ("../header-2025.php");?>



