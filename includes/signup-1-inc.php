

<?php require_once ("../meta/$page-$lang.php");?>

<style>
<style>
.app-signup-banner {
    position: relative;
    background: url('<?= htmlspecialchars($app_info['signup_top_img_url']) ?>') no-repeat center;
    background-size: contain;
    transition: background 0.4s ease-in-out;
}

/* Overlay for dark mode using ::before */
@media (prefers-color-scheme: dark) {
    .app-signup-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('<?= htmlspecialchars($app_info['signup_top_img_dark_url']) ?>') no-repeat center;
        background-size: contain;
        opacity: 1;
        transition: opacity 0.4s ease-in-out;
        z-index: 1;
        pointer-events: none;
    }
}

/* Hide the ::before layer in light mode */
@media (prefers-color-scheme: light) {
    .app-signup-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
        z-index: 1;
        pointer-events: none;
    }
}
</style>


</style>






<?php require_once ("../header-2025.php");?>



